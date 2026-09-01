<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\LoanApprovedMail;
use App\Mail\LoanRejectedMail;
use App\Mail\LoanAdditionalInfoMail;
use App\Mail\LoanActionStaffMail;
use App\Models\LoanApplication;
use App\Models\LoanApproval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class LoanApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $query = LoanApplication::with(['customer', 'business', 'loanProduct','agreement', 
])
            ->latest('id');

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

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

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

    public function show($id): JsonResponse
    {
        $application = LoanApplication::with([
            'customer',
            'business',
            'loanProduct',
            'coSigners',
            'customerByStaff.staff', 
            'agreement',           
        ])->find($id);
           if (! $application) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

if ($application->agreement && $application->agreement->signed_file_path) {
    $application->agreement->signed_file_url = asset('storage/' . $application->agreement->signed_file_path);
}
     

        // Check karo k staff ne submit ki thi ya customer ne khud
        $submittedByStaff = $application->customerByStaff !== null;

        return response()->json([
            'success'            => true,
            'data'               => $application,
            'submitted_by_staff' => $submittedByStaff,
            'staff'              => $submittedByStaff ? $application->customerByStaff->staff : null,
        ]);
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $application = LoanApplication::find($id);
        if (! $application) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(LoanApplication::STATUSES)],
        ]);

        $application->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data'    => $application->fresh(['customer', 'business', 'loanProduct']),
        ]);
    }

    // ── Approve ──────────────────────────────────────────────────────────────
    public function approve(Request $request, $id): JsonResponse
{
    $application = LoanApplication::with([
        'customer',
        'loanProduct',
        'customerByStaff.staff',
    ])->find($id);

    if (!$application) {
        return response()->json(['success' => false, 'message' => 'Application not found'], 404);
    }

    $validated = $request->validate([
        'message' => 'nullable|string|max:2000',
    ]);

    $application->update(['status' => 'approved']);

    LoanApproval::create([
        'application_id' => $application->id,
        'actioned_by'    => auth()->id(),
        'action'         => 'approved',
        'message'        => $validated['message'] ?? null,
        'actioned_at'    => now(),
    ]);

    // Agreement token generate karo
    $agreementToken = Str::random(64);
    $agreement = \App\Models\LoanAgreement::create([
        'loan_application_id'        => $application->id,
        'customer_id'                => $application->customer_id,
        'agreement_token'            => $agreementToken,
        'agreement_token_expires_at' => now()->addDays(7),
        'agreement_sent'             => true,
        'agreement_sent_at'          => now(),
        'status'                     => 'pending',
    ]);

    $submitUrl = url('/loan-agreement/' . $agreementToken);

    // Customer email — approved + agreement PDF + submit link
    $customerEmail = $application->customer->email ?? null;
    if ($customerEmail) {
        try {
            Mail::to($customerEmail)
                ->send(new LoanApprovedMail($application, $validated['message'] ?? '', $submitUrl));
        } catch (\Exception $e) {
            \Log::error('Loan approved email failed (customer): ' . $e->getMessage());
        }
    }

    // Staff email
    if ($application->customerByStaff && $application->customerByStaff->staff) {
        $staffEmail = $application->customerByStaff->staff->email ?? null;
        if ($staffEmail) {
            try {
                Mail::to($staffEmail)
                    ->send(new LoanActionStaffMail($application, 'approved', $validated['message'] ?? ''));
            } catch (\Exception $e) {
                \Log::error('Loan approved email failed (staff): ' . $e->getMessage());
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Loan application approved and email sent.',
        'data'    => $application->fresh(),
    ]);
}


    // ── Reject ───────────────────────────────────────────────────────────────
    public function reject(Request $request, $id): JsonResponse
    {
        $application = LoanApplication::with([
            'customer',
            'loanProduct',
            'customerByStaff.staff',
        ])->find($id);

        if (! $application) {
            return response()->json(['success' => false, 'message' => 'Application not found'], 404);
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:2000',
        ]);

        $application->update(['status' => 'rejected']);

        LoanApproval::create([
            'application_id' => $application->id,
            'actioned_by'    => auth()->id(),
            'action'         => 'rejected',
            'message'        => $validated['reason'],
            'actioned_at'    => now(),
        ]);

        // Email to customer
        $customerEmail = $application->customer->email ?? null;
        if ($customerEmail) {
            try {
                Mail::to($customerEmail)
                    ->send(new LoanRejectedMail($application, $validated['reason']));
            } catch (\Exception $e) {
                \Log::error('Loan rejected email failed (customer): ' . $e->getMessage());
            }
        }

// Email to staff
if ($application->customerByStaff && $application->customerByStaff->staff) {
    $staffEmail = $application->customerByStaff->staff->email ?? null;

    if ($staffEmail) {
        try {
            Mail::to($staffEmail)
                ->send(new LoanActionStaffMail(
                    $application,
                    'rejected',
                    $validated['reason']
                ));
        } catch (\Exception $e) {
            \Log::error('Loan rejected staff email failed: ' . $e->getMessage());
        }
    }
}

        return response()->json([
            'success' => true,
            'message' => 'Loan application rejected and email sent.',
            'data'    => $application->fresh(),
        ]);
    }


public function requestAdditionalInfo(Request $request, $id): JsonResponse
{
    $application = LoanApplication::with([
        'customer',
        'loanProduct',
        'customerByStaff.staff',
    ])->find($id);

    if (!$application) {
        return response()->json(['success' => false, 'message' => 'Application not found'], 404);
    }

    $validated = $request->validate([
        'message' => 'required|string|min:10|max:2000',
    ]);

    $application->update(['status' => 'under_review']);

    LoanApproval::create([
        'application_id' => $application->id,
        'actioned_by'    => auth()->id(),
        'action'         => 'additional_info_requested',
        'message'        => $validated['message'],
        'actioned_at'    => now(),
    ]);

    // Token generate karo — 7 din valid
    $token = Str::random(64);
    $application->customer->update([
        'edit_token'            => $token,
        'edit_token_expires_at' => now()->addDays(7),
    ]);

    $editUrl = url('/customer-edit/' . $token);

    // Customer email
    $customerEmail = $application->customer->email ?? null;
    if ($customerEmail) {
        try {
            Mail::to($customerEmail)
                ->send(new LoanAdditionalInfoMail($application, $validated['message'], $editUrl));
        } catch (\Exception $e) {
            \Log::error('Additional info email failed (customer): ' . $e->getMessage());
        }
    }

    // Staff email
    if ($application->customerByStaff && $application->customerByStaff->staff) {
        $staffEmail = $application->customerByStaff->staff->email ?? null;
        if ($staffEmail) {
            try {
                Mail::to($staffEmail)
                    ->send(new LoanActionStaffMail($application, 'additional_info_requested', $validated['message']));
            } catch (\Exception $e) {
                \Log::error('Additional info staff email failed: ' . $e->getMessage());
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Message sent to customer successfully.',
        'data'    => $application->fresh(),
    ]);
}

    public function destroy($id): JsonResponse
{
    $application = LoanApplication::find($id);
    
    if (!$application) {
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }

    // Sirf draft delete ho sakti hai
    if ($application->status !== 'draft') {
        return response()->json([
            'success' => false,
            'message' => 'Only draft applications can be deleted. Submitted applications are permanent records.'
        ], 422);
    }

    \App\Models\LoanApproval::where('application_id', $application->id)->delete();
    \App\Models\CoSigner::where('loan_application_id', $application->id)->delete();
    $application->delete();

    return response()->json(['success' => true, 'message' => 'Application deleted successfully.']);
}



// ── Download Signed Agreement ──────────────────────────────────────────────
public function downloadAgreement($id)
{
    try {
        $application = LoanApplication::with('agreement')->find($id);
        
        if (!$application || !$application->agreement) {
            return response()->json([
                'success' => false, 
                'message' => 'Agreement not found'
            ], 404);
        }
        
        $filePath = $application->agreement->signed_file_path;
        
        if (!$filePath) {
            return response()->json([
                'success' => false, 
                'message' => 'No file path found'
            ], 404);
        }
        
        // ✅ Full path build karein
        $fullPath = storage_path('app/public/' . $filePath);
        
        // ✅ Debug log
        \Log::info('Downloading file:', [
            'file_path' => $filePath,
            'full_path' => $fullPath,
            'exists' => file_exists($fullPath)
        ]);
        
        if (!file_exists($fullPath)) {
            return response()->json([
                'success' => false, 
                'message' => 'File not found on server',
                'path' => $fullPath
            ], 404);
        }
        
        $fileName = $application->agreement->signed_file_original_name ?? 'agreement.pdf';
        
        return response()->download($fullPath, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Download error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}

}