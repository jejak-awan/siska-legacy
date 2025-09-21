<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class HakAksesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $roles = Role::with(['permissions'])->get();
            $permissions = Permission::active()->orderBy('module')->orderBy('sort_order')->get();
            $modules = $permissions->groupBy('module');

            return response()->json([
                'message' => 'Hak akses data retrieved successfully',
                'data' => [
                    'roles' => $roles,
                    'permissions' => $permissions,
                    'modules' => $modules,
                    'actions' => ['view', 'create', 'edit', 'delete']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve hak akses data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRoles(): JsonResponse
    {
        try {
            $roles = Role::with(['permissions'])->get();

            return response()->json([
                'message' => 'Roles retrieved successfully',
                'data' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPermissions(): JsonResponse
    {
        try {
            $permissions = Permission::active()->orderBy('module')->orderBy('sort_order')->get();
            $modules = $permissions->groupBy('module');

            return response()->json([
                'message' => 'Permissions retrieved successfully',
                'data' => [
                    'permissions' => $permissions,
                    'modules' => $modules,
                    'actions' => ['view', 'create', 'edit', 'delete']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePermissions(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'permissions' => 'required|array',
                'permissions.*.permission_id' => 'required|exists:permissions,id',
                'permissions.*.granted' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::findOrFail($request->role_id);
            $permissions = $request->permissions;

            // Update permissions for the role
            foreach ($permissions as $permissionData) {
                $role->permissions()->syncWithoutDetaching([
                    $permissionData['permission_id'] => ['granted' => $permissionData['granted']]
                ]);
            }

            $role->load(['permissions']);

            return response()->json([
                'message' => 'Permissions updated successfully',
                'data' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRolePermissions($roleId): JsonResponse
    {
        try {
            $role = Role::with(['permissions'])->findOrFail($roleId);
            $permissions = Permission::active()->orderBy('module')->orderBy('sort_order')->get();

            // Create permission matrix
            $permissionMatrix = [];
            foreach ($permissions as $permission) {
                $permissionMatrix[$permission->module][$permission->action] = [
                    'permission_id' => $permission->id,
                    'granted' => $role->permissions()
                        ->where('permission_id', $permission->id)
                        ->wherePivot('granted', true)
                        ->exists()
                ];
            }

            return response()->json([
                'message' => 'Role permissions retrieved successfully',
                'data' => [
                    'role' => $role,
                    'permission_matrix' => $permissionMatrix,
                    'modules' => $permissions->groupBy('module'),
                    'actions' => ['view', 'create', 'edit', 'delete']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve role permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function grantPermission(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'permission_id' => 'required|exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::findOrFail($request->role_id);
            $role->grantPermission($request->permission_id);

            return response()->json([
                'message' => 'Permission granted successfully',
                'data' => $role->load(['permissions'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to grant permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function revokePermission(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'permission_id' => 'required|exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::findOrFail($request->role_id);
            $role->revokePermission($request->permission_id);

            return response()->json([
                'message' => 'Permission revoked successfully',
                'data' => $role->load(['permissions'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to revoke permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function statistics(): JsonResponse
    {
        try {
            $roleStats = [
                'total_roles' => Role::count(),
                'roles_with_permissions' => Role::whereHas('permissions')->count(),
            ];

            $permissionStats = Permission::getStatistics();

            return response()->json([
                'message' => 'Hak akses statistics retrieved successfully',
                'data' => [
                    'roles' => $roleStats,
                    'permissions' => $permissionStats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve hak akses statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkPermission(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'permission_name' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::findOrFail($request->role_id);
            $hasPermission = $role->hasPermission($request->permission_name);

            return response()->json([
                'message' => 'Permission check completed',
                'data' => [
                    'role_id' => $request->role_id,
                    'permission_name' => $request->permission_name,
                    'has_permission' => $hasPermission
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to check permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}