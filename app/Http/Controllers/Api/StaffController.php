<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

   
  // 🔥 ORIGINAL - Get last staff code (global)
    public function getLastStaffCode()
    {
        try {
            $lastStaff = Staff::orderBy('id', 'desc')->first();
            
            if ($lastStaff) {
                return response()->json([
                    'success' => true,
                    'data' => $lastStaff->staff_code
                ], 200);
            }
            
            return response()->json([
                'success' => true,
                'data' => 'STF0000'
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
     * Plain password is returned in the response so admin can share it manually.
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
            // 1) Generate unique staff_code (STF0001, STF0002, ...) — yehi login id hai
            $staffCode = $this->generateStaffCode();

            // 2) Generate random plain password
            $plainPassword = Str::random(4) . rand(10, 99) . Str::random(4);

            // 3) Create staff record
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

            return response()->json([
                'success' => true,
                'message' => 'Staff created successfully',
                'data'    => $staff->load('bank'),
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
     * Optionally regenerate a new password (returned in response).
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

            $response = [
                'success' => true,
                'message' => $newPlainPassword
                    ? 'Staff updated and password regenerated successfully'
                    : 'Staff updated successfully',
                'data'    => $staff->load('bank')
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
     * Generate the next sequential staff code, e.g. STF0001, STF0002...
     */
    private function generateStaffCode(): string
    {
        do {
            $lastStaff = Staff::orderBy('id', 'desc')->first();

            $nextNumber = $lastStaff
                ? ((int) substr($lastStaff->staff_code, 3)) + 1
                : 1;

            $code = 'STF' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } while (Staff::where('staff_code', $code)->exists());

        return $code;
    }
}