<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_customers'      => 0,
                'active_customers'     => 0,
                'blacklisted'          => 0,
                'personal_customers'   => 0,
                'sme_customers'        => 0,

                'total_applications'   => 0,
                'pending_applications' => 0,
                'approved_loans'       => 0,
                'disbursed_loans'      => 0,
                'rejected_loans'       => 0,
                'closed_loans'         => 0,

                'total_disbursed'      => 0,
                'total_repaid'         => 0,
                'pending_repayments'   => 0,

                'total_products'       => 0,
                'total_staff'          => 0,
            ]
        ]);
    }
}