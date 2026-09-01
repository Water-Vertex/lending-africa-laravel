<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Bank;
use App\Models\StaffForgetPassword;
use App\Mail\StaffAccountCreated;
use App\Mail\StaffForgotPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of staff.
     * Branch Manager => only staff THEY created.
     * Admin (or any other role) => all staff.
     */
    public function index(Request $request)
    {
        try {
            $admin = $request->user();
            $query = Staff::with('bank')->orderBy('id', 'desc');

            if ($admin && $admin->role && $admin->role->name === 'Branch Manager') {
                $query->where('created_by', $admin->id);
            }

            $staff = $query->get();

            return response()->json([
                'success' => true,
                'data' => $staff
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get next staff code - RANDOM numeric combination
     * Format: AIP + 4 random numbers (AIP7392, AIP1847, AIP9503, ...)
     */
    public function getNextStaffCode(Request $request)
    {
        try {
            // Generate a random unique staff code
            $staffCode = $this->generateRandomStaffCode();

            return response()->json([
                'success' => true,
                'data' => $staffCode
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate staff code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created staff member.
     * Auto-generates staff_code (AIP + 4 random numbers) and password.
     * Sends account credentials via email.
     * NO BANK DEPENDENCY - bank_id removed completely
     */
    public function store(Request $request)
    {
        $admin = $request->user();

        // Validation rules - NO bank_id required
        $rules = [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:staff,email|max:255',
            'phone'         => 'nullable|string|max:20',
            'designation'   => 'nullable|string|max:255',
            'employee_id'   => 'nullable|string|max:100',
            'status'        => 'required|in:active,inactive',
            'branch_name'   => 'nullable|string|max:255',
            'staff_code'    => 'nullable|string|max:50',
            'password'      => 'nullable|string|min:6',
        ];

        $request->validate($rules);

        try {
            // Generate unique random staff code (if not provided)
            $staffCode = $request->filled('staff_code') 
                ? $request->staff_code 
                : $this->generateRandomStaffCode();

            $plainPassword = $request->filled('password')
                ? $request->password
                : Str::random(10);

            $staff = Staff::create([
                'staff_code'    => $staffCode,
                'bank_id'       => null,
                'branch_name'   => $request->branch_name,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'designation'   => $request->designation,
                'employee_id'   => $request->employee_id,
                'password'      => Hash::make($plainPassword),
                'status'        => $request->status,
                'created_by'    => $admin->id,
            ]);

            $staff->load('bank');

            // Send account credentials email
            try {
                Mail::to($staff->email)->send(new StaffAccountCreated(
                    $staff->first_name . ' ' . $staff->last_name,
                    $staff->staff_code,
                    $staff->email,
                    $plainPassword,
                    'Bank',
                    'https://portal.aiploan.com/staff/login'  // ✅ Live Link
                ));
            } catch (\Exception $mailException) {
                Log::error('Staff account email failed: ' . $mailException->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Staff created successfully',
                'data'    => $staff,
                'credentials' => [
                    'staff_code' => $staff->staff_code,
                    'password'   => $plainPassword,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create staff',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a random unique staff code
     * Format: AIP + 4 random numbers (0000-9999)
     * Example: AIP7392, AIP1847, AIP9503
     * Guaranteed to be unique
     */
    private function generateRandomStaffCode(): string
    {
        $prefix = 'AIP';
        
        do {
            // Generate 4 random digits (0000 to 9999)
            $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $code = $prefix . $randomNumber;
            
        } while (Staff::where('staff_code', $code)->exists());

        return $code;
    }

    /**
     * Display the specified staff member.
     */
    public function show(Request $request, $id)
    {
        try {
            $admin = $request->user();
            $staff = Staff::with('bank')->findOrFail($id);

            if ($admin && $admin->role && $admin->role->name === 'Branch Manager' && $staff->created_by !== $admin->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data'    => $staff
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Staff not found'
            ], 404);
        }
    }

    /**
     * Update the specified staff member.
     * NO BANK DEPENDENCY - bank_id/branch_name removed
     */
    public function update(Request $request, $id)
    {
        $admin = $request->user();
        $isBranchManager = $admin && $admin->role && $admin->role->name === 'Branch Manager';

        try {
            $staff = Staff::findOrFail($id);

            if ($isBranchManager && $staff->created_by !== $admin->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Staff not found'
            ], 404);
        }

        $rules = [
            'first_name'          => 'required|string|max:255',
            'last_name'           => 'required|string|max:255',
            'email'               => [
                'required',
                'email',
                'max:255',
                Rule::unique('staff', 'email')->ignore($id)
            ],
            'phone'               => 'nullable|string|max:20',
            'designation'         => 'nullable|string|max:255',
            'employee_id'         => 'nullable|string|max:100',
            'status'              => 'required|in:active,inactive',
            'branch_name'         => 'nullable|string|max:255',
            'regenerate_password' => 'nullable|boolean',
        ];

        $request->validate($rules);

        try {
            $data = [
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'designation'   => $request->designation,
                'employee_id'   => $request->employee_id,
                'status'        => $request->status,
                'branch_name'   => $request->branch_name,
            ];

            $newPlainPassword = null;

            if ($request->boolean('regenerate_password')) {
                $newPlainPassword = Str::random(10);
                $data['password'] = Hash::make($newPlainPassword);
            }

            $staff->update($data);
            $staff->load('bank');

            if ($newPlainPassword) {
                try {
                    Mail::to($staff->email)->send(new StaffAccountCreated(
                        $staff->first_name . ' ' . $staff->last_name,
                        $staff->staff_code,
                        $staff->email,
                        $newPlainPassword,
                        'Bank',
                        'https://portal.aiploan.com/staff/login'  // ✅ Live Link
                    ));
                } catch (\Exception $mailException) {
                    Log::error('Staff password regenerate email failed: ' . $mailException->getMessage());
                }
            }

            $response = [
                'success' => true,
                'message' => $newPlainPassword
                    ? 'Staff updated and password regenerated successfully'
                    : 'Staff updated successfully',
                'data'    => $staff
            ];

            if ($newPlainPassword) {
                $response['credentials'] = [
                    'staff_code' => $staff->staff_code,
                    'password'   => $newPlainPassword,
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update staff',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $admin = $request->user();
            $staff = Staff::findOrFail($id);

            if ($admin && $admin->role && $admin->role->name === 'Branch Manager' && $staff->created_by !== $admin->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $staff->delete();

            return response()->json([
                'success' => true,
                'message' => 'Staff deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete staff',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated staff's own profile (self-service).
     */
    public function profile(Request $request)
    {
        try {
            $staff = $request->user()->load('bank');

            return response()->json([
                'success' => true,
                'data' => $staff
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update authenticated staff's own profile.
     */
    public function updateProfile(Request $request)
    {
        $staff = $request->user();

        $request->validate([
            'branch_name'      => 'nullable|string|max:255',
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => [
                'required',
                'email',
                'max:255',
                Rule::unique('staff', 'email')->ignore($staff->id),
            ],
            'phone'            => 'nullable|string|max:20',
            'designation'      => 'nullable|string|max:255',
            'employee_id'      => 'nullable|string|max:100',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password'     => 'nullable|string|min:6|confirmed',
        ]);

        try {
            $data = [
                'branch_name'  => $request->branch_name,
                'first_name'   => $request->first_name,
                'last_name'    => $request->last_name,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'designation'  => $request->designation,
                'employee_id'  => $request->employee_id,
            ];

            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $staff->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is incorrect'
                    ], 422);
                }
                $data['password'] = Hash::make($request->new_password);
            }

            $staff->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data'    => $staff->fresh()->load('bank')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // ✅ FORGOT PASSWORD (STAFF) - LIVE LINK
    // ============================================================

    /**
     * Send password reset link to staff email
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $staff = Staff::where('email', $request->email)->first();

            if (!$staff) {
                return response()->json([
                    'success' => false,
                    'message' => 'This staff email does not exist in our system.'
                ], 404);
            }

            // Remove any previous reset tokens
            StaffForgetPassword::where('email', $staff->email)->delete();

            // Generate secure token
            $plainToken = Str::random(64);

            StaffForgetPassword::create([
                'email'      => $staff->email,
                'token'      => hash('sha256', $plainToken),
                'expires_at' => Carbon::now()->addMinutes(15),
            ]);

            // ✅ LIVE RESET LINK
            $resetLink = 'https://portal.aiploan.com/staff/reset-password?token=' . $plainToken . '&email=' . urlencode($staff->email);
// $resetLink = 'http://localhost:4200/staff/reset-password?token=' . $plainToken . '&email=' . urlencode($staff->email);
            try {
                Mail::to($staff->email)->send(new StaffForgotPasswordMail(
                    $staff->first_name . ' ' . $staff->last_name,
                    $resetLink,
                    15
                ));

                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link has been sent to your email.'
                ], 200);

            } catch (\Exception $mailException) {
                Log::error('Staff forgot password email failed: ' . $mailException->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email. Please try again.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Staff forgotPassword error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // ✅ RESET PASSWORD (STAFF)
    // ============================================================

    /**
     * Reset staff password using token
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $record = StaffForgetPassword::where('email', $request->email)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$record || !hash_equals($record->token, hash('sha256', $request->token))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset link'
                ], 422);
            }

            if (Carbon::now()->greaterThan($record->expires_at)) {
                $record->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'This reset link has expired. Please request a new one.'
                ], 422);
            }

            $staff = Staff::where('email', $request->email)->first();

            if (!$staff) {
                return response()->json([
                    'success' => false,
                    'message' => 'Staff account not found'
                ], 404);
            }

            $staff->password = Hash::make($request->password);
            $staff->save();

            // Delete token so it cannot be reused
            $record->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Staff resetPassword error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}