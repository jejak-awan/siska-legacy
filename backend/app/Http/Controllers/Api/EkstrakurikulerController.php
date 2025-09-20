<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerSiswa;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EkstrakurikulerController extends Controller
{
    /**
     * Display a listing of extracurricular activities.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Ekstrakurikuler::with(['pembina', 'ketua'])
                ->orderBy('nama_ekstrakurikuler', 'asc');

            // Apply filters
            if ($request->filled('jenis')) {
                $query->where('jenis', $request->jenis);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('is_aktif')) {
                $query->where('is_aktif', $request->boolean('is_aktif'));
            }

            if ($request->filled('pembina_id')) {
                $query->where('pembina_id', $request->pembina_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_ekstrakurikuler', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            $ekstrakurikuler = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $ekstrakurikuler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving extracurricular activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created extracurricular activity.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_ekstrakurikuler' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|in:olahraga,seni,akademik,keagamaan,keterampilan,sosial',
            'status' => 'required|in:aktif,tidak_aktif,ditutup',
            'pembina_id' => 'nullable|exists:users,id',
            'ketua_id' => 'nullable|exists:users,id',
            'jadwal_latihan' => 'required|string',
            'tempat_latihan' => 'required|string',
            'persyaratan_anggota' => 'nullable|string',
            'kuota_anggota' => 'nullable|integer|min:0',
            'biaya_pendaftaran' => 'nullable|numeric|min:0',
            'fasilitas' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'foto_ekstrakurikuler' => 'nullable|string',
            'is_aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ekstrakurikuler = Ekstrakurikuler::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Extracurricular activity created successfully',
                'data' => $ekstrakurikuler->load(['pembina', 'ketua'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating extracurricular activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified extracurricular activity.
     */
    public function show(Ekstrakurikuler $ekstrakurikuler): JsonResponse
    {
        try {
            $ekstrakurikuler->load(['pembina', 'ketua', 'siswa']);

            return response()->json([
                'success' => true,
                'data' => $ekstrakurikuler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving extracurricular activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified extracurricular activity.
     */
    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_ekstrakurikuler' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string',
            'jenis' => 'sometimes|in:olahraga,seni,akademik,keagamaan,keterampilan,sosial',
            'status' => 'sometimes|in:aktif,tidak_aktif,ditutup',
            'pembina_id' => 'nullable|exists:users,id',
            'ketua_id' => 'nullable|exists:users,id',
            'jadwal_latihan' => 'sometimes|string',
            'tempat_latihan' => 'sometimes|string',
            'persyaratan_anggota' => 'nullable|string',
            'kuota_anggota' => 'nullable|integer|min:0',
            'biaya_pendaftaran' => 'nullable|numeric|min:0',
            'fasilitas' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'foto_ekstrakurikuler' => 'nullable|string',
            'is_aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ekstrakurikuler->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Extracurricular activity updated successfully',
                'data' => $ekstrakurikuler->load(['pembina', 'ketua'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating extracurricular activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified extracurricular activity.
     */
    public function destroy(Ekstrakurikuler $ekstrakurikuler): JsonResponse
    {
        try {
            $ekstrakurikuler->delete();

            return response()->json([
                'success' => true,
                'message' => 'Extracurricular activity deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting extracurricular activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get extracurricular activity statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_ekstrakurikuler' => Ekstrakurikuler::count(),
                'ekstrakurikuler_aktif' => Ekstrakurikuler::where('is_aktif', true)->count(),
                'ekstrakurikuler_olahraga' => Ekstrakurikuler::where('jenis', 'olahraga')->count(),
                'ekstrakurikuler_seni' => Ekstrakurikuler::where('jenis', 'seni')->count(),
                'ekstrakurikuler_akademik' => Ekstrakurikuler::where('jenis', 'akademik')->count(),
                'ekstrakurikuler_keagamaan' => Ekstrakurikuler::where('jenis', 'keagamaan')->count(),
                'ekstrakurikuler_keterampilan' => Ekstrakurikuler::where('jenis', 'keterampilan')->count(),
                'ekstrakurikuler_sosial' => Ekstrakurikuler::where('jenis', 'sosial')->count(),
                'total_anggota' => EkstrakurikulerSiswa::where('status', 'aktif')->count(),
                'pendaftaran_bulan_ini' => EkstrakurikulerSiswa::whereMonth('tanggal_daftar', now()->month)->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving extracurricular statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register student to extracurricular activity.
     */
    public function registerStudent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikuler,id',
            'siswa_id' => 'required|exists:siswa,id',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if student is already registered
            $existing = EkstrakurikulerSiswa::where('ekstrakurikuler_id', $request->ekstrakurikuler_id)
                ->where('siswa_id', $request->siswa_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already registered to this extracurricular activity'
                ], 400);
            }

            // Check quota
            $ekstrakurikuler = Ekstrakurikuler::find($request->ekstrakurikuler_id);
            if ($ekstrakurikuler->kuota_anggota && $ekstrakurikuler->jumlah_anggota >= $ekstrakurikuler->kuota_anggota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Extracurricular activity is full'
                ], 400);
            }

            $registration = EkstrakurikulerSiswa::create([
                'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
                'siswa_id' => $request->siswa_id,
                'tanggal_daftar' => now()->toDateString(),
                'status' => 'aktif',
                'catatan' => $request->catatan,
                'is_aktif' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully',
                'data' => $registration->load(['ekstrakurikuler', 'siswa'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error registering student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unregister student from extracurricular activity.
     */
    public function unregisterStudent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikuler,id',
            'siswa_id' => 'required|exists:siswa,id',
            'alasan_keluar' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $registration = EkstrakurikulerSiswa::where('ekstrakurikuler_id', $request->ekstrakurikuler_id)
                ->where('siswa_id', $request->siswa_id)
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is not registered to this extracurricular activity'
                ], 404);
            }

            $registration->update([
                'status' => 'keluar',
                'alasan_keluar' => $request->alasan_keluar,
                'is_aktif' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student unregistered successfully',
                'data' => $registration
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error unregistering student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students in extracurricular activity.
     */
    public function getStudents(Ekstrakurikuler $ekstrakurikuler): JsonResponse
    {
        try {
            $students = $ekstrakurikuler->siswa()
                ->wherePivot('status', 'aktif')
                ->withPivot(['tanggal_daftar', 'status', 'catatan'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available extracurricular activities for student.
     */
    public function getAvailableForStudent(Siswa $siswa): JsonResponse
    {
        try {
            $available = Ekstrakurikuler::where('status', 'aktif')
                ->where('is_aktif', true)
                ->whereDoesntHave('siswa', function ($query) use ($siswa) {
                    $query->where('siswa_id', $siswa->id)
                          ->where('status', 'aktif');
                })
                ->get();

            return response()->json([
                'success' => true,
                'data' => $available
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}