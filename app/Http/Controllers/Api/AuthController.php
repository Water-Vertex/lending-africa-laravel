<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        /** @var User $user */
        $user  = Auth::user();

        // Only active users can login
        if ($user->status !== 'active') {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.',
            ], 403);
        }

        // Revoke old tokens
        $user->tokens()->delete();

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role?->name,
                'status'     => $user->status,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role?->name,
                'status'     => $user->status,
            ],
        ]);
    }




      /*
    |--------------------------------------------------------------------------
    | STAFF LOGIN (web / blade — session guard 'staff')
    |--------------------------------------------------------------------------
    */

//     public function showStaffLoginForm(): \Illuminate\View\View|RedirectResponse
//     {
//         if (Auth::guard('staff')->check()) {
//             return redirect()->route('staff.dashboard');
//         }

//        return view('user.pages.staff.login');
//     }

//     public function staffLogin(Request $request): RedirectResponse
//     {
//         $request->validate([
//             'staff_code' => 'required|string',
//             'password'   => 'required|string',
//         ]);

//         $credentials = [
//             'staff_code' => strtoupper(trim($request->staff_code)),
//             'password'   => $request->password,
//         ];

//         if (!Auth::guard('staff')->attempt($credentials, $request->boolean('remember'))) {
//             return back()
//                 ->withErrors(['staff_code' => 'Invalid staff code or password.'])
//                 ->onlyInput('staff_code');
//         }

//         $staff = Auth::guard('staff')->user();

//         if ($staff->status !== 'active') {
//             Auth::guard('staff')->logout();
//             return back()
//                 ->withErrors(['staff_code' => 'Your account is inactive. Please contact admin.'])
//                 ->onlyInput('staff_code');
//         }

//         $request->session()->regenerate();

//         return redirect()->route('staff.dashboard');
//     }

//     public function staffDashboard(): \Illuminate\View\View
//     {
//         $staff = Auth::guard('staff')->user()->load('bank');

//         return view('user.pages.staff.dashboard', compact('staff'));  
//     }

//     public function staffLogout(Request $request): RedirectResponse
//     {
//         Auth::guard('staff')->logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return redirect()->route('staff.login.show');
//     }
//     public function showChangePasswordForm(): \Illuminate\View\View
// {
//     return view('user.pages.staff.reset-password');
// }

// public function updatePassword(Request $request): RedirectResponse
// {
//     $request->validate([
//         'current_password' => 'required|string',
//         'new_password'      => 'required|string|min:6|confirmed',
//     ], [
//         'new_password.confirmed' => 'New password and confirm password do not match.',
//     ]);

//     $staff = Auth::guard('staff')->user();

//     if (!Hash::check($request->current_password, $staff->password)) {
//         return back()
//             ->withErrors(['current_password' => 'Current password is incorrect.'])
//             ->onlyInput('current_password');
//     }

//     if (Hash::check($request->new_password, $staff->password)) {
//         return back()
//             ->withErrors(['new_password' => 'New password must be different from the current password.'])
//             ->onlyInput();
//     }

//     $staff->update([
//         'password' => Hash::make($request->new_password),
//     ]);

//     return back()->with('success', 'Password changed successfully.');
// }




/*
|--------------------------------------------------------------------------
| STAFF LOGIN (API — token based, Angular ke liye)
|--------------------------------------------------------------------------
*/

public function staffLogin(Request $request): JsonResponse
    {
        $request->validate([
            'staff_code' => 'required|string',
            'password'   => 'required|string',
        ]);

        $staffCode = strtoupper(trim($request->staff_code));

        // 1. Directly fetch staff by staff_code
        $staff = Staff::where('staff_code', $staffCode)->first();

        // 2. Check existence & password match
        if (!$staff || !Hash::check($request->password, $staff->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid staff code or password.',
            ], 401);
        }

        // 3. Status check
        if ($staff->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact admin.',
            ], 403);
        }

        // 4. Revoke old tokens & create new Sanctum token
        $staff->tokens()->delete();
        $token = $staff->createToken('staff-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token'   => $token,
            'staff'   => [
                'id'         => $staff->id,
                'staff_code' => $staff->staff_code,
                'first_name' => $staff->first_name ?? null,
                'last_name'  => $staff->last_name ?? null,
                'status'     => $staff->status,
            ],
        ]);
    }

    public function staffLogout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }


}