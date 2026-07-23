<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoanApplicationController extends Controller
{
    /**
     * GET /api/admin/loan-applications
     * List loan applications (paginated) with search + filters.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $query = LoanApplication::with(['customer', 'business', 'loanProduct'])
            ->latest('id');

        // Search by application_no or customer name
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone_primary', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by loan type (via loan product relation)
        if ($request->filled('loan_type')) {
            $loanType = $request->input('loan_type');
            $query->whereHas('loanProduct', function ($q) use ($loanType) {
                $q->where('loan_type', $loanType);
            });
        }

        $paginated = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $paginated->items(),
            'meta'    => [
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
            ],
        ]);
    }

    /**
     * GET /api/admin/loan-applications/stats
     * Quick counts for the dashboard cards.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total'        => LoanApplication::count(),
                'submitted'    => LoanApplication::where('status', 'submitted')->count(),
                'under_review' => LoanApplication::where('status', 'under_review')->count(),
                'approved'     => LoanApplication::where('status', 'approved')->count(),
                'rejected'     => LoanApplication::where('status', 'rejected')->count(),
            ],
        ]);
    }

    /**
     * GET /api/admin/loan-applications/{id}
     */
    public function show($id): JsonResponse
    {
        $application = LoanApplication::with(['customer', 'business', 'loanProduct'])
            ->find($id);

        if (! $application) {
            return response()->json([
                'success' => false,
                'message' => 'Loan application not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $application,
        ]);
    }

    /**
     * PATCH /api/admin/loan-applications/{id}/status
     * Only the status is editable from the admin panel.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $application = LoanApplication::find($id);

        if (! $application) {
            return response()->json([
                'success' => false,
                'message' => 'Loan application not found',
            ], 404);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(LoanApplication::STATUSES)],
        ]);

        $application->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data'    => $application->fresh(['customer', 'business', 'loanProduct']),
        ]);
    }
}