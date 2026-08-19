<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerByStaff;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        // Staff guard use ho raha hai login ke waqt (CustomerByStaffController::store() dekhein)
        $staffId = Auth::guard('staff')->id();

        // Is staff ke saare customer_ids nikalein pivot table se
        $customerIds = CustomerByStaff::where('staff_id', $staffId)
            ->pluck('customer_id');

        // ── Customers (sirf isi staff ke registered) ──
        $totalCustomers    = Customer::whereIn('id', $customerIds)->count();
        $activeCustomers   = Customer::whereIn('id', $customerIds)->where('status', 'active')->count();
        $blacklisted       = Customer::whereIn('id', $customerIds)->where('status', 'blacklisted')->count();
        $personalCustomers = Customer::whereIn('id', $customerIds)->where('customer_type', 'personal')->count();
        $smeCustomers      = Customer::whereIn('id', $customerIds)->where('customer_type', 'sme')->count();

        // ── Loan Applications (unhi customers ke jo isi staff ne register kiye) ──
        $loanQuery = LoanApplication::whereIn('customer_id', $customerIds);

        $totalApplications   = (clone $loanQuery)->count();
        $pendingApplications = (clone $loanQuery)->whereIn('status', ['submitted', 'under_review'])->count();
        $approvedLoans       = (clone $loanQuery)->where('status', 'approved')->count();
        $disbursedLoans      = (clone $loanQuery)->where('status', 'disbursed')->count();
        $rejectedLoans       = (clone $loanQuery)->where('status', 'rejected')->count();
        $closedLoans         = (clone $loanQuery)->where('status', 'closed')->count();

        // ── Financial ──
        $totalDisbursed    = (clone $loanQuery)->where('status', 'disbursed')->sum('loan_amount');
        $totalRepaid       = 0; // repayment model available hote hi yahan connect karna
        $pendingRepayments = $totalDisbursed - $totalRepaid;

        // ── Products (global rahenge) ──
        $totalProducts = LoanProduct::where('status', 'active')->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'total_customers'      => $totalCustomers,
                'active_customers'     => $activeCustomers,
                'blacklisted'          => $blacklisted,
                'personal_customers'   => $personalCustomers,
                'sme_customers'        => $smeCustomers,

                'total_applications'   => $totalApplications,
                'pending_applications' => $pendingApplications,
                'approved_loans'       => $approvedLoans,
                'disbursed_loans'      => $disbursedLoans,
                'rejected_loans'       => $rejectedLoans,
                'closed_loans'         => $closedLoans,

                'total_disbursed'      => (float) $totalDisbursed,
                'total_repaid'         => (float) $totalRepaid,
                'pending_repayments'   => (float) $pendingRepayments,

                'total_products'       => $totalProducts,

                'total_inquiries'      => 0,
                'pending_inquiries'    => 0,
                'email_sent_inquiries' => 0,
                'total_documents'      => 0,
                'pending_documents'    => 0,
                'total_staff'          => 0,
            ],
        ]);
    }
}