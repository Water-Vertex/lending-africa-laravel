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

class LoanApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $query = LoanApplication::with(['customer', 'business', 'loanProduct'])
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
            'customerByStaff.staff',   // staff info agar staff ne submit ki thi
        ])->find($id);

        if (! $application) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
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

        if (! $application) {
            return response()->json(['success' => false, 'message' => 'Application not found'], 404);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:2000',
        ]);

        // Update status
        $application->update(['status' => 'approved']);

        // Log approval
        LoanApproval::create([
            'application_id' => $application->id,
            'actioned_by'    => auth()->id(),
            'action'         => 'approved',
            'message'        => $validated['message'] ?? null,
            'actioned_at'    => now(),
        ]);

        $debugFile = storage_path('debug_mail.txt');
        $debugLog  = function (string $line) use ($debugFile) {
            // Direct file write, does not depend on Log facade / permissions on storage/logs
            @file_put_contents($debugFile, '[' . now()->toDateTimeString() . '] ' . $line . "\n", FILE_APPEND);
        };

        $debugLog('=== approve() called for application id=' . $application->id . ' by user=' . (auth()->id() ?? 'GUEST/NULL'));
        $debugLog('MAIL CONFIG: ' . json_encode([
            'default'    => config('mail.default'),
            'mailer'     => config('mail.mailers.' . config('mail.default')),
            'from'       => config('mail.from'),
        ]));

        // Email to customer
        $customerEmail = $application->customer->email ?? null;
        $debugLog('customerEmail resolved as: ' . var_export($customerEmail, true));

        if ($customerEmail) {
            $debugLog('BEFORE Mail::send() to customer');
            try {
                Mail::to($customerEmail)
                    ->send(new LoanApprovedMail($application, $validated['message'] ?? ''));
                $debugLog('AFTER Mail::send() to customer - NO EXCEPTION THROWN (does not guarantee delivery, only that send() call completed)');
            } catch (\Throwable $e) {
                // Catching \Throwable instead of \Exception so we also catch Errors (e.g. TypeError, missing class, etc.)
                $debugLog('EXCEPTION on customer mail: ' . get_class($e) . ' - ' . $e->getMessage());
                $debugLog('TRACE: ' . $e->getTraceAsString());
                \Log::error('Loan approved email failed (customer): ' . $e->getMessage());
            }
        } else {
            $debugLog('SKIPPED customer email - $customerEmail was empty/null/falsy');
        }

        // Email to staff if submitted by staff
      // Email to staff if submitted by staff
if ($application->customerByStaff && $application->customerByStaff->staff) {
    $staffEmail = $application->customerByStaff->staff->email ?? null;

    if ($staffEmail) {
        try {
            Mail::to($staffEmail)
                ->send(new LoanActionStaffMail(
                    $application,
                    'approved',
                    $validated['message'] ?? ''
                ));
        } catch (\Exception $e) {
            \Log::error('Loan approved staff email failed: ' . $e->getMessage());
        }
    }
}
        $debugLog('=== approve() finished for application id=' . $application->id);

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

    // ── Additional Info Required ──────────────────────────────────────────────
    public function requestAdditionalInfo(Request $request, $id): JsonResponse
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
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Status under_review pe rakho ya pending — additional info pending
        $application->update(['status' => 'under_review']);

        LoanApproval::create([
            'application_id' => $application->id,
            'actioned_by'    => auth()->id(),
            'action'         => 'additional_info_requested',
            'message'        => $validated['message'],
            'actioned_at'    => now(),
        ]);

        // Email to customer
        $customerEmail = $application->customer->email ?? null;
        if ($customerEmail) {
            try {
                Mail::to($customerEmail)
                    ->send(new LoanAdditionalInfoMail($application, $validated['message']));
            } catch (\Exception $e) {
                \Log::error('Additional info email failed (customer): ' . $e->getMessage());
            }
        }

        // Email to staff
     // Email to staff
if ($application->customerByStaff && $application->customerByStaff->staff) {
    $staffEmail = $application->customerByStaff->staff->email ?? null;

    if ($staffEmail) {
        try {
            Mail::to($staffEmail)
                ->send(new LoanActionStaffMail(
                    $application,
                    'additional_info_requested',
                    $validated['message']
                ));
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
}