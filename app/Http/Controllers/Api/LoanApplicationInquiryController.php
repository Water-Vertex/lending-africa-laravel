<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\LoanApplicationInquiry;
use Illuminate\Http\Request;
 
class LoanApplicationInquiryController extends Controller
{
    /**
     * List loan inquiries with search, filters & pagination.
     */
    public function index(Request $request)
    {
        $query = LoanApplicationInquiry::query();
 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->loan_type);
        }
 
        $perPage = (int) $request->get('per_page', 15);
 
        $inquiries = $query->latest()->paginate($perPage);
 
        return response()->json([
            'success' => true,
            'data'    => $inquiries->items(),
            'meta'    => [
                'current_page' => $inquiries->currentPage(),
                'last_page'    => $inquiries->lastPage(),
                'per_page'     => $inquiries->perPage(),
                'total'        => $inquiries->total(),
            ],
        ]);
    }
 
    /**
     * Single inquiry detail (agar admin ne kabhi row expand/detail dekhna ho).
     */
    public function show($id)
    {
        $inquiry = LoanApplicationInquiry::findOrFail($id);
 
        return response()->json([
            'success' => true,
            'data'    => $inquiry,
        ]);
    }
 
    /**
     * Dashboard-style stats cards ke liye.
     */
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total'      => LoanApplicationInquiry::count(),
                'pending'    => LoanApplicationInquiry::where('status', 'pending')->count(),
                'email_sent' => LoanApplicationInquiry::where('status', 'email_sent')->count(),
                'verified'   => LoanApplicationInquiry::where('status', 'verified')->count(),
                'completed'  => LoanApplicationInquiry::where('status', 'completed')->count(),
                'expired'    => LoanApplicationInquiry::where('status', 'expired')->count(),
            ],
        ]);
    }
}