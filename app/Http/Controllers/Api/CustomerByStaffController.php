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
use App\Models\LoanAmount;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class CustomerByStaffController extends Controller
{
    /**
     * NOTE: Is controller ke kaam karne ke liye LoanApplication model mein
     * ye relation add karna zaroori hai (agar pehle se nahi hai):
     *
     *   public function loanAmount(): \Illuminate\Database\Eloquent\Relations\HasOne
     *   {
     *       return $this->hasOne(LoanAmount::class, 'loan_application_id');
     *   }
     */

    public function loanProducts(Request $request): JsonResponse
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

        $query = Customer::where('email', $request->input('email'));

        // ✅ Agar edit mode se ignore_id bheja gaya ho (khud ka email check na ho)
        if ($request->filled('ignore_id')) {
            $query->where('id', '!=', $request->input('ignore_id'));
        }

        $exists = $query->exists();

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
            'customer.loanApplications.loanAmount',
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
     * GET /api/staff/customers/{id}
     * Edit form ke liye ek customer ka pura data (business, bank, documents,
     * loan application + co-signer + calculation) load karta hai. Sirf wohi
     * staff dekh sakta hai jisne is customer ko register kiya tha.
     */
    public function show($id): JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        $link = CustomerByStaff::where('customer_id', $id)
            ->where('staff_id', $staff->id)
            ->first();

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found or you do not have access to edit this record.',
            ], 404);
        }

        $customer = Customer::with([
            'business',
            'documents',
            'bankAccounts.bank',
            'loanApplications' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'loanApplications.coSigner',
            'loanApplications.loanProduct',
            'loanApplications.loanAmount',
        ])->find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $customer,
        ]);
    }

    /**
     * POST /api/staff/customers/{id}/update
     * (POST use kiya hai kyunke multipart/form-data + PUT Laravel mein
     *  files ke sath reliably parse nahi hota — Angular se bhi POST hi bheja jaye)
     *
     * Flow: Customer update -> Business (SME) update/remove -> Bank Account update ->
     *       Documents (remove + add new) -> Loan Application update -> Loan Amount
     *       recalculation -> Co-signer update
     */
    public function update(Request $request, $id): JsonResponse
    {
        /** @var \App\Models\Staff $staff */
        $staff = Auth::guard('staff')->user();

        $link = CustomerByStaff::where('customer_id', $id)
            ->where('staff_id', $staff->id)
            ->first();

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found or you do not have access to edit this record.',
            ], 404);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $isSme = $request->input('customer_type') === 'sme';

        $rules = [
            'customer_type'  => ['required', Rule::in(Customer::CUSTOMER_TYPES)],
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'middle_name'    => 'nullable|string|max:100',
            'date_of_birth'  => 'nullable|date|before:today',
            'gender'         => ['nullable', Rule::in(Customer::GENDERS)],
            'national_id'    => ['nullable', 'string', 'max:100', Rule::unique('customers', 'national_id')->ignore($customer->id)],
            'email'          => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
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

            // New documents only (existing ones are handled via removed_document_ids)
            'documents'                        => 'nullable|array',
            'documents.*.document_type'        => ['nullable', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
            'documents.*.file'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'documents.*.verification_status'  => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],

            // Existing documents that the staff removed on the form
            'removed_document_ids'   => 'nullable|array',
            'removed_document_ids.*' => 'integer|exists:customer_documents,id',

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

            // 1) Customer — update
            $customerData = collect($validated)->only([
                'customer_type', 'first_name', 'last_name', 'middle_name', 'date_of_birth',
                'gender', 'national_id', 'email', 'phone_primary', 'phone_secondary',
                'occupation', 'monthly_income', 'country', 'state', 'city',
                'local_government_area', 'address', 'status',
            ])->toArray();

            $customerData['status'] = $customerData['status'] ?? $customer->status ?? 'active';

            $customer->update($customerData);

            // 2) Business (SME only) — update if exists, create if newly switched to SME,
            //    remove if switched away from SME
            $business = $customer->business;

            if ($isSme) {
                $businessData = [
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
                ];

                if ($business) {
                    $business->update($businessData);
                } else {
                    $business = Business::create(array_merge(['customer_id' => $customer->id], $businessData));
                }
            } elseif ($business) {
                // Customer switched from SME to personal — drop the business record
                $business->delete();
                $business = null;
            }

            // 3) Bank Account — update the existing one
            $bankAccount = $customer->bankAccounts()->first();

            $bankData = [
                'bank_id'        => $validated['bank_id'],
                'account_name'   => $validated['account_name'],
                'account_number' => $validated['account_number'],
            ];

            if ($bankAccount) {
                $bankAccount->update($bankData);
            } else {
                $bankAccount = CustomerBankAccount::create(array_merge(['customer_id' => $customer->id], $bankData));
            }

            // 4) Documents — remove any the staff deleted on the form
            $removedIds = $validated['removed_document_ids'] ?? [];
            if (!empty($removedIds)) {
                $docsToRemove = $customer->documents()->whereIn('id', $removedIds)->get();
                foreach ($docsToRemove as $doc) {
                    if ($doc->file_path) {
                        Storage::disk('public')->delete($doc->file_path);
                    }
                    $doc->delete();
                }
            }

            // Documents — append any newly uploaded ones
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

            // 5) Loan Application — update the most recent application
            $loanApplication = $customer->loanApplications()->orderBy('created_at', 'desc')->first();

            $loanApplicationData = [
                'business_id'     => $business?->id,
                'loan_product_id' => $loanProduct->id,
                'loan_amount'     => $validated['loan_amount'],
                'duration_months' => $validated['duration_months'],
                'purpose'         => $validated['purpose'],
            ];

            $isNewApplication = false;

            if ($loanApplication) {
                $loanApplication->update($loanApplicationData);
            } else {
                // Edge case: customer had no loan application yet — create one
                $isNewApplication = true;

                $loanApplication = LoanApplication::createWithAmount(array_merge($loanApplicationData, [
                    'application_no'    => LoanApplication::generateApplicationNo(),
                    'customer_id'       => $customer->id,
                    'status'            => 'submitted',
                    'application_date'  => now()->toDateString(),
                ]), (float) $loanProduct->interest_rate);
            }

            // 5b) Loan Amount — recalculate the breakdown whenever an EXISTING
            //     application is updated, since amount/duration/product/rate
            //     may have changed. createWithAmount() above already builds
            //     the LoanAmount row for brand-new applications.
            if (!$isNewApplication) {
                $loanApplication->loanAmount()->delete();

                LoanAmount::createFor($loanApplication, (float) $loanProduct->interest_rate);
            }

            // 6) Co-signer — update existing record, or create if missing
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

            $coSigner = $loanApplication->coSigner;

            $coSignerData = [
                'first_name'             => $validated['cosigner_first_name'],
                'last_name'              => $validated['cosigner_last_name'],
                'middle_name'            => $validated['cosigner_middle_name'] ?? null,
                'date_of_birth'          => $validated['cosigner_date_of_birth'] ?? null,
                'occupation'             => $validated['cosigner_occupation'] ?? null,
                'email'                  => $validated['cosigner_email'] ?? null,
                'phone_primary'          => $validated['cosigner_phone_primary'],
                'phone_secondary'        => $validated['cosigner_phone_secondary'] ?? null,
                'address'                => $validated['cosigner_address'] ?? null,
                'city'                   => $validated['cosigner_city'] ?? null,
                'state'                  => $validated['cosigner_state'] ?? null,
                'country'                => $validated['cosigner_country'] ?? null,
                'bvn'                    => $validated['cosigner_bvn'] ?? null,
                'relationship'           => $validated['cosigner_relationship'] ?? null,
            ];

            // Only overwrite file paths when a new file was actually uploaded
            if ($evidencePath) {
                if ($coSigner && $coSigner->evidence_of_occupation) {
                    Storage::disk('public')->delete($coSigner->evidence_of_occupation);
                }
                $coSignerData['evidence_of_occupation'] = $evidencePath;
            }

            if ($photoIdPath) {
                if ($coSigner && $coSigner->photo_id) {
                    Storage::disk('public')->delete($coSigner->photo_id);
                }
                $coSignerData['photo_id'] = $photoIdPath;
            }

            if ($coSigner) {
                $coSigner->update($coSignerData);
            } else {
                $coSigner = CoSigner::create(array_merge(['application_id' => $loanApplication->id], $coSignerData));
            }

            // 7) Keep customer_by_staff link's cached customer_code in sync
            $link->update([
                'customer_code' => $customer->customer_code,
            ]);

            DB::commit();

            // ✅ ============================================================
            // ✅ 8) SEND EMAIL AFTER SUCCESSFUL UPDATE (with loan calculation)
            // ✅ ============================================================
            try {
                if (!empty($customer->email)) {
                    Mail::to($customer->email)->send(
                        new CustomerByStaffMail(
                            $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
                            $loanApplication->fresh(['loanProduct', 'business', 'coSigner', 'loanAmount'])
                        )
                    );

                    Log::info('Loan application update email sent to: ' . $customer->email);
                }
            } catch (\Exception $mailException) {
                Log::error('Failed to send loan application update email: ' . $mailException->getMessage());
                // Email failure should not break the update process
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer "' . $customer->full_name . '" (' . $customer->customer_code . ') updated successfully. Updated loan application details have been sent to the customer\'s email.',
                'data'    => [
                    'customer'         => $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
                    'bank_account'     => $bankAccount,
                    'co_signer'        => $coSigner,
                    'loan_application' => $loanApplication->fresh(['loanProduct', 'business', 'coSigner', 'loanAmount']),
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update staff customer: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'errors'  => ['error' => ['Failed to update customer: ' . $e->getMessage()]],
            ], 500);
        }
    }

    /**
     * DELETE /api/staff/customers/{customerId}/documents/{documentId}
     * Optional standalone endpoint to remove a single existing document
     * (kept for convenience — the update() method also accepts
     * removed_document_ids[] in bulk).
     */
    public function removeDocument($customerId, $documentId): JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        $link = CustomerByStaff::where('customer_id', $customerId)
            ->where('staff_id', $staff->id)
            ->first();

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found or you do not have access to this record.',
            ], 404);
        }

        $document = CustomerDocument::where('customer_id', $customerId)
            ->where('id', $documentId)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found.',
            ], 404);
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document removed successfully.',
        ]);
    }

    /**
     * POST /api/staff/customers
     * Flow: Customer -> Business (SME) -> Bank Account -> Documents ->
     *       Loan Application (+ Loan Amount via createWithAmount) -> Co-signer ->
     *       Customer-by-Staff link -> Email PDF
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

            // 5) Loan Application (createWithAmount se loan_amounts bhi save hoga)
            $loanApplication = LoanApplication::createWithAmount([
                'application_no'  => LoanApplication::generateApplicationNo(),
                'customer_id'     => $customer->id,
                'business_id'     => $business?->id,
                'loan_product_id' => $loanProduct->id,
                'loan_amount'     => $validated['loan_amount'],
                'duration_months' => $validated['duration_months'],
                'purpose'         => $validated['purpose'],
                'status'          => 'submitted',
                'application_date' => now()->toDateString(),
            ], (float) $loanProduct->interest_rate);

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
            //    (loanAmount eager-loaded so the calculation shows up in the PDF/email)
            try {
                if (!empty($customer->email)) {
                    Mail::to($customer->email)->send(
                        new CustomerByStaffMail(
                            $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
                            $loanApplication->fresh(['loanProduct', 'business', 'coSigner', 'loanAmount'])
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
                    'loan_application' => $loanApplication->fresh(['loanProduct', 'business', 'coSigner', 'loanAmount']),
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
// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Mail\CustomerByStaffMail;
// use App\Models\Bank;
// use App\Models\Business;
// use App\Models\CoSigner;
// use App\Models\Customer;
// use App\Models\CustomerBankAccount;
// use App\Models\CustomerByStaff;
// use App\Models\CustomerDocument;
// use App\Models\LoanApplication;
// use App\Models\LoanProduct;
// use Illuminate\Http\JsonResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Validation\Rule;


// class CustomerByStaffController extends Controller
// {  public function loanProducts(Request $request): JsonResponse
//     {
//         $query = LoanProduct::where('status', 'active');

//         if ($request->filled('type')) {
//             $query->where('loan_type', $request->input('type'));
//         }

//         return response()->json([
//             'success' => true,
//             'data'    => $query->get(),
//         ]);
//     }

//     /**
//      * GET /api/staff/banks
//      */
//     public function banks(): JsonResponse
//     {
//         return response()->json([
//             'success' => true,
//             'data'    => Bank::where('status', 'active')->get(),
//         ]);
//     }

//     /**
//      * POST /api/staff/check-email
//      */
//     public function checkEmail(Request $request): JsonResponse
//     {
//         $request->validate([
//             'email' => 'required|email|max:255'
//         ]);

//         $query = Customer::where('email', $request->input('email'));

//         // ✅ Agar edit mode se ignore_id bheja gaya ho (khud ka email check na ho)
//         if ($request->filled('ignore_id')) {
//             $query->where('id', '!=', $request->input('ignore_id'));
//         }

//         $exists = $query->exists();

//         return response()->json([
//             'exists' => $exists
//         ]);
//     }

//     /**
//      * GET /api/staff/customers
//      */
//     public function index(Request $request): JsonResponse
//     {
//         $perPage = $request->get('per_page', 15);

//         $customers = CustomerByStaff::with([
//             'customer',
//             'customer.business',
//             'customer.documents',
//             'customer.bankAccounts',
//             'customer.bankAccounts.bank',
//             'customer.loanApplications',
//             'customer.loanApplications.coSigner',
//         ])
//         ->where('staff_id', auth()->id())
//         ->orderBy('created_at', 'desc')
//         ->paginate($perPage);

//         return response()->json([
//             'success' => true,
//             'data' => $customers
//         ]);
//     }

//     /**
//      * GET /api/staff/customers/{id}
//      * Edit form ke liye ek customer ka pura data (business, bank, documents,
//      * loan application + co-signer) load karta hai. Sirf wohi staff dekh
//      * sakta hai jisne is customer ko register kiya tha.
//      */
//     public function show($id): JsonResponse
//     {
//         $staff = Auth::guard('staff')->user();

//         $link = CustomerByStaff::where('customer_id', $id)
//             ->where('staff_id', $staff->id)
//             ->first();

//         if (!$link) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Customer not found or you do not have access to edit this record.',
//             ], 404);
//         }

//         $customer = Customer::with([
//             'business',
//             'documents',
//             'bankAccounts.bank',
//             'loanApplications' => function ($q) {
//                 $q->orderBy('created_at', 'desc');
//             },
//             'loanApplications.coSigner',
//             'loanApplications.loanProduct',
//         ])->find($id);

//         if (!$customer) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Customer not found.',
//             ], 404);
//         }

//         return response()->json([
//             'success' => true,
//             'data'    => $customer,
//         ]);
//     }

//     /**
//      * POST /api/staff/customers/{id}/update
//      * (POST use kiya hai kyunke multipart/form-data + PUT Laravel mein
//      *  files ke sath reliably parse nahi hota — Angular se bhi POST hi bheja jaye)
//      *
//      * Flow: Customer update -> Business (SME) update/remove -> Bank Account update ->
//      *       Documents (remove + add new) -> Loan Application update -> Co-signer update
//      */
//   public function update(Request $request, $id): JsonResponse
// {
//     /** @var \App\Models\Staff $staff */
//     $staff = Auth::guard('staff')->user();

//     $link = CustomerByStaff::where('customer_id', $id)
//         ->where('staff_id', $staff->id)
//         ->first();

//     if (!$link) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Customer not found or you do not have access to edit this record.',
//         ], 404);
//     }

//     $customer = Customer::find($id);

//     if (!$customer) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Customer not found.',
//         ], 404);
//     }

//     $isSme = $request->input('customer_type') === 'sme';

//     $rules = [
//         'customer_type'  => ['required', Rule::in(Customer::CUSTOMER_TYPES)],
//         'first_name'     => 'required|string|max:100',
//         'last_name'      => 'required|string|max:100',
//         'middle_name'    => 'nullable|string|max:100',
//         'date_of_birth'  => 'nullable|date|before:today',
//         'gender'         => ['nullable', Rule::in(Customer::GENDERS)],
//         'national_id'    => ['nullable', 'string', 'max:100', Rule::unique('customers', 'national_id')->ignore($customer->id)],
//         'email'          => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
//         'phone_primary'  => 'required|string|max:20',
//         'phone_secondary'=> 'nullable|string|max:20',
//         'occupation'     => 'nullable|string|max:150',
//         'monthly_income' => 'nullable|numeric|min:0',
//         'country'        => 'nullable|string|max:100',
//         'state'          => 'nullable|string|max:100',
//         'city'           => 'nullable|string|max:100',
//         'local_government_area' => 'nullable|string|max:100',
//         'address'        => 'nullable|string',
//         'status'         => ['nullable', Rule::in(Customer::STATUSES)],

//         // New documents only (existing ones are handled via removed_document_ids)
//         'documents'                        => 'nullable|array',
//         'documents.*.document_type'        => ['nullable', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
//         'documents.*.file'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
//         'documents.*.verification_status'  => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],

//         // Existing documents that the staff removed on the form
//         'removed_document_ids'   => 'nullable|array',
//         'removed_document_ids.*' => 'integer|exists:customer_documents,id',

//         // Bank Details
//         'bank_id'         => ['required', 'exists:banks,id'],
//         'account_name'    => ['required', 'string', 'max:150'],
//         'account_number'  => ['required', 'string', 'max:50'],

//         // Co-signer
//         'cosigner_first_name'             => ['required', 'string', 'max:100'],
//         'cosigner_last_name'              => ['required', 'string', 'max:100'],
//         'cosigner_middle_name'            => ['nullable', 'string', 'max:100'],
//         'cosigner_date_of_birth'          => ['nullable', 'date', 'before:today'],
//         'cosigner_occupation'             => ['nullable', 'string', 'max:150'],
//         'cosigner_evidence_of_occupation' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
//         'cosigner_email'                  => ['nullable', 'email', 'max:255'],
//         'cosigner_phone_primary'          => ['required', 'string', 'max:20'],
//         'cosigner_phone_secondary'        => ['nullable', 'string', 'max:20'],
//         'cosigner_address'                => ['nullable', 'string'],
//         'cosigner_city'                   => ['nullable', 'string', 'max:100'],
//         'cosigner_state'                  => ['nullable', 'string', 'max:100'],
//         'cosigner_country'                => ['nullable', 'string', 'max:100'],
//         'cosigner_bvn'                    => ['nullable', 'string', 'max:20'],
//         'cosigner_photo_id'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
//         'cosigner_relationship'           => ['nullable', 'string', 'max:100'],

//         // Loan Application
//         'loan_product_id' => ['required', 'exists:loan_products,id'],
//         'loan_amount'     => ['required', 'numeric', 'min:0'],
//         'duration_months' => ['required', 'integer', 'min:1'],
//         'purpose'         => ['required', 'string', 'max:1000'],
//     ];

//     if ($isSme) {
//         $rules = array_merge($rules, [
//             'business_name'                  => ['required', 'string', 'max:200'],
//             'registration_number'            => ['nullable', 'string', 'max:100'],
//             'tax_number'                      => ['nullable', 'string', 'max:100'],
//             'business_type'                   => ['nullable', 'string', 'max:100'],
//             'business_monthly_revenue'        => ['nullable', 'numeric', 'min:0'],
//             'business_monthly_expense'        => ['nullable', 'numeric', 'min:0'],
//             'business_address'                => ['nullable', 'string'],
//             'business_city'                    => ['nullable', 'string', 'max:100'],
//             'business_state'                   => ['nullable', 'string', 'max:100'],
//             'business_local_government_area'  => ['nullable', 'string', 'max:100'],
//         ]);
//     }

//     $validated = $request->validate($rules, [
//         'business_name.required'          => 'Business name is required for SME customers.',
//         'loan_product_id.exists'          => 'Selected loan product is invalid.',
//         'bank_id.exists'                  => 'Selected bank is invalid.',
//         'cosigner_first_name.required'    => 'Co-signer first name is required.',
//         'cosigner_last_name.required'     => 'Co-signer last name is required.',
//         'cosigner_phone_primary.required' => 'Co-signer phone number is required.',
//     ]);

//     $loanProduct = LoanProduct::where('id', $validated['loan_product_id'])
//         ->where('status', 'active')
//         ->first();

//     if (!$loanProduct) {
//         return response()->json([
//             'success' => false,
//             'errors'  => ['loan_product_id' => ['Selected loan product is currently unavailable.']],
//         ], 422);
//     }

//     if ($loanProduct->loan_type !== $validated['customer_type']) {
//         return response()->json([
//             'success' => false,
//             'errors'  => ['loan_product_id' => ['Selected loan product does not match the customer type.']],
//         ], 422);
//     }

//     if ($validated['loan_amount'] < $loanProduct->minimum_amount || $validated['loan_amount'] > $loanProduct->maximum_amount) {
//         return response()->json([
//             'success' => false,
//             'errors'  => [
//                 'loan_amount' => [
//                     'Loan amount must be between ₦' . number_format($loanProduct->minimum_amount, 0)
//                         . ' and ₦' . number_format($loanProduct->maximum_amount, 0) . ' for this product.',
//                 ],
//             ],
//         ], 422);
//     }

//     try {
//         DB::beginTransaction();

//         // 1) Customer — update
//         $customerData = collect($validated)->only([
//             'customer_type', 'first_name', 'last_name', 'middle_name', 'date_of_birth',
//             'gender', 'national_id', 'email', 'phone_primary', 'phone_secondary',
//             'occupation', 'monthly_income', 'country', 'state', 'city',
//             'local_government_area', 'address', 'status',
//         ])->toArray();

//         $customerData['status'] = $customerData['status'] ?? $customer->status ?? 'active';

//         $customer->update($customerData);

//         // 2) Business (SME only) — update if exists, create if newly switched to SME,
//         //    remove if switched away from SME
//         $business = $customer->business;

//         if ($isSme) {
//             $businessData = [
//                 'business_name'          => $validated['business_name'],
//                 'registration_number'    => $validated['registration_number'] ?? null,
//                 'tax_number'             => $validated['tax_number'] ?? null,
//                 'business_type'          => $validated['business_type'] ?? null,
//                 'monthly_revenue'        => $validated['business_monthly_revenue'] ?? null,
//                 'monthly_expense'        => $validated['business_monthly_expense'] ?? null,
//                 'address'                => $validated['business_address'] ?? null,
//                 'city'                   => $validated['business_city'] ?? null,
//                 'state'                  => $validated['business_state'] ?? null,
//                 'local_government_area'  => $validated['business_local_government_area'] ?? null,
//                 'status'                 => 'active',
//             ];

//             if ($business) {
//                 $business->update($businessData);
//             } else {
//                 $business = Business::create(array_merge(['customer_id' => $customer->id], $businessData));
//             }
//         } elseif ($business) {
//             // Customer switched from SME to personal — drop the business record
//             $business->delete();
//             $business = null;
//         }

//         // 3) Bank Account — update the existing one
//         $bankAccount = $customer->bankAccounts()->first();

//         $bankData = [
//             'bank_id'        => $validated['bank_id'],
//             'account_name'   => $validated['account_name'],
//             'account_number' => $validated['account_number'],
//         ];

//         if ($bankAccount) {
//             $bankAccount->update($bankData);
//         } else {
//             $bankAccount = CustomerBankAccount::create(array_merge(['customer_id' => $customer->id], $bankData));
//         }

//         // 4) Documents — remove any the staff deleted on the form
//         $removedIds = $validated['removed_document_ids'] ?? [];
//         if (!empty($removedIds)) {
//             $docsToRemove = $customer->documents()->whereIn('id', $removedIds)->get();
//             foreach ($docsToRemove as $doc) {
//                 if ($doc->file_path) {
//                     Storage::disk('public')->delete($doc->file_path);
//                 }
//                 $doc->delete();
//             }
//         }

//         // Documents — append any newly uploaded ones
//         foreach ($request->file('documents', []) as $index => $docFiles) {
//             $file = $docFiles['file'] ?? null;

//             if (!$file) {
//                 continue;
//             }

//             $path = $file->store('customer-documents', 'public');

//             $customer->documents()->create([
//                 'document_type'        => $validated['documents'][$index]['document_type'] ?? 'other',
//                 'file_path'            => $path,
//                 'verification_status'  => $validated['documents'][$index]['verification_status'] ?? 'pending',
//             ]);
//         }

//         // 5) Loan Application — update the most recent application
//         $loanApplication = $customer->loanApplications()->orderBy('created_at', 'desc')->first();

//         $loanApplicationData = [
//             'business_id'     => $business?->id,
//             'loan_product_id' => $loanProduct->id,
//             'loan_amount'     => $validated['loan_amount'],
//             'duration_months' => $validated['duration_months'],
//             'purpose'         => $validated['purpose'],
//         ];

//         if ($loanApplication) {
//             $loanApplication->update($loanApplicationData);
//         } else {
//             // Edge case: customer had no loan application yet — create one
//             $loanApplication = LoanApplication::createWithAmount(array_merge($loanApplicationData, [
//                 'application_no'    => LoanApplication::generateApplicationNo(),
//                 'customer_id'       => $customer->id,
//                 'status'            => 'submitted',
//                 'application_date'  => now()->toDateString(),
//             ]), (float) $loanProduct->interest_rate);
//         }

//         // 6) Co-signer — update existing record, or create if missing
//         $evidencePath = null;
//         if ($request->hasFile('cosigner_evidence_of_occupation')) {
//             $evidencePath = $request->file('cosigner_evidence_of_occupation')
//                 ->store('cosigner-documents', 'public');
//         }

//         $photoIdPath = null;
//         if ($request->hasFile('cosigner_photo_id')) {
//             $photoIdPath = $request->file('cosigner_photo_id')
//                 ->store('cosigner-documents', 'public');
//         }

//         $coSigner = $loanApplication->coSigner;

//         $coSignerData = [
//             'first_name'             => $validated['cosigner_first_name'],
//             'last_name'              => $validated['cosigner_last_name'],
//             'middle_name'            => $validated['cosigner_middle_name'] ?? null,
//             'date_of_birth'          => $validated['cosigner_date_of_birth'] ?? null,
//             'occupation'             => $validated['cosigner_occupation'] ?? null,
//             'email'                  => $validated['cosigner_email'] ?? null,
//             'phone_primary'          => $validated['cosigner_phone_primary'],
//             'phone_secondary'        => $validated['cosigner_phone_secondary'] ?? null,
//             'address'                => $validated['cosigner_address'] ?? null,
//             'city'                   => $validated['cosigner_city'] ?? null,
//             'state'                  => $validated['cosigner_state'] ?? null,
//             'country'                => $validated['cosigner_country'] ?? null,
//             'bvn'                    => $validated['cosigner_bvn'] ?? null,
//             'relationship'           => $validated['cosigner_relationship'] ?? null,
//         ];

//         // Only overwrite file paths when a new file was actually uploaded
//         if ($evidencePath) {
//             if ($coSigner && $coSigner->evidence_of_occupation) {
//                 Storage::disk('public')->delete($coSigner->evidence_of_occupation);
//             }
//             $coSignerData['evidence_of_occupation'] = $evidencePath;
//         }

//         if ($photoIdPath) {
//             if ($coSigner && $coSigner->photo_id) {
//                 Storage::disk('public')->delete($coSigner->photo_id);
//             }
//             $coSignerData['photo_id'] = $photoIdPath;
//         }

//         if ($coSigner) {
//             $coSigner->update($coSignerData);
//         } else {
//             $coSigner = CoSigner::create(array_merge(['application_id' => $loanApplication->id], $coSignerData));
//         }

//         // 7) Keep customer_by_staff link's cached customer_code in sync
//         $link->update([
//             'customer_code' => $customer->customer_code,
//         ]);

//         DB::commit();

//         // ✅ ============================================================
//         // ✅ 8) SEND EMAIL AFTER SUCCESSFUL UPDATE (FIXED)
//         // ✅ ============================================================
//         try {
//             if (!empty($customer->email)) {
//                 Mail::to($customer->email)->send(
//                     new CustomerByStaffMail(
//                         $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
//                         $loanApplication->fresh(['loanProduct', 'business', 'coSigner'])
//                     )
//                 );
                
//                 Log::info('Loan application update email sent to: ' . $customer->email);
//             }
//         } catch (\Exception $mailException) {
//             Log::error('Failed to send loan application update email: ' . $mailException->getMessage());
//             // Email failure should not break the update process
//         }

//         return response()->json([
//             'success' => true,
//             'message' => 'Customer "' . $customer->full_name . '" (' . $customer->customer_code . ') updated successfully. Updated loan application details have been sent to the customer\'s email.',
//             'data'    => [
//                 'customer'         => $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
//                 'bank_account'     => $bankAccount,
//                 'co_signer'        => $coSigner,
//                 'loan_application' => $loanApplication->fresh(['loanProduct', 'business', 'coSigner']),
//             ],
//         ], 200);

//     } catch (\Exception $e) {
//         DB::rollBack();

//         Log::error('Failed to update staff customer: ' . $e->getMessage());

//         return response()->json([
//             'success' => false,
//             'errors'  => ['error' => ['Failed to update customer: ' . $e->getMessage()]],
//         ], 500);
//     }
// }

//     /**
//      * DELETE /api/staff/customers/{customerId}/documents/{documentId}
//      * Optional standalone endpoint to remove a single existing document
//      * (kept for convenience — the update() method also accepts
//      * removed_document_ids[] in bulk).
//      */
//     public function removeDocument($customerId, $documentId): JsonResponse
//     {
//         $staff = Auth::guard('staff')->user();

//         $link = CustomerByStaff::where('customer_id', $customerId)
//             ->where('staff_id', $staff->id)
//             ->first();

//         if (!$link) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Customer not found or you do not have access to this record.',
//             ], 404);
//         }

//         $document = CustomerDocument::where('customer_id', $customerId)
//             ->where('id', $documentId)
//             ->first();

//         if (!$document) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Document not found.',
//             ], 404);
//         }

//         if ($document->file_path) {
//             Storage::disk('public')->delete($document->file_path);
//         }

//         $document->delete();

//         return response()->json([
//             'success' => true,
//             'message' => 'Document removed successfully.',
//         ]);
//     }

//     /**
//      * POST /api/staff/customers
//      * Flow: Customer -> Business (SME) -> Bank Account -> Documents ->
//      *       Loan Application -> Co-signer -> Customer-by-Staff link -> Email PDF
//      */
//     public function store(Request $request): JsonResponse
//     {
//         /** @var \App\Models\Staff $staff */
//         $staff = Auth::guard('staff')->user();

//         $isSme = $request->input('customer_type') === 'sme';

//         $rules = [
//             'customer_type'  => ['required', Rule::in(Customer::CUSTOMER_TYPES)],
//             'first_name'     => 'required|string|max:100',
//             'last_name'      => 'required|string|max:100',
//             'middle_name'    => 'nullable|string|max:100',
//             'date_of_birth'  => 'nullable|date|before:today',
//             'gender'         => ['nullable', Rule::in(Customer::GENDERS)],
//             'national_id'    => 'nullable|string|max:100|unique:customers,national_id',
//             'email'          => 'nullable|email|max:255|unique:customers,email',
//             'phone_primary'  => 'required|string|max:20',
//             'phone_secondary'=> 'nullable|string|max:20',
//             'occupation'     => 'nullable|string|max:150',
//             'monthly_income' => 'nullable|numeric|min:0',
//             'country'        => 'nullable|string|max:100',
//             'state'          => 'nullable|string|max:100',
//             'city'           => 'nullable|string|max:100',
//             'local_government_area' => 'nullable|string|max:100',
//             'address'        => 'nullable|string',
//             'status'         => ['nullable', Rule::in(Customer::STATUSES)],

//             // Documents
//             'documents'                        => 'nullable|array',
//             'documents.*.document_type'        => ['nullable', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
//             'documents.*.file'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
//             'documents.*.verification_status'  => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],

//             // Bank Details
//             'bank_id'         => ['required', 'exists:banks,id'],
//             'account_name'    => ['required', 'string', 'max:150'],
//             'account_number'  => ['required', 'string', 'max:50'],

//             // Co-signer
//             'cosigner_first_name'             => ['required', 'string', 'max:100'],
//             'cosigner_last_name'              => ['required', 'string', 'max:100'],
//             'cosigner_middle_name'            => ['nullable', 'string', 'max:100'],
//             'cosigner_date_of_birth'          => ['nullable', 'date', 'before:today'],
//             'cosigner_occupation'             => ['nullable', 'string', 'max:150'],
//             'cosigner_evidence_of_occupation' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
//             'cosigner_email'                  => ['nullable', 'email', 'max:255'],
//             'cosigner_phone_primary'          => ['required', 'string', 'max:20'],
//             'cosigner_phone_secondary'        => ['nullable', 'string', 'max:20'],
//             'cosigner_address'                => ['nullable', 'string'],
//             'cosigner_city'                   => ['nullable', 'string', 'max:100'],
//             'cosigner_state'                  => ['nullable', 'string', 'max:100'],
//             'cosigner_country'                => ['nullable', 'string', 'max:100'],
//             'cosigner_bvn'                    => ['nullable', 'string', 'max:20'],
//             'cosigner_photo_id'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
//             'cosigner_relationship'           => ['nullable', 'string', 'max:100'],

//             // Loan Application
//             'loan_product_id' => ['required', 'exists:loan_products,id'],
//             'loan_amount'     => ['required', 'numeric', 'min:0'],
//             'duration_months' => ['required', 'integer', 'min:1'],
//             'purpose'         => ['required', 'string', 'max:1000'],
//         ];

//         if ($isSme) {
//             $rules = array_merge($rules, [
//                 'business_name'                  => ['required', 'string', 'max:200'],
//                 'registration_number'            => ['nullable', 'string', 'max:100'],
//                 'tax_number'                      => ['nullable', 'string', 'max:100'],
//                 'business_type'                   => ['nullable', 'string', 'max:100'],
//                 'business_monthly_revenue'        => ['nullable', 'numeric', 'min:0'],
//                 'business_monthly_expense'        => ['nullable', 'numeric', 'min:0'],
//                 'business_address'                => ['nullable', 'string'],
//                 'business_city'                    => ['nullable', 'string', 'max:100'],
//                 'business_state'                   => ['nullable', 'string', 'max:100'],
//                 'business_local_government_area'  => ['nullable', 'string', 'max:100'],
//             ]);
//         }

//         $validated = $request->validate($rules, [
//             'business_name.required'          => 'Business name is required for SME customers.',
//             'loan_product_id.exists'          => 'Selected loan product is invalid.',
//             'bank_id.exists'                  => 'Selected bank is invalid.',
//             'cosigner_first_name.required'    => 'Co-signer first name is required.',
//             'cosigner_last_name.required'     => 'Co-signer last name is required.',
//             'cosigner_phone_primary.required' => 'Co-signer phone number is required.',
//         ]);

//         $loanProduct = LoanProduct::where('id', $validated['loan_product_id'])
//             ->where('status', 'active')
//             ->first();

//         if (!$loanProduct) {
//             return response()->json([
//                 'success' => false,
//                 'errors'  => ['loan_product_id' => ['Selected loan product is currently unavailable.']],
//             ], 422);
//         }

//         if ($loanProduct->loan_type !== $validated['customer_type']) {
//             return response()->json([
//                 'success' => false,
//                 'errors'  => ['loan_product_id' => ['Selected loan product does not match the customer type.']],
//             ], 422);
//         }

//         if ($validated['loan_amount'] < $loanProduct->minimum_amount || $validated['loan_amount'] > $loanProduct->maximum_amount) {
//             return response()->json([
//                 'success' => false,
//                 'errors'  => [
//                     'loan_amount' => [
//                         'Loan amount must be between ₦' . number_format($loanProduct->minimum_amount, 0)
//                             . ' and ₦' . number_format($loanProduct->maximum_amount, 0) . ' for this product.',
//                     ],
//                 ],
//             ], 422);
//         }

//         try {
//             DB::beginTransaction();

//             // 1) Customer
//             $customerData = collect($validated)->only([
//                 'customer_type', 'first_name', 'last_name', 'middle_name', 'date_of_birth',
//                 'gender', 'national_id', 'email', 'phone_primary', 'phone_secondary',
//                 'occupation', 'monthly_income', 'country', 'state', 'city',
//                 'local_government_area', 'address', 'status',
//             ])->toArray();

//             $customerData['customer_code'] = Customer::generateCustomerCode();
//             $customerData['status'] = $customerData['status'] ?? 'active';

//             $customer = Customer::create($customerData);

//             // 2) Business (SME only)
//             $business = null;

//             if ($isSme) {
//                 $business = Business::create([
//                     'customer_id'            => $customer->id,
//                     'business_name'          => $validated['business_name'],
//                     'registration_number'    => $validated['registration_number'] ?? null,
//                     'tax_number'             => $validated['tax_number'] ?? null,
//                     'business_type'          => $validated['business_type'] ?? null,
//                     'monthly_revenue'        => $validated['business_monthly_revenue'] ?? null,
//                     'monthly_expense'        => $validated['business_monthly_expense'] ?? null,
//                     'address'                => $validated['business_address'] ?? null,
//                     'city'                   => $validated['business_city'] ?? null,
//                     'state'                  => $validated['business_state'] ?? null,
//                     'local_government_area'  => $validated['business_local_government_area'] ?? null,
//                     'status'                 => 'active',
//                 ]);
//             }

//             // 3) Bank Account
//             $bankAccount = CustomerBankAccount::create([
//                 'customer_id'    => $customer->id,
//                 'bank_id'        => $validated['bank_id'],
//                 'account_name'   => $validated['account_name'],
//                 'account_number' => $validated['account_number'],
//             ]);

//             // 4) Documents
//             foreach ($request->file('documents', []) as $index => $docFiles) {
//                 $file = $docFiles['file'] ?? null;

//                 if (!$file) {
//                     continue;
//                 }

//                 $path = $file->store('customer-documents', 'public');

//                 $customer->documents()->create([
//                     'document_type'        => $validated['documents'][$index]['document_type'] ?? 'other',
//                     'file_path'            => $path,
//                     'verification_status'  => $validated['documents'][$index]['verification_status'] ?? 'pending',
//                 ]);
//             }

//            // 5) Loan Application (createWithAmount se loan_amounts bhi save hoga)
// $loanApplication = LoanApplication::createWithAmount([
//     'application_no'  => LoanApplication::generateApplicationNo(),
//     'customer_id'     => $customer->id,
//     'business_id'     => $business?->id,
//     'loan_product_id' => $loanProduct->id,
//     'loan_amount'     => $validated['loan_amount'],
//     'duration_months' => $validated['duration_months'],
//     'purpose'         => $validated['purpose'],
//     'status'          => 'submitted',
//     'application_date' => now()->toDateString(),
// ], (float) $loanProduct->interest_rate);

//             // 6) Co-signer
//             $evidencePath = null;
//             if ($request->hasFile('cosigner_evidence_of_occupation')) {
//                 $evidencePath = $request->file('cosigner_evidence_of_occupation')
//                     ->store('cosigner-documents', 'public');
//             }

//             $photoIdPath = null;
//             if ($request->hasFile('cosigner_photo_id')) {
//                 $photoIdPath = $request->file('cosigner_photo_id')
//                     ->store('cosigner-documents', 'public');
//             }

//             $coSigner = CoSigner::create([
//                 'application_id'         => $loanApplication->id,
//                 'first_name'             => $validated['cosigner_first_name'],
//                 'last_name'              => $validated['cosigner_last_name'],
//                 'middle_name'            => $validated['cosigner_middle_name'] ?? null,
//                 'date_of_birth'          => $validated['cosigner_date_of_birth'] ?? null,
//                 'occupation'             => $validated['cosigner_occupation'] ?? null,
//                 'evidence_of_occupation' => $evidencePath,
//                 'email'                  => $validated['cosigner_email'] ?? null,
//                 'phone_primary'          => $validated['cosigner_phone_primary'],
//                 'phone_secondary'        => $validated['cosigner_phone_secondary'] ?? null,
//                 'address'                => $validated['cosigner_address'] ?? null,
//                 'city'                   => $validated['cosigner_city'] ?? null,
//                 'state'                  => $validated['cosigner_state'] ?? null,
//                 'country'                => $validated['cosigner_country'] ?? null,
//                 'bvn'                    => $validated['cosigner_bvn'] ?? null,
//                 'photo_id'               => $photoIdPath,
//                 'relationship'           => $validated['cosigner_relationship'] ?? null,
//             ]);

//             // 7) Customer-by-Staff link
//             CustomerByStaff::create([
//                 'customer_id'   => $customer->id,
//                 'customer_code' => $customer->customer_code,
//                 'staff_id'      => $staff->id,
//                 'staff_code'    => $staff->staff_code,
//             ]);

//             DB::commit();

//             // 8) Send the loan application confirmation email with PDF attachment
//             try {
//                 if (!empty($customer->email)) {
//                     Mail::to($customer->email)->send(
//                         new CustomerByStaffMail(
//                             $customer->fresh(['documents', 'business', 'bankAccounts.bank']),
//                             $loanApplication->fresh(['loanProduct', 'business', 'coSigner'])
//                         )
//                     );
//                 }
//             } catch (\Exception $mailException) {
//                 Log::error('Failed to send loan application email: ' . $mailException->getMessage());
//             }

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Customer "' . $customer->full_name . '" (' . $customer->customer_code . ') registered successfully. Loan application ' . $loanApplication->application_no . ' has been submitted.',
//                 'data'    => [
//                     'customer'         => $customer->fresh(['documents', 'business']),
//                     'bank_account'     => $bankAccount,
//                     'co_signer'        => $coSigner,
//                     'loan_application' => $loanApplication,
//                 ],
//             ], 201);

//         } catch (\Exception $e) {
//             DB::rollBack();

//             return response()->json([
//                 'success' => false,
//                 'errors'  => ['error' => ['Failed to create customer: ' . $e->getMessage()]],
//             ], 500);
//         }
//     }
// }