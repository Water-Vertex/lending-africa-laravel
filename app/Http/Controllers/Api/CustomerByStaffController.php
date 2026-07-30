<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CustomerByStaffMail;
use App\Models\Bank;
use App\Models\Business;
use App\Models\CoSigner;
use App\Models\Customer;
use App\Models\CustomerBankAccount;
use App\Models\CustomerByStaff;
use App\Models\CustomerDocument;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;


class CustomerByStaffController extends Controller
{  public function loanProducts(Request $request): JsonResponse
    {
        $query = LoanProduct::where('status', 'active');

        if ($request->filled('type')) {
            $query->where('loan_type', $request->input('type'));
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
        ]);
    }

    /**
     * GET /api/staff/banks
     */
    public function banks(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Bank::where('status', 'active')->get(),
        ]);
    }

    /**
     * POST /api/staff/check-email
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $exists = Customer::where('email', $request->input('email'))->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    /**
     * GET /api/staff/customers
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);

        $customers = CustomerByStaff::with([
            'customer',
            'customer.business',
            'customer.documents',
            'customer.bankAccounts',
            'customer.bankAccounts.bank',
            'customer.loanApplications',
            'customer.loanApplications.coSigner',
        ])
        ->where('staff_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    /**
     * POST /api/staff/customers
     * Flow: Customer -> Business (SME) -> Bank Account -> Documents ->
     *       Loan Application -> Co-signer -> Customer-by-Staff link -> Email PDF
     */
    public function store(Request $request): JsonResponse
    {
        /** @var \App\Models\Staff $staff */
        $staff = Auth::guard('staff')->user();

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

            // Documents
            'documents'                        => 'nullable|array',
            'documents.*.document_type'        => ['nullable', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
            'documents.*.file'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'documents.*.verification_status'  => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],

            // Bank Details
            'bank_id'         => ['required', 'exists:banks,id'],
            'account_name'    => ['required', 'string', 'max:150'],
            'account_number'  => ['required', 'string', 'max:50'],

            // Co-signer
            'cosigner_first_name'             => ['required', 'string', 'max:100'],
            'cosigner_last_name'              => ['required', 'string', 'max:100'],
            'cosigner_middle_name'            => ['nullable', 'string', 'max:100'],
            'cosigner_date_of_birth'          => ['nullable', 'date', 'before:today'],
            'cosigner_occupation'             => ['nullable', 'string', 'max:150'],
            'cosigner_evidence_of_occupation' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'cosigner_email'                  => ['nullable', 'email', 'max:255'],
            'cosigner_phone_primary'          => ['required', 'string', 'max:20'],
            'cosigner_phone_secondary'        => ['nullable', 'string', 'max:20'],
            'cosigner_address'                => ['nullable', 'string'],
            'cosigner_city'                   => ['nullable', 'string', 'max:100'],
            'cosigner_state'                  => ['nullable', 'string', 'max:100'],
            'cosigner_country'                => ['nullable', 'string', 'max:100'],
            'cosigner_bvn'                    => ['nullable', 'string', 'max:20'],
            'cosigner_photo_id'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'cosigner_relationship'           => ['nullable', 'string', 'max:100'],

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
                'business_local_government_area'  => ['nullable', 'string', 'max:100'],
            ]);
        }

        $validated = $request->validate($rules, [
            'business_name.required'          => 'Business name is required for SME customers.',
            'loan_product_id.exists'          => 'Selected loan product is invalid.',
            'bank_id.exists'                  => 'Selected bank is invalid.',
            'cosigner_first_name.required'    => 'Co-signer first name is required.',
            'cosigner_last_name.required'     => 'Co-signer last name is required.',
            'cosigner_phone_primary.required' => 'Co-signer phone number is required.',
        ]);

        $loanProduct = LoanProduct::where('id', $validated['loan_product_id'])
            ->where('status', 'active')
            ->first();

        if (!$loanProduct) {
            return response()->json([
                'success' => false,
                'errors'  => ['loan_product_id' => ['Selected loan product is currently unavailable.']],
            ], 422);
        }

        if ($loanProduct->loan_type !== $validated['customer_type']) {
            return response()->json([
                'success' => false,
                'errors'  => ['loan_product_id' => ['Selected loan product does not match the customer type.']],
            ], 422);
        }

        if ($validated['loan_amount'] < $loanProduct->minimum_amount || $validated['loan_amount'] > $loanProduct->maximum_amount) {
            return response()->json([
                'success' => false,
                'errors'  => [
                    'loan_amount' => [
                        'Loan amount must be between ₦' . number_format($loanProduct->minimum_amount, 0)
                            . ' and ₦' . number_format($loanProduct->maximum_amount, 0) . ' for this product.',
                    ],
                ],
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1) Customer
            $customerData = collect($validated)->only([
                'customer_type', 'first_name', 'last_name', 'middle_name', 'date_of_birth',
                'gender', 'national_id', 'email', 'phone_primary', 'phone_secondary',
                'occupation', 'monthly_income', 'country', 'state', 'city',
                'local_government_area', 'address', 'status',
            ])->toArray();

            $customerData['customer_code'] = Customer::generateCustomerCode();
            $customerData['status'] = $customerData['status'] ?? 'active';

            $customer = Customer::create($customerData);

            // 2) Business (SME only)
            $business = null;

            if ($isSme) {
                $business = Business::create([
                    'customer_id'            => $customer->id,
                    'business_name'          => $validated['business_name'],
                    'registration_number'    => $validated['registration_number'] ?? null,
                    'tax_number'             => $validated['tax_number'] ?? null,
                    'business_type'          => $validated['business_type'] ?? null,
                    'monthly_revenue'        => $validated['business_monthly_revenue'] ?? null,
                    'monthly_expense'        => $validated['business_monthly_expense'] ?? null,
                    'address'                => $validated['business_address'] ?? null,
                    'city'                   => $validated['business_city'] ?? null,
                    'state'                  => $validated['business_state'] ?? null,
                    'local_government_area'  => $validated['business_local_government_area'] ?? null,
                    'status'                 => 'active',
                ]);
            }

            // 3) Bank Account
            $bankAccount = CustomerBankAccount::create([
                'customer_id'    => $customer->id,
                'bank_id'        => $validated['bank_id'],
                'account_name'   => $validated['account_name'],
                'account_number' => $validated['account_number'],
            ]);

            // 4) Documents
            foreach ($request->file('documents', []) as $index => $docFiles) {
                $file = $docFiles['file'] ?? null;

                if (!$file) {
                    continue;
                }

                $path = $file->store('customer-documents', 'public');

                $customer->documents()->create([
                    'document_type'        => $validated['documents'][$index]['document_type'] ?? 'other',
                    'file_path'            => $path,
                    'verification_status'  => $validated['documents'][$index]['verification_status'] ?? 'pending',
                ]);
            }

            // 5) Loan Application
            $loanApplication = LoanApplication::create([
                'application_no'  => LoanApplication::generateApplicationNo(),
                'customer_id'     => $customer->id,
                'business_id'     => $business?->id,
                'loan_product_id' => $loanProduct->id,
                'loan_amount'     => $validated['loan_amount'],
                'duration_months' => $validated['duration_months'],
                'purpose'         => $validated['purpose'],
                'status'          => 'submitted',
                'application_date' => now()->toDateString(),
            ]);

            // 6) Co-signer
            $evidencePath = null;
            if ($request->hasFile('cosigner_evidence_of_occupation')) {
                $evidencePath = $request->file('cosigner_evidence_of_occupation')
                    ->store('cosigner-documents', 'public');
            }

            $photoIdPath = null;
            if ($request->hasFile('cosigner_photo_id')) {
                $photoIdPath = $request->file('cosigner_photo_id')
                    ->store('cosigner-documents', 'public');
            }

            $coSigner = CoSigner::create([
                'application_id'         => $loanApplication->id,
                'first_name'             => $validated['cosigner_first_name'],
                'last_name'              => $validated['cosigner_last_name'],
                'middle_name'            => $validated['cosigner_middle_name'] ?? null,
                'date_of_birth'          => $validated['cosigner_date_of_birth'] ?? null,
                'occupation'             => $validated['cosigner_occupation'] ?? null,
                'evidence_of_occupation' => $evidencePath,
                'email'                  => $validated['cosigner_email'] ?? null,
                'phone_primary'          => $validated['cosigner_phone_primary'],
                'phone_secondary'        => $validated['cosigner_phone_secondary'] ?? null,
                'address'                => $validated['cosigner_address'] ?? null,
                'city'                   => $validated['cosigner_city'] ?? null,
                'state'                  => $validated['cosigner_state'] ?? null,
                'country'                => $validated['cosigner_country'] ?? null,
                'bvn'                    => $validated['cosigner_bvn'] ?? null,
                'photo_id'               => $photoIdPath,
                'relationship'           => $validated['cosigner_relationship'] ?? null,
            ]);

            // 7) Customer-by-Staff link
            CustomerByStaff::create([
                'customer_id'   => $customer->id,
                'customer_code' => $customer->customer_code,
                'staff_id'      => $staff->id,
                'staff_code'    => $staff->staff_code,
            ]);

            DB::commit();

            // 8) Send the loan application confirmation email with PDF attachment
            try {
                if (!empty($customer->email)) {
                    Mail::to($customer->email)->send(
                        new CustomerByStaffMail(
                            $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
                            $loanApplication->fresh(['loanProduct', 'business', 'coSigner'])
                        )
                    );
                }
            } catch (\Exception $mailException) {
                Log::error('Failed to send loan application email: ' . $mailException->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer "' . $customer->full_name . '" (' . $customer->customer_code . ') registered successfully. Loan application ' . $loanApplication->application_no . ' has been submitted.',
                'data'    => [
                    'customer'         => $customer->fresh(['documents', 'business']),
                    'bank_account'     => $bankAccount,
                    'co_signer'        => $coSigner,
                    'loan_application' => $loanApplication,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'errors'  => ['error' => ['Failed to create customer: ' . $e->getMessage()]],
            ], 500);
        }
    }
}