<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OSISKegiatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class OSISController extends Controller
{
    /**
     * Display a listing of OSIS activities.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = OSISKegiatan::orderBy('tanggal_mulai', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('jenis_kegiatan')) {
                $query->where('jenis_kegiatan', $request->jenis_kegiatan);
            }

            if ($request->filled('is_urgent')) {
                $query->where('is_urgent', $request->boolean('is_urgent'));
            }

            if ($request->filled('is_aktif')) {
                $query->where('is_aktif', $request->boolean('is_aktif'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_mulai', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kegiatan', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('penanggung_jawab', 'like', "%{$search}%");
                });
            }

            $kegiatan = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $kegiatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving OSIS activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created OSIS activity.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jenis_kegiatan' => 'required|in:akademik,non_akademik,sosial,olahraga,seni,keagamaan',
            'status' => 'required|in:perencanaan,persiapan,berlangsung,selesai,dibatalkan',
            'tempat_kegiatan' => 'required|string',
            'tujuan_kegiatan' => 'required|string',
            'sasaran_kegiatan' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string',
            'penanggung_jawab' => 'required|string',
            'peserta' => 'nullable|array',
            'panitia' => 'nullable|array',
            'keterangan' => 'nullable|string',
            'foto_kegiatan' => 'nullable|string',
            'is_aktif' => 'boolean',
            'is_urgent' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kegiatan = OSISKegiatan::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'OSIS activity created successfully',
                'data' => $kegiatan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating OSIS activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified OSIS activity.
     */
    public function show(OSISKegiatan $osisKegiatan): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $osisKegiatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving OSIS activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified OSIS activity.
     */
    public function update(Request $request, OSISKegiatan $osisKegiatan): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'sometimes|date_format:H:i',
            'jam_selesai' => 'sometimes|date_format:H:i|after:jam_mulai',
            'jenis_kegiatan' => 'sometimes|in:akademik,non_akademik,sosial,olahraga,seni,keagamaan',
            'status' => 'sometimes|in:perencanaan,persiapan,berlangsung,selesai,dibatalkan',
            'tempat_kegiatan' => 'sometimes|string',
            'tujuan_kegiatan' => 'sometimes|string',
            'sasaran_kegiatan' => 'sometimes|string',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string',
            'penanggung_jawab' => 'sometimes|string',
            'peserta' => 'nullable|array',
            'panitia' => 'nullable|array',
            'keterangan' => 'nullable|string',
            'foto_kegiatan' => 'nullable|string',
            'is_aktif' => 'boolean',
            'is_urgent' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $osisKegiatan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'OSIS activity updated successfully',
                'data' => $osisKegiatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating OSIS activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified OSIS activity.
     */
    public function destroy(OSISKegiatan $osisKegiatan): JsonResponse
    {
        try {
            $osisKegiatan->delete();

            return response()->json([
                'success' => true,
                'message' => 'OSIS activity deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting OSIS activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get OSIS activity statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_kegiatan' => OSISKegiatan::count(),
                'kegiatan_hari_ini' => OSISKegiatan::whereDate('tanggal_mulai', today())->count(),
                'kegiatan_minggu_ini' => OSISKegiatan::whereBetween('tanggal_mulai', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'kegiatan_bulan_ini' => OSISKegiatan::whereMonth('tanggal_mulai', now()->month)->count(),
                'kegiatan_urgent' => OSISKegiatan::where('is_urgent', true)->count(),
                'kegiatan_aktif' => OSISKegiatan::where('is_aktif', true)->count(),
                'kegiatan_selesai' => OSISKegiatan::where('status', 'selesai')->count(),
                'kegiatan_berlangsung' => OSISKegiatan::where('status', 'berlangsung')->count(),
                'kegiatan_terjadwal' => OSISKegiatan::where('status', 'terjadwal')->count(),
                'kegiatan_akademik' => OSISKegiatan::where('jenis_kegiatan', 'akademik')->count(),
                'kegiatan_non_akademik' => OSISKegiatan::where('jenis_kegiatan', 'non_akademik')->count(),
                'kegiatan_sosial' => OSISKegiatan::where('jenis_kegiatan', 'sosial')->count(),
                'kegiatan_olahraga' => OSISKegiatan::where('jenis_kegiatan', 'olahraga')->count(),
                'kegiatan_seni' => OSISKegiatan::where('jenis_kegiatan', 'seni')->count(),
                'kegiatan_keagamaan' => OSISKegiatan::where('jenis_kegiatan', 'keagamaan')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving OSIS statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming activities.
     */
    public function upcoming(): JsonResponse
    {
        try {
            $upcoming = OSISKegiatan::where('tanggal_mulai', '>=', now())
                ->where('is_aktif', true)
                ->orderBy('tanggal_mulai', 'asc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $upcoming
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving upcoming activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ongoing activities.
     */
    public function ongoing(): JsonResponse
    {
        try {
            $ongoing = OSISKegiatan::where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->where('is_aktif', true)
                ->orderBy('tanggal_mulai', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $ongoing
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving ongoing activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}