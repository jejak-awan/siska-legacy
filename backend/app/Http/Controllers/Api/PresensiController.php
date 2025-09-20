<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\JadwalPresensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Presensi::with(['user', 'validator']);

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date
            if ($request->has('tanggal')) {
                $query->where('tanggal', $request->tanggal);
            }

            // Search by user name
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $presensi = $query->orderBy('tanggal', 'desc')
                            ->orderBy('jam_masuk', 'desc')
                            ->paginate($perPage);

            return response()->json([
                'message' => 'Presensi data retrieved successfully',
                'data' => $presensi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve presensi data',
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
                'tanggal' => 'required|date',
                'jam_masuk' => 'nullable|date_format:H:i:s',
                'jam_keluar' => 'nullable|date_format:H:i:s',
                'status' => 'required|in:hadir,terlambat,sakit,izin,alpha',
                'lokasi_lat' => 'nullable|numeric|between:-90,90',
                'lokasi_lng' => 'nullable|numeric|between:-180,180',
                'qr_code' => 'nullable|string|max:255',
                'foto_absen' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
                'divalidasi_oleh' => 'nullable|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if presensi already exists for this user and date
            $existingPresensi = Presensi::where('user_id', $request->user_id)
                                      ->where('tanggal', $request->tanggal)
                                      ->first();

            if ($existingPresensi) {
                return response()->json([
                    'message' => 'Presensi already exists for this user and date',
                    'data' => $existingPresensi
                ], 409);
            }

            $presensi = Presensi::create($request->all());

            return response()->json([
                'message' => 'Presensi created successfully',
                'data' => $presensi->load(['user', 'validator'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create presensi',
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
            $presensi = Presensi::with(['user', 'validator'])->findOrFail($id);

            return response()->json([
                'message' => 'Presensi data retrieved successfully',
                'data' => $presensi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Presensi not found',
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
            $presensi = Presensi::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'jam_masuk' => 'nullable|date_format:H:i:s',
                'jam_keluar' => 'nullable|date_format:H:i:s',
                'status' => 'sometimes|in:hadir,terlambat,sakit,izin,alpha',
                'lokasi_lat' => 'nullable|numeric|between:-90,90',
                'lokasi_lng' => 'nullable|numeric|between:-180,180',
                'qr_code' => 'nullable|string|max:255',
                'foto_absen' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
                'divalidasi_oleh' => 'nullable|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $presensi->update($request->all());

            return response()->json([
                'message' => 'Presensi updated successfully',
                'data' => $presensi->load(['user', 'validator'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update presensi',
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
            $presensi = Presensi::findOrFail($id);
            $presensi->delete();

            return response()->json([
                'message' => 'Presensi deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete presensi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get presensi statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $query = Presensi::query();

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            $stats = [
                'total_presensi' => $query->count(),
                'hadir' => $query->clone()->where('status', 'hadir')->count(),
                'terlambat' => $query->clone()->where('status', 'terlambat')->count(),
                'sakit' => $query->clone()->where('status', 'sakit')->count(),
                'izin' => $query->clone()->where('status', 'izin')->count(),
                'alpha' => $query->clone()->where('status', 'alpha')->count(),
            ];

            // Calculate percentages
            $total = $stats['total_presensi'];
            if ($total > 0) {
                $stats['persentase_hadir'] = round(($stats['hadir'] / $total) * 100, 2);
                $stats['persentase_terlambat'] = round(($stats['terlambat'] / $total) * 100, 2);
                $stats['persentase_alpha'] = round(($stats['alpha'] / $total) * 100, 2);
            } else {
                $stats['persentase_hadir'] = 0;
                $stats['persentase_terlambat'] = 0;
                $stats['persentase_alpha'] = 0;
            }

            return response()->json([
                'message' => 'Presensi statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve presensi statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get presensi by user
     */
    public function byUser(Request $request, string $userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);
            
            $query = Presensi::where('user_id', $userId)
                            ->with(['validator']);

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            $perPage = $request->get('per_page', 15);
            $presensi = $query->orderBy('tanggal', 'desc')
                            ->paginate($perPage);

            return response()->json([
                'message' => 'User presensi data retrieved successfully',
                'data' => [
                    'user' => $user,
                    'presensi' => $presensi
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve user presensi data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get presensi by date
     */
    public function byDate(Request $request, string $date): JsonResponse
    {
        try {
            $query = Presensi::where('tanggal', $date)
                            ->with(['user', 'validator']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $perPage = $request->get('per_page', 15);
            $presensi = $query->orderBy('jam_masuk', 'asc')
                            ->paginate($perPage);

            return response()->json([
                'message' => 'Date presensi data retrieved successfully',
                'data' => $presensi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve date presensi data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
