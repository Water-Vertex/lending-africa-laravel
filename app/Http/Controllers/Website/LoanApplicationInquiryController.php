<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\LoanApplicationInquiryMail;
use App\Models\LoanApplicationInquiry;
use App\Models\LoanProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LoanApplicationInquiryController extends Controller
{
    public function store(Request $request)
    {
        // Get active loan product
        $loanProduct = LoanProduct::where('loan_type', $request->loan_type)
            ->where('status', 'active')
            ->first();

        if (!$loanProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Selected loan type is currently unavailable.',
            ], 422);
        }

        // Validation
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:100'],
                'last_name'  => ['required', 'string', 'max:100'],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    // Rule::unique('loan_application_inquiries', 'email'),
                ],

                'phone' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('loan_application_inquiries', 'phone'),
                ],

                'loan_type' => [
                    'required',
                    Rule::in(['personal', 'sme']),
                ],

                'loan_amount' => [
                    'required',
                    'numeric',
                    'min:' . $loanProduct->minimum_amount,
                    'max:' . $loanProduct->maximum_amount,
                ],

                'preferred_bank' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'loan_purpose' => [
                    'required',
                    'string',
                    'max:1000',
                ],

            ], [
                // 'email.unique' =>
                //     'A loan application has already been submitted using this email address.',

                'phone.unique' =>
                    'A loan application has already been submitted using this phone number.',

                'loan_amount.min' =>
                    'Minimum loan amount is ₦' . number_format($loanProduct->minimum_amount, 0),

                'loan_amount.max' =>
                    'Maximum loan amount is ₦' . number_format($loanProduct->maximum_amount, 0),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors'  => $e->errors(),
            ], 422);
        }

        $inquiry = LoanApplicationInquiry::create([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'loan_type'      => $validated['loan_type'],
            'loan_amount'    => $validated['loan_amount'],
            'preferred_bank' => $validated['preferred_bank'],
            'loan_purpose'   => $validated['loan_purpose'],
            'token'          => Str::random(64),
            'email_sent'     => false,
            'status'         => 'pending',
        ]);

        // Email bhejo
        try {
            Mail::to($inquiry->email)
                ->send(new LoanApplicationInquiryMail($inquiry));

            $inquiry->update([
                'email_sent'    => true,
                'email_sent_at' => now(),
                'status'        => 'email_sent',
            ]);
        } catch (\Exception $e) {
            \Log::error('Loan inquiry email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your loan application has been submitted successfully.',
        ]);
    }
}