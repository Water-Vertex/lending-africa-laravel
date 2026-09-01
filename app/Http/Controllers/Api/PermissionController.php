<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User; // ✅ YEH ADD KARO

class PermissionController extends Controller
{
    /**
     * Get all permissions (only names for frontend)
     */
    public function index()
    {
        try {
            $permissions = Permission::orderBy('name')->get();
            $permissionNames = $permissions->pluck('name')->toArray();

            return response()->json([
                'success' => true,
                'data' => $permissionNames,
                'message' => 'Permissions retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all roles with their permissions
     */
    public function getRolesWithPermissions()
    {
        try {
            $roles = Role::with('permissions')->orderBy('name')->get();
            
            $data = $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                    'guard_name' => $role->guard_name,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Roles with permissions retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch roles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get permissions for a specific role
     */
    public function getRolePermissions($roleId)
    {
        try {
            $role = Role::with('permissions')->findOrFail($roleId);
            
            return response()->json([
                'success' => true,
                'data' => $role->permissions->pluck('name')->toArray(),
                'message' => 'Role permissions retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get role permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync permissions for a role (Assign/Remove permissions)
     */
    public function syncRolePermissions(Request $request, $roleId)
    {
        try {
            $request->validate([
                'permissions' => 'required|array',
            ]);

            $role = Role::findOrFail($roleId);
            $role->syncPermissions($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully for role: ' . $role->name,
                'data' => [
                    'role' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->toArray()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ SIRF YEH METHOD FIX KARO - Get all users with their roles
     */
    public function getUsersWithRoles()
    {
        try {
            // ✅ User::with('roles') - Spatie ka roles relationship use karega
            $users = User::with('roles')->get();
            
            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'roles' => $user->roles->pluck('name')->toArray(),
                    'role' => $user->roles->first() ? $user->roles->first()->name : null,
                    'branch' => $user->branch,
                    'phone' => $user->phone,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Users with roles retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign role to user
     */
    public function assignRoleToUser(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'role' => 'required|string|exists:roles,name',
            ]);

            $user = User::findOrFail($request->user_id);
            $user->syncRoles([$request->role]);

            return response()->json([
                'success' => true,
                'message' => 'Role assigned to user successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role: ' . $e->getMessage()
            ], 500);
        }
    }


    
}
// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;

// class PermissionController extends Controller
// {
//     /**
//      * Get all permissions (only names for frontend)
//      */
//     public function index()
//     {
//         try {
//             $permissions = Permission::orderBy('name')->get();
            
//             // Return only permission names for frontend
//             $permissionNames = $permissions->pluck('name')->toArray();

//             return response()->json([
//                 'success' => true,
//                 'data' => $permissionNames,
//                 'message' => 'Permissions retrieved successfully'
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to fetch permissions: ' . $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Get all roles with their permissions
//      */
//     public function getRolesWithPermissions()
//     {
//         try {
//             $roles = Role::with('permissions')->orderBy('name')->get();
            
//             $data = $roles->map(function ($role) {
//                 return [
//                     'id' => $role->id,
//                     'name' => $role->name,
//                     'permissions' => $role->permissions->pluck('name')->toArray(),
//                     'guard_name' => $role->guard_name,
//                     'created_at' => $role->created_at,
//                     'updated_at' => $role->updated_at,
//                 ];
//             });

//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Roles with permissions retrieved successfully'
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to fetch roles: ' . $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Get permissions for a specific role
//      */
//     public function getRolePermissions($roleId)
//     {
//         try {
//             $role = Role::with('permissions')->findOrFail($roleId);
            
//             return response()->json([
//                 'success' => true,
//                 'data' => $role->permissions->pluck('name')->toArray(),
//                 'message' => 'Role permissions retrieved successfully'
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to get role permissions: ' . $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Sync permissions for a role (Assign/Remove permissions)
//      */
//     public function syncRolePermissions(Request $request, $roleId)
//     {
//         try {
//             $request->validate([
//                 'permissions' => 'required|array',
//             ]);

//             $role = Role::findOrFail($roleId);
            
//             // Sync permissions - this will add/remove as needed
//             $role->syncPermissions($request->permissions);

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Permissions updated successfully for role: ' . $role->name,
//                 'data' => [
//                     'role' => $role->name,
//                     'permissions' => $role->permissions->pluck('name')->toArray()
//                 ]
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to sync permissions: ' . $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Get all users with their roles
//      */
//     public function getUsersWithRoles()
//     {
//         try {
//             $users = \App\Models\User::with('roles')->get();
            
//             $data = $users->map(function ($user) {
//                 return [
//                     'id' => $user->id,
//                     'name' => $user->name,
//                     'email' => $user->email,
//                     'roles' => $user->roles->pluck('name')->toArray(),
//                     'created_at' => $user->created_at,
//                     'updated_at' => $user->updated_at,
//                 ];
//             });

//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Users with roles retrieved successfully'
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to fetch users: ' . $e->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Assign role to user
//      */
//     public function assignRoleToUser(Request $request)
//     {
//         try {
//             $request->validate([
//                 'user_id' => 'required|exists:users,id',
//                 'role' => 'required|string|exists:roles,name',
//             ]);

//             $user = \App\Models\User::findOrFail($request->user_id);
//             $user->syncRoles([$request->role]);

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Role assigned to user successfully'
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to assign role: ' . $e->getMessage()
//             ], 500);
//         }
//     }
// }