<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konseling;
use App\Models\HomeVisit;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BKController extends Controller
{
    /**
     * Display a listing of konseling sessions.
     */
    public function indexKonseling(Request $request): JsonResponse
    {
        try {
            $query = Konseling::with(['siswa', 'konselor'])
                ->orderBy('tanggal_konseling', 'desc')
                ->orderBy('jam_mulai', 'desc');

            // Apply filters
            if ($request->filled('siswa_id')) {
                $query->where('siswa_id', $request->siswa_id);
            }

            if ($request->filled('konselor_id')) {
                $query->where('konselor_id', $request->konselor_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('jenis_konseling')) {
                $query->where('jenis_konseling', $request->jenis_konseling);
            }

            if ($request->filled('is_urgent')) {
                $query->where('is_urgent', $request->boolean('is_urgent'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_konseling', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }

            $konseling = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $konseling
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving konseling data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created konseling session.
     */
    public function storeKonseling(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswa,id',
            'konselor_id' => 'required|exists:users,id',
            'tanggal_konseling' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jenis_konseling' => 'required|in:individual,kelompok,keluarga,krisis',
            'status' => 'required|in:terjadwal,berlangsung,selesai,dibatalkan',
            'masalah' => 'required|string',
            'tujuan_konseling' => 'required|string',
            'intervensi' => 'nullable|string',
            'hasil_konseling' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan_konselor' => 'nullable|string',
            'tempat_konseling' => 'nullable|string',
            'is_urgent' => 'boolean',
            'is_confidential' => 'boolean',
            'data_tambahan' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $konseling = Konseling::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Konseling session created successfully',
                'data' => $konseling->load(['siswa', 'konselor'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating konseling session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified konseling session.
     */
    public function showKonseling(Konseling $konseling): JsonResponse
    {
        try {
            $konseling->load(['siswa', 'konselor']);

            return response()->json([
                'success' => true,
                'data' => $konseling
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving konseling session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified konseling session.
     */
    public function updateKonseling(Request $request, Konseling $konseling): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'sometimes|exists:siswa,id',
            'konselor_id' => 'sometimes|exists:users,id',
            'tanggal_konseling' => 'sometimes|date',
            'jam_mulai' => 'sometimes|date_format:H:i',
            'jam_selesai' => 'sometimes|date_format:H:i|after:jam_mulai',
            'jenis_konseling' => 'sometimes|in:individual,kelompok,keluarga,krisis',
            'status' => 'sometimes|in:terjadwal,berlangsung,selesai,dibatalkan',
            'masalah' => 'sometimes|string',
            'tujuan_konseling' => 'sometimes|string',
            'intervensi' => 'nullable|string',
            'hasil_konseling' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan_konselor' => 'nullable|string',
            'tempat_konseling' => 'nullable|string',
            'is_urgent' => 'boolean',
            'is_confidential' => 'boolean',
            'data_tambahan' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $konseling->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Konseling session updated successfully',
                'data' => $konseling->load(['siswa', 'konselor'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating konseling session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified konseling session.
     */
    public function destroyKonseling(Konseling $konseling): JsonResponse
    {
        try {
            $konseling->delete();

            return response()->json([
                'success' => true,
                'message' => 'Konseling session deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting konseling session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get konseling statistics.
     */
    public function statisticsKonseling(): JsonResponse
    {
        try {
            $stats = [
                'total_konseling' => Konseling::count(),
                'konseling_hari_ini' => Konseling::whereDate('tanggal_konseling', today())->count(),
                'konseling_minggu_ini' => Konseling::whereBetween('tanggal_konseling', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'konseling_bulan_ini' => Konseling::whereMonth('tanggal_konseling', now()->month)->count(),
                'konseling_urgent' => Konseling::where('is_urgent', true)->count(),
                'konseling_selesai' => Konseling::where('status', 'selesai')->count(),
                'konseling_berlangsung' => Konseling::where('status', 'berlangsung')->count(),
                'konseling_terjadwal' => Konseling::where('status', 'terjadwal')->count(),
                'konseling_individual' => Konseling::where('jenis_konseling', 'individual')->count(),
                'konseling_kelompok' => Konseling::where('jenis_konseling', 'kelompok')->count(),
                'konseling_keluarga' => Konseling::where('jenis_konseling', 'keluarga')->count(),
                'konseling_krisis' => Konseling::where('jenis_konseling', 'krisis')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving konseling statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of home visits.
     */
    public function indexHomeVisit(Request $request): JsonResponse
    {
        try {
            $query = HomeVisit::with(['siswa', 'konselor'])
                ->orderBy('tanggal_kunjungan', 'desc')
                ->orderBy('jam_mulai', 'desc');

            // Apply filters
            if ($request->filled('siswa_id')) {
                $query->where('siswa_id', $request->siswa_id);
            }

            if ($request->filled('konselor_id')) {
                $query->where('konselor_id', $request->konselor_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('is_urgent')) {
                $query->where('is_urgent', $request->boolean('is_urgent'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_kunjungan', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }

            $homeVisits = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $homeVisits
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving home visit data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created home visit.
     */
    public function storeHomeVisit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswa,id',
            'konselor_id' => 'required|exists:users,id',
            'tanggal_kunjungan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:terjadwal,berlangsung,selesai,dibatalkan',
            'tujuan_kunjungan' => 'required|string',
            'hasil_kunjungan' => 'nullable|string',
            'catatan_kunjungan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'alamat_kunjungan' => 'required|string',
            'koordinat_lat' => 'nullable|string',
            'koordinat_lng' => 'nullable|string',
            'foto_kunjungan' => 'nullable|string',
            'is_urgent' => 'boolean',
            'is_confidential' => 'boolean',
            'data_keluarga' => 'nullable|array',
            'data_lingkungan' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $homeVisit = HomeVisit::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Home visit created successfully',
                'data' => $homeVisit->load(['siswa', 'konselor'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating home visit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified home visit.
     */
    public function showHomeVisit(HomeVisit $homeVisit): JsonResponse
    {
        try {
            $homeVisit->load(['siswa', 'konselor']);

            return response()->json([
                'success' => true,
                'data' => $homeVisit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving home visit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified home visit.
     */
    public function updateHomeVisit(Request $request, HomeVisit $homeVisit): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'sometimes|exists:siswa,id',
            'konselor_id' => 'sometimes|exists:users,id',
            'tanggal_kunjungan' => 'sometimes|date',
            'jam_mulai' => 'sometimes|date_format:H:i',
            'jam_selesai' => 'sometimes|date_format:H:i|after:jam_mulai',
            'status' => 'sometimes|in:terjadwal,berlangsung,selesai,dibatalkan',
            'tujuan_kunjungan' => 'sometimes|string',
            'hasil_kunjungan' => 'nullable|string',
            'catatan_kunjungan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'alamat_kunjungan' => 'sometimes|string',
            'koordinat_lat' => 'nullable|string',
            'koordinat_lng' => 'nullable|string',
            'foto_kunjungan' => 'nullable|string',
            'is_urgent' => 'boolean',
            'is_confidential' => 'boolean',
            'data_keluarga' => 'nullable|array',
            'data_lingkungan' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $homeVisit->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Home visit updated successfully',
                'data' => $homeVisit->load(['siswa', 'konselor'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating home visit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified home visit.
     */
    public function destroyHomeVisit(HomeVisit $homeVisit): JsonResponse
    {
        try {
            $homeVisit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Home visit deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting home visit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get home visit statistics.
     */
    public function statisticsHomeVisit(): JsonResponse
    {
        try {
            $stats = [
                'total_home_visit' => HomeVisit::count(),
                'home_visit_hari_ini' => HomeVisit::whereDate('tanggal_kunjungan', today())->count(),
                'home_visit_minggu_ini' => HomeVisit::whereBetween('tanggal_kunjungan', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'home_visit_bulan_ini' => HomeVisit::whereMonth('tanggal_kunjungan', now()->month)->count(),
                'home_visit_urgent' => HomeVisit::where('is_urgent', true)->count(),
                'home_visit_selesai' => HomeVisit::where('status', 'selesai')->count(),
                'home_visit_berlangsung' => HomeVisit::where('status', 'berlangsung')->count(),
                'home_visit_terjadwal' => HomeVisit::where('status', 'terjadwal')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving home visit statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get combined BK statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $konselingStats = [
                'total_konseling' => Konseling::count(),
                'konseling_hari_ini' => Konseling::whereDate('tanggal_konseling', today())->count(),
                'konseling_urgent' => Konseling::where('is_urgent', true)->count(),
                'konseling_selesai' => Konseling::where('status', 'selesai')->count()
            ];

            $homeVisitStats = [
                'total_home_visit' => HomeVisit::count(),
                'home_visit_hari_ini' => HomeVisit::whereDate('tanggal_kunjungan', today())->count(),
                'home_visit_urgent' => HomeVisit::where('is_urgent', true)->count(),
                'home_visit_selesai' => HomeVisit::where('status', 'selesai')->count()
            ];

            $combinedStats = array_merge($konselingStats, $homeVisitStats);

            return response()->json([
                'success' => true,
                'data' => $combinedStats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving BK statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}