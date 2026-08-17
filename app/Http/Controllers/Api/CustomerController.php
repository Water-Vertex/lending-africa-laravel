<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Customer::with(['documents', 'loanApplications']);

        //$query = Customer::with('documents');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer type
        if ($request->has('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_primary', 'LIKE', "%{$search}%")
                  ->orWhere('customer_code', 'LIKE', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $customers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers,
            'message' => 'Customers retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created customer with documents in one go.
     */
    public function store(Request $request): JsonResponse
    {
        // Validation rules
        $validated = $request->validate([
            // Customer fields
            'customer_type' => ['required', Rule::in(Customer::CUSTOMER_TYPES)],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => ['nullable', Rule::in(Customer::GENDERS)],
            'national_id' => 'nullable|string|max:100|unique:customers,national_id',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:150',
            'monthly_income' => 'nullable|numeric|min:0',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'local_government_area' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'status' => ['nullable', Rule::in(Customer::STATUSES)],

            // Document fields
            'documents' => 'nullable|array',
            'documents.*.document_type' => ['required_with:documents', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
            'documents.*.file_path' => 'required_with:documents|string',
            'documents.*.verification_status' => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],
        ]);

        try {
            DB::beginTransaction();

            // Generate customer code
            $customerData = $validated;
            unset($customerData['documents']);
            $customerData['customer_code'] = Customer::generateCustomerCode();

            // Set default status if not provided
            if (!isset($customerData['status'])) {
                $customerData['status'] = 'active';
            }

            // Create customer
            $customer = Customer::create($customerData);

            // Handle documents if provided
            if (isset($validated['documents']) && !empty($validated['documents'])) {
                foreach ($validated['documents'] as $documentData) {
                    $customer->documents()->create([
                        'document_type' => $documentData['document_type'],
                        'file_path' => $documentData['file_path'],
                        'verification_status' => $documentData['verification_status'] ?? 'pending',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $customer->load('documents'),
                'message' => 'Customer created successfully with documents.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified customer.
     */
    // public function show(Customer $customer): JsonResponse
    // {
    //     return response()->json([
    //         'success' => true,
    //         'data' => $customer->load('documents'),
    //         'message' => 'Customer retrieved successfully.'
    //     ]);
    // }
public function show(Customer $customer): JsonResponse
{
    $customer->load([
        'documents',
        'businesses',
        'bankAccounts.bank',
        'loanApplications.loanProduct',
        'loanApplications.business',
        'loanApplications.coSigners',
    ]);

    return response()->json([
        'success' => true,
        'data' => $customer,
        'message' => 'Customer retrieved successfully.'
    ]);
}
    /**
     * Update the specified customer and their documents.
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        // Validation rules
        $validated = $request->validate([
            // Customer fields
            'customer_type' => ['required', Rule::in(Customer::CUSTOMER_TYPES)],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => ['nullable', Rule::in(Customer::GENDERS)],
            'national_id' => 'nullable|string|max:100|unique:customers,national_id,' . $customer->id,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:150',
            'monthly_income' => 'nullable|numeric|min:0',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'local_government_area' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'status' => ['nullable', Rule::in(Customer::STATUSES)],

            // Document fields
            'documents' => 'nullable|array',
            'documents.*.document_type' => ['required_with:documents', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
            'documents.*.file_path' => 'required_with:documents|string',
            'documents.*.verification_status' => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],
            'replace_documents' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update customer
            $customerData = $validated;
            unset($customerData['documents']);
            unset($customerData['replace_documents']);
            $customer->update($customerData);

            // Handle documents if provided
            if (isset($validated['documents']) && !empty($validated['documents'])) {
                $replaceDocuments = $validated['replace_documents'] ?? false;

                if ($replaceDocuments) {
                    $customer->documents()->delete();
                }

                foreach ($validated['documents'] as $documentData) {
                    $customer->documents()->create([
                        'document_type' => $documentData['document_type'],
                        'file_path' => $documentData['file_path'],
                        'verification_status' => $documentData['verification_status'] ?? 'pending',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $customer->fresh()->load('documents'),
                'message' => 'Customer updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer and their documents.
     */
    // public function destroy(Customer $customer): JsonResponse
    // {
    //     try {
    //         DB::beginTransaction();
    //         $customer->documents()->delete();
    //         $customer->delete();
    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Customer and their documents deleted successfully.'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to delete customer.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
// public function destroy(Customer $customer): JsonResponse
// {
//     try {
//         DB::beginTransaction();

//         // Pehle sari related records delete karo order mein
        
//         // 1. Loan approvals (loan applications ke through)
//         $applicationIds = $customer->loanApplications()->pluck('id');
//         if ($applicationIds->isNotEmpty()) {
//             \App\Models\LoanApproval::whereIn('application_id', $applicationIds)->delete();
//         }

//         // 2. Co-signers
//         if ($applicationIds->isNotEmpty()) {
//             \App\Models\CoSigner::whereIn('loan_application_id', $applicationIds)->delete();
//         }

//         // 3. Loan applications
//         $customer->loanApplications()->delete();

//         // 4. Bank accounts
//         $customer->bankAccounts()->delete();

//         // 5. Documents
//         $customer->documents()->delete();

//         // 6. Business info
//         $customer->businesses()->delete();

//         // 7. Customer by staff record
//         \App\Models\CustomerByStaff::where('customer_id', $customer->id)->delete();

//         // 8. Finally customer delete
//         $customer->delete();

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Customer and all related records deleted successfully.'
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         \Log::error('Customer delete failed: ' . $e->getMessage());

//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to delete customer.',
//             'error'   => $e->getMessage()
//         ], 500);
//     }
// }
public function destroy(Customer $customer): JsonResponse
{
    try {
        // Check karo k koi loan application hai
        if ($customer->loanApplications()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This customer cannot be deleted because they have loan application(s) on record. Loan records must be retained for compliance purposes.',
            ], 422);
        }

        DB::beginTransaction();

        // Sirf woh customers delete hon jinka koi loan application nahi
        \App\Models\CustomerByStaff::where('customer_id', $customer->id)->delete();
        $customer->bankAccounts()->delete();
        $customer->documents()->delete();
        $customer->businesses()->delete();
        $customer->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete customer.',
        ], 500);
    }
}
    /**
     * Get customer statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('status', 'active')->count(),
            'inactive_customers' => Customer::where('status', 'inactive')->count(),
            'blacklisted_customers' => Customer::where('status', 'blacklisted')->count(),
            'personal_customers' => Customer::where('customer_type', 'personal')->count(),
            'sme_customers' => Customer::where('customer_type', 'sme')->count(),
            'total_documents' => CustomerDocument::count(),
            'pending_verification' => CustomerDocument::where('verification_status', 'pending')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistics retrieved successfully.'
        ]);
    }

    /**
     * Bulk update customer status.
     */
    public function bulkStatusUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'status' => ['required', Rule::in(Customer::STATUSES)],
        ]);

        try {
            Customer::whereIn('id', $validated['customer_ids'])
                ->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Customer statuses updated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer statuses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update customer status.
     */
    public function updateStatus(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Customer::STATUSES)],
        ]);

        try {
            $customer->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'data' => $customer,
                'message' => 'Customer status updated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add documents to an existing customer.
     */
    public function addDocuments(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'documents' => 'required|array',
            'documents.*.document_type' => ['required', Rule::in(CustomerDocument::DOCUMENT_TYPES)],
            'documents.*.file_path' => 'required|string',
            'documents.*.verification_status' => ['nullable', Rule::in(CustomerDocument::VERIFICATION_STATUSES)],
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['documents'] as $documentData) {
                $customer->documents()->create([
                    'document_type' => $documentData['document_type'],
                    'file_path' => $documentData['file_path'],
                    'verification_status' => $documentData['verification_status'] ?? 'pending',
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $customer->load('documents'),
                'message' => 'Documents added successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add documents.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
