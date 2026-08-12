<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\LoanApplicationInquiry;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        // ── Customers ──
        $totalCustomers     = Customer::count();
        $activeCustomers    = Customer::where('status', 'active')->count();
        $blacklisted        = Customer::where('status', 'blacklisted')->count();
        $personalCustomers  = Customer::where('customer_type', 'personal')->count();
        $smeCustomers       = Customer::where('customer_type', 'sme')->count();

        // ── Loan Applications ──
        $totalApplications  = LoanApplication::count();
        $pendingApplications = LoanApplication::whereIn('status', ['submitted', 'under_review'])->count();
        $approvedLoans      = LoanApplication::where('status', 'approved')->count();
        $disbursedLoans     = LoanApplication::where('status', 'disbursed')->count();
        $rejectedLoans      = LoanApplication::where('status', 'rejected')->count();
        $closedLoans        = LoanApplication::where('status', 'closed')->count();

        // ── Financial ──
        $totalDisbursed     = LoanApplication::where('status', 'disbursed')
                                ->sum('loan_amount');
        $totalRepaid        = 0; // repayment model yahan connect karo agar hai
        $pendingRepayments  = $totalDisbursed - $totalRepaid;

        // ── Products ──
        $totalProducts      = LoanProduct::where('status', 'active')->count();

        // ── Inquiries ──
        $totalInquiries     = LoanApplicationInquiry::count();
        $pendingInquiries   = LoanApplicationInquiry::where('status', 'pending')->count();
        $emailSentInquiries = LoanApplicationInquiry::where('status', 'email_sent')->count();

        // ── Documents ──
        $totalDocuments     = CustomerDocument::count();
        $pendingDocs        = CustomerDocument::where('verification_status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data'    => [
                // Customers
                'total_customers'     => $totalCustomers,
                'active_customers'    => $activeCustomers,
                'blacklisted'         => $blacklisted,
                'personal_customers'  => $personalCustomers,
                'sme_customers'       => $smeCustomers,

                // Loan Applications
                'total_applications'  => $totalApplications,
                'pending_applications'=> $pendingApplications,
                'approved_loans'      => $approvedLoans,
                'disbursed_loans'     => $disbursedLoans,
                'rejected_loans'      => $rejectedLoans,
                'closed_loans'        => $closedLoans,

                // Financial
                'total_disbursed'     => (float) $totalDisbursed,
                'total_repaid'        => (float) $totalRepaid,
                'pending_repayments'  => (float) $pendingRepayments,

                // Products
                'total_products'      => $totalProducts,

                // Inquiries
                'total_inquiries'     => $totalInquiries,
                'pending_inquiries'   => $pendingInquiries,
                'email_sent_inquiries'=> $emailSentInquiries,

                // Documents
                'total_documents'     => $totalDocuments,
                'pending_documents'   => $pendingDocs,

                // Staff (agar Staff model hai)
                'total_staff'         => \App\Models\Staff::count(),
            ],
        ]);
    }
}