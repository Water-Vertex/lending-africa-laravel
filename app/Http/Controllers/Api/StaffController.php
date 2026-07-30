<?php

// namespace App\Http\Controllers\Api;
// use App\Http\Controllers\Controller;
// use App\Models\Staff;
// use App\Models\Bank;
// use App\Mail\StaffAccountCreated;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Str;
// use Illuminate\Validation\Rule;

// class StaffController extends Controller
// {
//     /**
//      * Display a listing of staff.
//      */
//     public function index()
//     {
//         try {
//             $staff = Staff::with('bank')->orderBy('id', 'desc')->get();

//             return response()->json([
//                 'success' => true,
//                 'data' => $staff
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to fetch staff',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }

   
//   // 🔥 ORIGINAL - Get last staff code (global)
//     // public function getLastStaffCode()
//     // {
//     //     try {
//     //         $lastStaff = Staff::orderBy('id', 'desc')->first();
            
//     //         if ($lastStaff) {
//     //             return response()->json([
//     //                 'success' => true,
//     //                 'data' => $lastStaff->staff_code
//     //             ], 200);
//     //         }
            
//     //         return response()->json([
//     //             'success' => true,
//     //             'data' => 'STF0000'
//     //         ], 200);
            
//     //     } catch (\Exception $e) {
//     //         return response()->json([
//     //             'success' => false,
//     //             'message' => 'Failed to get last staff code',
//     //             'error' => $e->getMessage()
//     //         ], 500);
//     //     }
//     // }


//     public function getLastStaffCode(Request $request)
// {
//     try {
//         $bankId = $request->query('bank_id');

//         if (!$bankId) {
//             return response()->json([
//                 'success' => true,
//                 'data' => null
//             ], 200);
//         }

//         $bank = Bank::find($bankId);

//         if (!$bank) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Bank not found'
//             ], 404);
//         }

//         $prefix = $this->getBankPrefix($bank->name);

//         $lastStaff = Staff::where('staff_code', 'like', $prefix . '%')
//             ->orderBy('id', 'desc')
//             ->first();

//         $nextNumber = 1;
//         if ($lastStaff) {
//             $numPart = substr($lastStaff->staff_code, strlen($prefix));
//             $nextNumber = ((int) $numPart) + 1;
//         }

//         $nextCode = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

//         return response()->json([
//             'success' => true,
//             'data' => $nextCode
//         ], 200);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to get last staff code',
//             'error' => $e->getMessage()
//         ], 500);
//     }
//   }
//     /**
//      * Store a newly created staff member.
//      * Auto-generates staff_code and password.
//      * Plain password is returned in the response so admin can share it manually.
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'bank_id'       => 'required|exists:banks,id',
//             'branch_name'   => 'nullable|string|max:255',
//             'first_name'    => 'required|string|max:255',
//             'last_name'     => 'required|string|max:255',
//             'email'         => 'required|email|unique:staff,email|max:255',
//             'phone'         => 'nullable|string|max:20',
//             'designation'   => 'nullable|string|max:255',
//             'employee_id'   => 'nullable|string|max:100',
//             'status'        => 'required|in:active,inactive',
//         ]);

//         try {
//            $bank = Bank::findOrFail($request->bank_id);
//             $staffCode = $this->generateStaffCode($bank);
//             // 2) Generate random plain password
//             // $plainPassword = Str::random(4) . rand(10, 99) . Str::random(4);
//             $plainPassword = $request->filled('password') 
//     ? $request->password 
//     : Str::random(4) . rand(10, 99) . Str::random(4);

//             // 3) Create staff record
//             $staff = Staff::create([
//                 'staff_code'    => $staffCode,
//                 'bank_id'       => $request->bank_id,
//                 'branch_name'   => $request->branch_name,
//                 'first_name'    => $request->first_name,
//                 'last_name'     => $request->last_name,
//                 'email'         => $request->email,
//                 'phone'         => $request->phone,
//                 'designation'   => $request->designation,
//                 'employee_id'   => $request->employee_id,
//                 'password'      => Hash::make($plainPassword),
//                 'status'        => $request->status,
//             ]);

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Staff created successfully',
//                 'data'    => $staff->load('bank'),
//                 'credentials' => [
//                     'staff_code' => $staff->staff_code,
//                     'password'   => $plainPassword,   // Sirf yahin ek baar dikhega
//                 ]
//             ], 201);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to create staff',
//                 'error'   => $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Display the specified staff member.
//      */
//     public function show($id)
//     {
//         try {
//             $staff = Staff::with('bank')->findOrFail($id);

//             return response()->json([
//                 'success' => true,
//                 'data'    => $staff
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Staff not found'
//             ], 404);
//         }
//     }

//     /**
//      * Update the specified staff member.
//      * staff_code is never changed after creation.
//      * Optionally regenerate a new password (returned in response).
//      */
//     public function update(Request $request, $id)
//     {
//         $request->validate([
//             'bank_id'             => 'required|exists:banks,id',
//             'branch_name'         => 'nullable|string|max:255',
//             'first_name'          => 'required|string|max:255',
//             'last_name'           => 'required|string|max:255',
//             'email'               => [
//                 'required',
//                 'email',
//                 'max:255',
//                 Rule::unique('staff', 'email')->ignore($id)
//             ],
//             'phone'               => 'nullable|string|max:20',
//             'designation'         => 'nullable|string|max:255',
//             'employee_id'         => 'nullable|string|max:100',
//             'status'              => 'required|in:active,inactive',
//             'regenerate_password' => 'nullable|boolean',
//         ]);

//         try {
//             $staff = Staff::findOrFail($id);

//             $data = [
//                 'bank_id'       => $request->bank_id,
//                 'branch_name'   => $request->branch_name,
//                 'first_name'    => $request->first_name,
//                 'last_name'     => $request->last_name,
//                 'email'         => $request->email,
//                 'phone'         => $request->phone,
//                 'designation'   => $request->designation,
//                 'employee_id'   => $request->employee_id,
//                 'status'        => $request->status,
//             ];

//             $newPlainPassword = null;

//             if ($request->boolean('regenerate_password')) {
//                 $newPlainPassword = Str::random(4) . rand(10, 99) . Str::random(4);
//                 $data['password'] = Hash::make($newPlainPassword);
//             }

//             $staff->update($data);

//             $response = [
//                 'success' => true,
//                 'message' => $newPlainPassword
//                     ? 'Staff updated and password regenerated successfully'
//                     : 'Staff updated successfully',
//                 'data'    => $staff->load('bank')
//             ];

//             if ($newPlainPassword) {
//                 $response['credentials'] = [
//                     'staff_code' => $staff->staff_code,
//                     'password'   => $newPlainPassword,
//                 ];
//             }

//             return response()->json($response, 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to update staff',
//                 'error'   => $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Remove the specified staff member.
//      */
//     public function destroy($id)
//     {
//         try {
//             $staff = Staff::findOrFail($id);
//             $staff->delete();

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Staff deleted successfully'
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to delete staff',
//                 'error'   => $e->getMessage()
//             ], 500);
//         }
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Helpers
//     |--------------------------------------------------------------------------
//     */

//     /**
//      * Generate the next sequential staff code, e.g. STF0001, STF0002...
//      */
//     // private function generateStaffCode(): string
//     // {
//     //     do {
//     //         $lastStaff = Staff::orderBy('id', 'desc')->first();

//     //         $nextNumber = $lastStaff
//     //             ? ((int) substr($lastStaff->staff_code, 3)) + 1
//     //             : 1;

//     //         $code = 'STF' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
//     //     } while (Staff::where('staff_code', $code)->exists());

//     //     return $code;
//     // }


//     /**
//  * Get bank's first letter as prefix, e.g. "Polaris Bank" -> P
//  */
// private function getBankPrefix(string $bankName): string
// {
//     $clean = preg_replace('/[^A-Za-z]/', '', $bankName);
//     return $clean !== '' ? strtoupper(substr($clean, 0, 1)) : 'X';
// }

// /**
//  * Generate the next unique staff code for a given bank, e.g. P0001, Z0004...
//  */
// private function generateStaffCode(Bank $bank): string
// {
//     $prefix = $this->getBankPrefix($bank->name);

//     do {
//         $lastStaff = Staff::where('staff_code', 'like', $prefix . '%')
//             ->orderBy('id', 'desc')
//             ->first();

//         $nextNumber = 1;
//         if ($lastStaff) {
//             $numPart = substr($lastStaff->staff_code, strlen($prefix));
//             $nextNumber = ((int) $numPart) + 1;
//         }

//         $code = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
//     } while (Staff::where('staff_code', $code)->exists());

//     return $code;
// }



// /**
//  * Get authenticated staff's own profile (self-service).
//  */
// public function profile(Request $request)
// {
//     try {
//         $staff = $request->user()->load('bank');

//         return response()->json([
//             'success' => true,
//             'data' => $staff
//         ], 200);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to fetch profile',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

// /**
//  * Update authenticated staff's own profile.
//  * staff_code aur bank_id yahan se kabhi update nahi honge.
//  */
// public function updateProfile(Request $request)
// {
//     $staff = $request->user();

//     $request->validate([
//         'branch_name'      => 'nullable|string|max:255',
//         'first_name'       => 'required|string|max:255',
//         'last_name'        => 'required|string|max:255',
//         'email'            => [
//             'required',
//             'email',
//             'max:255',
//             Rule::unique('staff', 'email')->ignore($staff->id),
//         ],
//         'phone'            => 'nullable|string|max:20',
//         'designation'      => 'nullable|string|max:255',
//         'employee_id'      => 'nullable|string|max:100',
//         'current_password' => 'nullable|required_with:new_password|string',
//         'new_password'     => 'nullable|string|min:6|confirmed',
//     ]);

//     try {
//         $data = [
//             'branch_name'  => $request->branch_name,
//             'first_name'   => $request->first_name,
//             'last_name'    => $request->last_name,
//             'email'        => $request->email,
//             'phone'        => $request->phone,
//             'designation'  => $request->designation,
//             'employee_id'  => $request->employee_id,
//         ];

//         if ($request->filled('new_password')) {
//             if (!Hash::check($request->current_password, $staff->password)) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Current password is incorrect'
//                 ], 422);
//             }
//             $data['password'] = Hash::make($request->new_password);
//         }

//         $staff->update($data);

//         return response()->json([
//             'success' => true,
//             'message' => 'Profile updated successfully',
//             'data'    => $staff->fresh()->load('bank')
//         ], 200);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to update profile',
//             'error'   => $e->getMessage()
//         ], 500);
//     }
// }
// }


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Bank;
use App\Mail\StaffAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of staff.
     */
    public function index()
    {
        try {
            $staff = Staff::with('bank')->orderBy('id', 'desc')->get();

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
     * Get next staff code for a given bank.
     */
    public function getLastStaffCode(Request $request)
    {
        try {
            $bankId = $request->query('bank_id');

            if (!$bankId) {
                return response()->json([
                    'success' => true,
                    'data' => null
                ], 200);
            }

            $bank = Bank::find($bankId);

            if (!$bank) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bank not found'
                ], 404);
            }

            $prefix = $this->getBankPrefix($bank->name);

            $lastStaff = Staff::where('staff_code', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastStaff) {
                $numPart = substr($lastStaff->staff_code, strlen($prefix));
                $nextNumber = ((int) $numPart) + 1;
            }

            $nextCode = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            return response()->json([
                'success' => true,
                'data' => $nextCode
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get last staff code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created staff member.
     * Auto-generates staff_code and password.
     * Sends account credentials via email.
     * Plain password is also returned in the response so admin can share it manually.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_id'       => 'required|exists:banks,id',
            'branch_name'   => 'nullable|string|max:255',
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:staff,email|max:255',
            'phone'         => 'nullable|string|max:20',
            'designation'   => 'nullable|string|max:255',
            'employee_id'   => 'nullable|string|max:100',
            'status'        => 'required|in:active,inactive',
        ]);

        try {
            $bank = Bank::findOrFail($request->bank_id);
            $staffCode = $this->generateStaffCode($bank);

            $plainPassword = $request->filled('password')
                ? $request->password
                : Str::random(4) . rand(10, 99) . Str::random(4);

            $staff = Staff::create([
                'staff_code'    => $staffCode,
                'bank_id'       => $request->bank_id,
                'branch_name'   => $request->branch_name,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'designation'   => $request->designation,
                'employee_id'   => $request->employee_id,
                'password'      => Hash::make($plainPassword),
                'status'        => $request->status,
            ]);

            $staff->load('bank');

            // 🔥 Send account credentials email
            try {
                Mail::to($staff->email)->send(new StaffAccountCreated(
                    $staff->first_name . ' ' . $staff->last_name,
                    $staff->staff_code,
                    $staff->email,
                    $plainPassword,
                    optional($staff->bank)->name,
                    'http://localhost:4200/staff/login'
                //    'https://portal.aiploan.com/staff/login'
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
                    'password'   => $plainPassword,   // Sirf yahin ek baar dikhega
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
     * Display the specified staff member.
     */
    public function show($id)
    {
        try {
            $staff = Staff::with('bank')->findOrFail($id);

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
     * staff_code is never changed after creation.
     * Optionally regenerate a new password (emailed + returned in response).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_id'             => 'required|exists:banks,id',
            'branch_name'         => 'nullable|string|max:255',
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
            'regenerate_password' => 'nullable|boolean',
        ]);

        try {
            $staff = Staff::findOrFail($id);

            $data = [
                'bank_id'       => $request->bank_id,
                'branch_name'   => $request->branch_name,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'designation'   => $request->designation,
                'employee_id'   => $request->employee_id,
                'status'        => $request->status,
            ];

            $newPlainPassword = null;

            if ($request->boolean('regenerate_password')) {
                $newPlainPassword = Str::random(4) . rand(10, 99) . Str::random(4);
                $data['password'] = Hash::make($newPlainPassword);
            }

            $staff->update($data);
            $staff->load('bank');

            // 🔥 Send new credentials email if password was regenerated
            if ($newPlainPassword) {
                try {
                    Mail::to($staff->email)->send(new StaffAccountCreated(
                        $staff->first_name . ' ' . $staff->last_name,
                        $staff->staff_code,
                        $staff->email,
                        $newPlainPassword,
                        optional($staff->bank)->name,
                        'http://localhost:4200/staff/login'
                        // 'https://portal.aiploan.com/staff/login'
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
    public function destroy($id)
    {
        try {
            $staff = Staff::findOrFail($id);
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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get bank's first letter as prefix, e.g. "Polaris Bank" -> P
     */
    private function getBankPrefix(string $bankName): string
    {
        $clean = preg_replace('/[^A-Za-z]/', '', $bankName);
        return $clean !== '' ? strtoupper(substr($clean, 0, 1)) : 'X';
    }

    /**
     * Generate the next unique staff code for a given bank, e.g. P0001, Z0004...
     */
    private function generateStaffCode(Bank $bank): string
    {
        $prefix = $this->getBankPrefix($bank->name);

        do {
            $lastStaff = Staff::where('staff_code', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastStaff) {
                $numPart = substr($lastStaff->staff_code, strlen($prefix));
                $nextNumber = ((int) $numPart) + 1;
            }

            $code = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } while (Staff::where('staff_code', $code)->exists());

        return $code;
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
     * staff_code aur bank_id yahan se kabhi update nahi honge.
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
}