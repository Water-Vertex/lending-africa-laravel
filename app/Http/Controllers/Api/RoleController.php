<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        try {
            $roles = Role::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:roles,name|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $role = Role::create([
                'name'        => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data'    => $role
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($id)
            ],
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name'        => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'data'    => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role.
     */
/**
 * Remove the specified role safely.
 */


/**
 * Remove the specified role.
 */
public function destroy($id)
{
    Log::info("Delete request hit for Role ID: " . $id);

    try {
        // 1. Role search karein
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        // 2. Protected system roles check
        if (in_array(strtolower($role->name), ['super_admin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'This role cannot be deleted.'
            ], 403);
        }

        // 3. Direct DB query se relationship clean karein (Safe from class resolution errors)
        DB::table('model_has_roles')->where('role_id', $id)->delete();
        DB::table('role_has_permissions')->where('role_id', $id)->delete();

        // 4. Role record delete karein directly DB level se
        DB::table('roles')->where('id', $id)->delete();

        // 5. Spatie Cache clear (Safe string passing)
        if (class_exists('\Spatie\Permission\PermissionRegistrar')) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ], 200);

    } catch (\Throwable $e) {
        Log::error("Role Delete Error: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete role',
            'error'   => $e->getMessage()
        ], 500);
    }
}
}