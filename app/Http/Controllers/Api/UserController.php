<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Mail\UserAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with(['role' /* , 'bank' */])->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'    => 'required|exists:roles,id',
            // 'bank_id'    => 'nullable|exists:banks,id',
            'branch'     => 'nullable|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email|max:255',
            'phone'      => 'nullable|string|max:20',
            'password'   => 'required|string|min:6',
            'status'     => 'required|in:active,inactive',
        ]);

        $role = Role::find($request->role_id);

        if ($role && $role->name === 'Branch Manager') {

            // 👇 ab bank_id ki condition hata di, sirf branch name check hoga
            if ($request->branch) {
                $existingManager = User::where('branch', $request->branch)
                                      ->whereHas('role', fn($q) => $q->where('name', 'Branch Manager'))
                                      ->where('status', 'active')
                                      ->exists();

                if ($existingManager) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This branch already has a branch manager',
                        'errors' => ['branch' => ['This branch already has a branch manager']]
                    ], 422);
                }
            }
        }

        try {
            $plainPassword = $request->password;

            $user = User::create([
                'role_id'    => $request->role_id,
                // 'bank_id'    => $request->bank_id,
                'branch'     => $request->branch,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'password'   => Hash::make($plainPassword),
                'status'     => $request->status,
            ]);

            $user->load(['role' /* , 'bank' */]);

            // 🔥 Send account credentials email
            try {
                Mail::to($user->email)->send(new UserAccountCreated(
                    $user->first_name . ' ' . $user->last_name,
                    $user->email,
                    $plainPassword,
                    optional($user->role)->name,
                    null, // bank name not needed for now
                   'https://portal.aiploan.com/login'
                ));
            } catch (\Exception $mailException) {
                Log::error('User account email failed: ' . $mailException->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data'    => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_id'    => 'required|exists:roles,id',
            // 'bank_id'    => 'nullable|exists:banks,id',
            'branch'     => 'nullable|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'phone'      => 'nullable|string|max:20',
            'password'   => 'nullable|string|min:6',
            'status'     => 'required|in:active,inactive',
        ]);

        $role = Role::find($request->role_id);

        if ($role && $role->name === 'Branch Manager') {

            if ($request->branch) {
                $existingManager = User::where('branch', $request->branch)
                                      ->where('id', '!=', $id)
                                      ->whereHas('role', fn($q) => $q->where('name', 'Branch Manager'))
                                      ->where('status', 'active')
                                      ->exists();

                if ($existingManager) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This branch already has a branch manager',
                        'errors' => ['branch' => ['This branch already has a branch manager']]
                    ], 422);
                }
            }
        }

        try {
            $user = User::findOrFail($id);

            $data = [
                'role_id'    => $request->role_id,
                // 'bank_id'    => $request->bank_id,
                'branch'     => $request->branch,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'status'     => $request->status,
            ];

            $newPlainPassword = null;

            if ($request->filled('password')) {
                $newPlainPassword = $request->password;
                $data['password'] = Hash::make($newPlainPassword);
            }

            $user->update($data);
            $user->load(['role' /* , 'bank' */]);

            // 🔥 Send new credentials email only if password was changed
            if ($newPlainPassword) {
                try {
                    Mail::to($user->email)->send(new UserAccountCreated(
                        $user->first_name . ' ' . $user->last_name,
                        $user->email,
                        $newPlainPassword,
                        optional($user->role)->name,
                        null, // bank name not needed for now
                       'https://portal.aiploan.com/login'
                    ));
                } catch (\Exception $mailException) {
                    Log::error('User password update email failed: ' . $mailException->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data'    => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with(['role' /* , 'bank' */])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}