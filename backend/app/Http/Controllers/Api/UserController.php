<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role type
        if ($request->has('role_type')) {
            $query->where('role_type', $request->role_type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by username or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'role_type' => 'required|in:admin,guru,siswa,tendik,bk,wali_kelas,osis,ekstrakurikuler,orang_tua',
            'status' => 'sometimes|in:aktif,nonaktif,suspended',
            'two_factor_enabled' => 'sometimes|boolean',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_type' => $request->role_type,
            'status' => $request->get('status', 'aktif'),
            'two_factor_enabled' => $request->get('two_factor_enabled', false),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'guru', 'siswa']);

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['sometimes', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|string|min:8',
            'role_type' => 'sometimes|in:admin,guru,siswa,tendik,bk,wali_kelas,osis,ekstrakurikuler,orang_tua',
            'status' => 'sometimes|in:aktif,nonaktif,suspended',
            'two_factor_enabled' => 'sometimes|boolean',
        ]);

        $updateData = $request->only([
            'username', 'email', 'role_type', 'status', 'two_factor_enabled'
        ]);

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->role_type === 'admin') {
            return response()->json([
                'message' => 'Cannot delete admin user'
            ], Response::HTTP_FORBIDDEN);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Activate user
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'aktif']);

        return response()->json([
            'message' => 'User activated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        $user->update(['status' => 'nonaktif']);

        return response()->json([
            'message' => 'User deactivated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Get users by role
     */
    public function byRole($role)
    {
        $users = User::where('role_type', $role)
                    ->active()
                    ->get();

        return response()->json([
            'message' => "Users with role {$role} retrieved successfully",
            'data' => $users
        ], Response::HTTP_OK);
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        $stats = [
            'totalUsers' => User::count(),
            'adminCount' => User::where('role_type', 'admin')->count(),
            'staffCount' => User::whereIn('role_type', ['guru', 'tendik', 'bk', 'wali_kelas'])->count(),
            'studentParentCount' => User::whereIn('role_type', ['siswa', 'orang_tua'])->count(),
            'activeUsers' => User::where('status', 'aktif')->count(),
            'inactiveUsers' => User::where('status', 'nonaktif')->count(),
            'suspendedUsers' => User::where('status', 'suspended')->count(),
            'recentUsers' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json([
            'message' => 'User statistics retrieved successfully',
            'data' => $stats
        ], Response::HTTP_OK);
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,suspend,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        $action = $request->action;
        
        // Prevent bulk operations on admin users
        $adminUsers = User::whereIn('id', $userIds)->where('role_type', 'admin')->count();
        if ($adminUsers > 0 && in_array($action, ['deactivate', 'suspend', 'delete'])) {
            return response()->json([
                'message' => 'Cannot perform this action on admin users'
            ], Response::HTTP_FORBIDDEN);
        }

        $affected = 0;
        
        switch ($action) {
            case 'activate':
                $affected = User::whereIn('id', $userIds)->update(['status' => 'aktif']);
                break;
            case 'deactivate':
                $affected = User::whereIn('id', $userIds)->update(['status' => 'nonaktif']);
                break;
            case 'suspend':
                $affected = User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                break;
            case 'delete':
                $affected = User::whereIn('id', $userIds)->delete();
                break;
        }

        return response()->json([
            'message' => "Bulk {$action} completed successfully",
            'data' => [
                'affected_count' => $affected,
                'action' => $action
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Import users from CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        // This is a placeholder for file import functionality
        // In a real implementation, you would use Laravel Excel or similar
        
        return response()->json([
            'message' => 'User import feature coming soon',
            'data' => [
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize(),
            ]
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Export users to CSV/Excel
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,xlsx',
            'role_type' => 'sometimes|in:admin,guru,siswa,tendik,bk,wali_kelas,osis,ekstrakurikuler,orang_tua',
            'status' => 'sometimes|in:aktif,nonaktif,suspended',
        ]);

        // This is a placeholder for file export functionality
        // In a real implementation, you would use Laravel Excel or similar
        
        return response()->json([
            'message' => 'User export feature coming soon',
            'data' => [
                'format' => $request->format,
                'filters' => $request->only(['role_type', 'status']),
            ]
        ], Response::HTTP_ACCEPTED);
    }
}