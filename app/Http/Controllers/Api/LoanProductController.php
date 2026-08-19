<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\LoanProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
 
class LoanProductController extends Controller
{
    // GET /api/admin/loan-products
    public function index(Request $request): JsonResponse
    {
        $query = LoanProduct::query();
 
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->loan_type);
        }
 
        $products = $query->latest()->get();
 
        return response()->json([
            'success' => true,
            'data'    => $products,
            'total'   => $products->count(),
        ]);
    }
 
    // POST /api/admin/loan-products
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:150|unique:loan_products,name',
            'loan_type'       => 'required|in:personal,sme',
            'minimum_amount'  => 'required|numeric|min:0',
            'maximum_amount'  => 'required|numeric|gt:minimum_amount',
            'interest_rate'   => 'required|numeric|min:0|max:100',
            'processing_fee'  => 'nullable|numeric|min:0',
            'late_fee'        => 'nullable|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:360',
            'minimum_duration_month'  => 'required|integer|min:1|lte:duration_months',
            'description'     => 'nullable|string',
            'status'          => 'required|in:active,inactive',
        ]);
 
        $product = LoanProduct::create($validated);
 
        return response()->json([
            'success' => true,
            'message' => 'Loan product created successfully.',
            'data'    => $product,
        ], 201);
    }
 
    // GET /api/admin/loan-products/{id}
    public function show(LoanProduct $loanProduct): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $loanProduct,
        ]);
    }
 
    // PUT /api/admin/loan-products/{id}
    public function update(Request $request, LoanProduct $loanProduct): JsonResponse
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:150', Rule::unique('loan_products', 'name')->ignore($loanProduct->id)],
            'loan_type'       => 'required|in:personal,sme',
            'minimum_amount'  => 'required|numeric|min:0',
            'maximum_amount'  => 'required|numeric|gt:minimum_amount',
            'interest_rate'   => 'required|numeric|min:0|max:100',
            'processing_fee'  => 'nullable|numeric|min:0',
            'late_fee'        => 'nullable|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:360',
            'minimum_duration_month'  => 'required|integer|min:1|lte:duration_months',
            'description'     => 'nullable|string',
            'status'          => 'required|in:active,inactive',
        ]);
 
        $loanProduct->update($validated);
 
        return response()->json([
            'success' => true,
            'message' => 'Loan product updated successfully.',
            'data'    => $loanProduct->fresh(),
        ]);
    }
 
    // DELETE /api/admin/loan-products/{id}
    public function destroy(LoanProduct $loanProduct): JsonResponse
    {
        // Prevent deletion if product has applications
        if ($loanProduct->loanApplications()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete — this product has existing loan applications.',
            ], 422);
        }
 
        $loanProduct->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Loan product deleted successfully.',
        ]);
    }
 
    // PATCH /api/admin/loan-products/{id}/toggle-status
    public function toggleStatus(LoanProduct $loanProduct): JsonResponse
    {
        $loanProduct->update([
            'status' => $loanProduct->status === 'active' ? 'inactive' : 'active',
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
            'data'    => $loanProduct->fresh(),
        ]);
    }
    // GET /api/loan-products/public-rates
public function publicRates(): JsonResponse
{
    $personal = LoanProduct::where('loan_type', 'personal')
        ->where('status', 'active')

        ->first(['interest_rate', 'minimum_amount', 'maximum_amount', 'duration_months']);

    $sme = LoanProduct::where('loan_type', 'sme')
        ->where('status', 'active')
        ->first(['interest_rate', 'minimum_amount', 'maximum_amount', 'duration_months']);


    return response()->json([
        'success'  => true,
        'personal' => $personal,
        'sme'      => $sme,
    ]);
}
}