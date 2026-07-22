<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Show the customer registration + loan application form.
     */
    public function create(): View
    {
        $loanProducts = LoanProduct::where('status', 'active')->get();

        return view('user.pages.customer-add', [
            'loanProducts' => $loanProducts,
        ]);
    }

    /**
     * Store Customer, Business (if SME), Documents, and Loan Application
     * all together in a single transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        $isSme = $request->input('customer_type') === 'sme';

        $rules = [
            'customer_type'  => ['required', Rule::in(Customer::CUSTOMER_TYPES)],
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'middle_name'    => 'nullable|string|max:100',
            'date_of_birth'  => 'nullable|date|before:today',
            'gender'         => ['nullable', Rule::in(Customer::GENDERS)],
            'national_id'    => 'nullable|string|max:100|unique:customers,national_id',
            'email'          => 'nullable|email|max:255|unique:customers,email',
            'phone_primary'  => 'required|string|max:20',
            'phone_secondary'=> 'nullable|string|max:20',
            'occupation'     => 'nullable|string|max:150',
            'monthly_income' => 'nullable|numeric|min:0',
            'country'        => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:100',
            'local_government_area' => 'nullable|string|max:100',
            'address'        => 'nullable|string',
            'status'         => ['nullable', Rule::in(Customer::STATUSES)],

            'documents'                        => 'nullable|array',
            'documents.*.document_type'        => ['nullable', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
            'documents.*.file'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'documents.*.verification_status'  => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],

            // Loan Application
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'loan_amount'     => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'purpose'         => ['required', 'string', 'max:1000'],
        ];

        if ($isSme) {
            $rules = array_merge($rules, [
                'business_name'                  => ['required', 'string', 'max:200'],
                'registration_number'            => ['nullable', 'string', 'max:100'],
                'tax_number'                      => ['nullable', 'string', 'max:100'],
                'business_type'                   => ['nullable', 'string', 'max:100'],
                'business_monthly_revenue'        => ['nullable', 'numeric', 'min:0'],
                'business_monthly_expense'        => ['nullable', 'numeric', 'min:0'],
                'business_address'                => ['nullable', 'string'],
                'business_city'                    => ['nullable', 'string', 'max:100'],
                'business_state'                   => ['nullable', 'string', 'max:100'],
                'business_local_government_area'   => ['nullable', 'string', 'max:100'],
            ]);
        }

        $validated = $request->validate($rules, [
            'business_name.required' => 'Business name is required for SME customers.',
            'loan_product_id.exists' => 'Selected loan product is invalid.',
        ]);

        // Loan product ka type & amount range double-check (server-side safety)
        $loanProduct = LoanProduct::where('id', $validated['loan_product_id'])
            ->where('status', 'active')
            ->first();

        if (!$loanProduct) {
            return back()
                ->withInput()
                ->withErrors(['loan_product_id' => 'Selected loan product is currently unavailable.']);
        }

        if ($loanProduct->loan_type !== $validated['customer_type']) {
            return back()
                ->withInput()
                ->withErrors(['loan_product_id' => 'Selected loan product does not match the customer type.']);
        }

        if ($validated['loan_amount'] < $loanProduct->minimum_amount || $validated['loan_amount'] > $loanProduct->maximum_amount) {
            return back()
                ->withInput()
                ->withErrors([
                    'loan_amount' => 'Loan amount must be between ₦' . number_format($loanProduct->minimum_amount, 0)
                        . ' and ₦' . number_format($loanProduct->maximum_amount, 0) . ' for this product.',
                ]);
        }

        try {
            DB::beginTransaction();

            // 1. Customer
            $customerData = collect($validated)->only([
                'customer_type', 'first_name', 'last_name', 'middle_name', 'date_of_birth',
                'gender', 'national_id', 'email', 'phone_primary', 'phone_secondary',
                'occupation', 'monthly_income', 'country', 'state', 'city',
                'local_government_area', 'address', 'status',
            ])->toArray();

            $customerData['customer_code'] = Customer::generateCustomerCode();
            $customerData['status'] = $customerData['status'] ?? 'active';

            $customer = Customer::create($customerData);

            // 2. Business (SME only)
            $business = null;

            if ($isSme) {
                $business = Business::create([
                    'customer_id'            => $customer->id,
                    'business_name'           => $validated['business_name'],
                    'registration_number'     => $validated['registration_number'] ?? null,
                    'tax_number'              => $validated['tax_number'] ?? null,
                    'business_type'           => $validated['business_type'] ?? null,
                    'monthly_revenue'         => $validated['business_monthly_revenue'] ?? null,
                    'monthly_expense'         => $validated['business_monthly_expense'] ?? null,
                    'address'                 => $validated['business_address'] ?? null,
                    'city'                    => $validated['business_city'] ?? null,
                    'state'                   => $validated['business_state'] ?? null,
                    'local_government_area'   => $validated['business_local_government_area'] ?? null,
                    'status'                  => 'active',
                ]);
            }

            // 3. Documents
            foreach ($request->file('documents', []) as $index => $docFiles) {
                $file = $docFiles['file'] ?? null;

                if (! $file) {
                    continue;
                }

                $path = $file->store('customer-documents', 'public');

                $customer->documents()->create([
                    'document_type'        => $validated['documents'][$index]['document_type'] ?? 'other',
                    'file_path'            => $path,
                    'verification_status'  => $validated['documents'][$index]['verification_status'] ?? 'pending',
                ]);
            }

            // 4. Loan Application
            $loanApplication = LoanApplication::create([
                'application_no'  => LoanApplication::generateApplicationNo(),
                'customer_id'      => $customer->id,
                'business_id'      => $business?->id, // personal ke liye null
                'loan_product_id'  => $loanProduct->id,
                'loan_amount'      => $validated['loan_amount'],
                'duration_months'  => $validated['duration_months'],
                'purpose'          => $validated['purpose'],
                'status'           => 'submitted',
                'application_date' => now()->toDateString(),
            ]);

            DB::commit();

            return redirect()
                ->route('staff.customer.create')
                ->with('success', 'Customer "' . $customer->full_name . '" (' . $customer->customer_code . ') registered successfully. Loan application ' . $loanApplication->application_no . ' has been submitted.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create customer: ' . $e->getMessage()]);
        }
    }
}