<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\LoanAgreement;
use App\Models\LoanApplication;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    // Show agreement submission page
    public function show(string $token)
    {
        $agreement = LoanAgreement::where('agreement_token', $token)
            ->with(['loanApplication.customer', 'loanApplication.loanProduct'])
            ->first();

        if (!$agreement) {
            abort(404, 'Invalid agreement link.');
        }

        // Check expired
        if ($agreement->isExpired() || $agreement->status === 'expired') {
            // Mark expired + reject loan
            if ($agreement->status === 'pending') {
                $agreement->update(['status' => 'expired']);
                $agreement->loanApplication->update(['status' => 'rejected']);
            }
            return view('user.pages.agreement-expired', compact('agreement'));
        }

        // Already submitted
        if ($agreement->status === 'submitted') {
            return view('user.pages.agreement-already-submitted', compact('agreement'));
        }

        return view('user.pages.agreement-submit', compact('agreement', 'token'));
    }

    // Submit signed agreement
    public function submit(Request $request, string $token)
    {
        $agreement = LoanAgreement::where('agreement_token', $token)
            ->with(['loanApplication.customer', 'loanApplication.loanProduct'])
            ->first();

        if (!$agreement) {
            return response()->json(['success' => false, 'message' => 'Invalid link.'], 404);
        }

        if ($agreement->isExpired() || $agreement->status === 'expired') {
            $agreement->update(['status' => 'expired']);
            $agreement->loanApplication->update(['status' => 'rejected']);
            return response()->json(['success' => false, 'message' => 'This link has expired. Your loan has been cancelled.'], 422);
        }

        if ($agreement->status === 'submitted') {
            return response()->json(['success' => false, 'message' => 'You have already submitted your signed agreement.'], 422);
        }

        $request->validate([
            'signed_agreement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'signed_agreement.required' => 'Please upload your signed agreement file.',
            'signed_agreement.mimes'    => 'Only JPG, PNG, or PDF files are accepted.',
            'signed_agreement.max'      => 'File size must not exceed 5MB.',
        ]);

        $file         = $request->file('signed_agreement');
        $originalName = $file->getClientOriginalName();
        $mimeType     = $file->getMimeType();
        $path         = $file->store('signed-agreements', 'public');

        $agreement->update([
            'signed_file_path'          => $path,
            'signed_file_original_name' => $originalName,
            'signed_file_type'          => $mimeType,
            'signed_submitted_at'       => now(),
            'status'                    => 'submitted',
        ]);

        // Admin ko email bhejo
        try {
            \Mail::to('info@aiploan.com')
                ->send(new \App\Mail\AgreementSubmittedAdminMail($agreement));
        } catch (\Exception $e) {
            \Log::error('Agreement submitted admin email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your signed agreement has been submitted successfully. We will contact you shortly.',
        ]);
    }
}