<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $query = Notifikasi::where('user_id', $user->id);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by tipe
            if ($request->has('tipe')) {
                $query->where('tipe', $request->tipe);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            // Search by title or message
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('pesan', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $notifikasi = $query->orderBy('created_at', 'desc')
                              ->paginate($perPage);

            return response()->json([
                'message' => 'Notifications retrieved successfully',
                'data' => $notifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'judul' => 'required|string|max:255',
                'pesan' => 'required|string',
                'tipe' => 'required|in:info,warning,error,success',
                'status' => 'sometimes|in:unread,read,archived',
                'data' => 'nullable|array',
                'action_url' => 'nullable|string|max:255',
                'action_text' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notifikasi = Notifikasi::create($request->all());

            return response()->json([
                'message' => 'Notification created successfully',
                'data' => $notifikasi
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifikasi = Notifikasi::where('user_id', $user->id)->findOrFail($id);

            // Mark as read if it's unread
            if ($notifikasi->status === 'unread') {
                $notifikasi->markAsRead();
            }

            return response()->json([
                'message' => 'Notification retrieved successfully',
                'data' => $notifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Notification not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifikasi = Notifikasi::where('user_id', $user->id)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|in:unread,read,archived'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notifikasi->update($request->all());

            return response()->json([
                'message' => 'Notification updated successfully',
                'data' => $notifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifikasi = Notifikasi::where('user_id', $user->id)->findOrFail($id);
            $notifikasi->delete();

            return response()->json([
                'message' => 'Notification deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifikasi = Notifikasi::where('user_id', $user->id)->findOrFail($id);
            $notifikasi->markAsRead();

            return response()->json([
                'message' => 'Notification marked as read',
                'data' => $notifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as archived
     */
    public function markAsArchived(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifikasi = Notifikasi::where('user_id', $user->id)->findOrFail($id);
            $notifikasi->markAsArchived();

            return response()->json([
                'message' => 'Notification marked as archived',
                'data' => $notifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to mark notification as archived',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            $user = Auth::user();
            $updated = Notifikasi::where('user_id', $user->id)
                               ->where('status', 'unread')
                               ->update([
                                   'status' => 'read',
                                   'read_at' => now()
                               ]);

            return response()->json([
                'message' => "{$updated} notifications marked as read"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $stats = [
                'total' => Notifikasi::where('user_id', $user->id)->count(),
                'unread' => Notifikasi::where('user_id', $user->id)->where('status', 'unread')->count(),
                'read' => Notifikasi::where('user_id', $user->id)->where('status', 'read')->count(),
                'archived' => Notifikasi::where('user_id', $user->id)->where('status', 'archived')->count(),
                'by_type' => [
                    'info' => Notifikasi::where('user_id', $user->id)->where('tipe', 'info')->count(),
                    'warning' => Notifikasi::where('user_id', $user->id)->where('tipe', 'warning')->count(),
                    'error' => Notifikasi::where('user_id', $user->id)->where('tipe', 'error')->count(),
                    'success' => Notifikasi::where('user_id', $user->id)->where('tipe', 'success')->count(),
                ]
            ];

            return response()->json([
                'message' => 'Notification statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve notification statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications
     */
    public function unread(): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifikasi = Notifikasi::where('user_id', $user->id)
                                  ->where('status', 'unread')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

            return response()->json([
                'message' => 'Unread notifications retrieved successfully',
                'data' => $notifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve unread notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'judul' => 'required|string|max:255',
                'pesan' => 'required|string',
                'tipe' => 'required|in:info,warning,error,success',
                'data' => 'nullable|array',
                'action_url' => 'nullable|string|max:255',
                'action_text' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notifications = [];
            foreach ($request->user_ids as $userId) {
                $notifications[] = Notifikasi::create([
                    'user_id' => $userId,
                    'judul' => $request->judul,
                    'pesan' => $request->pesan,
                    'tipe' => $request->tipe,
                    'data' => $request->data,
                    'action_url' => $request->action_url,
                    'action_text' => $request->action_text,
                    'status' => 'unread'
                ]);
            }

            return response()->json([
                'message' => 'Notifications sent successfully',
                'data' => $notifications
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
