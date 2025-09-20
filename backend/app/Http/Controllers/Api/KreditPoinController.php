<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KreditPoin;
use App\Models\KategoriKreditPoin;
use App\Models\Siswa;
use App\Services\KreditPoinService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KreditPoinController extends Controller
{
    protected $kreditPoinService;

    public function __construct(KreditPoinService $kreditPoinService)
    {
        $this->kreditPoinService = $kreditPoinService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = KreditPoin::with(['siswa.user', 'kategori', 'pelapor']);

            // Filter by siswa
            if ($request->has('siswa_id')) {
                $query->where('siswa_id', $request->siswa_id);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by kategori
            if ($request->has('kategori_id')) {
                $query->where('kategori_id', $request->kategori_id);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            // Filter by jenis (positif/negatif)
            if ($request->has('jenis')) {
                $query->whereHas('kategori', function($q) use ($request) {
                    $q->where('jenis', $request->jenis);
                });
            }

            // Search by siswa name
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $kreditPoin = $query->orderBy('tanggal', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate($perPage);

            return response()->json([
                'message' => 'Kredit poin data retrieved successfully',
                'data' => $kreditPoin
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve kredit poin data',
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
                'siswa_id' => 'required|exists:siswa,id',
                'kategori_id' => 'required|exists:kategori_kredit_poin,id',
                'nilai' => 'integer',
                'deskripsi' => 'required|string|max:500',
                'tanggal' => 'date',
                'pelapor_id' => 'required|exists:users,id',
                'catatan_admin' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $kreditPoin = $this->kreditPoinService->createKreditPoin($request->all());

            return response()->json([
                'message' => 'Kredit poin created successfully',
                'data' => $kreditPoin->load(['siswa.user', 'kategori', 'pelapor'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create kredit poin',
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
            $kreditPoin = KreditPoin::with(['siswa.user', 'kategori', 'pelapor'])->findOrFail($id);

            return response()->json([
                'message' => 'Kredit poin data retrieved successfully',
                'data' => $kreditPoin
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kredit poin not found',
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
            $kreditPoin = KreditPoin::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nilai' => 'sometimes|integer',
                'deskripsi' => 'sometimes|string',
                'tanggal' => 'sometimes|date',
                'status' => 'sometimes|in:pending,approved,rejected',
                'catatan_admin' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $kreditPoin->update($request->all());

            return response()->json([
                'message' => 'Kredit poin updated successfully',
                'data' => $kreditPoin->load(['siswa.user', 'kategori', 'pelapor'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update kredit poin',
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
            $kreditPoin = KreditPoin::findOrFail($id);
            $kreditPoin->delete();

            return response()->json([
                'message' => 'Kredit poin deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete kredit poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve kredit poin
     */
    public function approve(Request $request, string $id): JsonResponse
    {
        try {
            $kreditPoin = KreditPoin::findOrFail($id);

            if ($kreditPoin->status !== 'pending') {
                return response()->json([
                    'message' => 'Only pending kredit poin can be approved'
                ], 400);
            }

            $approverId = auth()->id();
            $catatanAdmin = $request->catatan_admin;

            $this->kreditPoinService->approveKreditPoin($kreditPoin, $approverId, $catatanAdmin);

            return response()->json([
                'message' => 'Kredit poin approved successfully',
                'data' => $kreditPoin->fresh()->load(['siswa.user', 'kategori', 'pelapor'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to approve kredit poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject kredit poin
     */
    public function reject(Request $request, string $id): JsonResponse
    {
        try {
            $kreditPoin = KreditPoin::findOrFail($id);

            if ($kreditPoin->status !== 'pending') {
                return response()->json([
                    'message' => 'Only pending kredit poin can be rejected'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'catatan_admin' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $rejectorId = auth()->id();
            $catatanAdmin = $request->catatan_admin;

            $this->kreditPoinService->rejectKreditPoin($kreditPoin, $rejectorId, $catatanAdmin);

            return response()->json([
                'message' => 'Kredit poin rejected successfully',
                'data' => $kreditPoin->fresh()->load(['siswa.user', 'kategori', 'pelapor'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to reject kredit poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kredit poin statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $stats = $this->kreditPoinService->getStatistics();

            return response()->json([
                'message' => 'Kredit poin statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve kredit poin statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kredit poin by siswa
     */
    public function bySiswa(Request $request, string $siswaId): JsonResponse
    {
        try {
            $siswa = Siswa::with('user')->findOrFail($siswaId);
            
            $query = KreditPoin::where('siswa_id', $siswaId)
                              ->with(['kategori', 'pelapor']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            $perPage = $request->get('per_page', 15);
            $kreditPoin = $query->orderBy('tanggal', 'desc')
                              ->paginate($perPage);

            // Calculate total points
            $totalPositif = KreditPoin::where('siswa_id', $siswaId)
                                    ->whereHas('kategori', function($q) {
                                        $q->where('jenis', 'positif');
                                    })
                                    ->where('status', 'approved')
                                    ->sum('nilai');

            $totalNegatif = KreditPoin::where('siswa_id', $siswaId)
                                    ->whereHas('kategori', function($q) {
                                        $q->where('jenis', 'negatif');
                                    })
                                    ->where('status', 'approved')
                                    ->sum('nilai');

            return response()->json([
                'message' => 'Siswa kredit poin data retrieved successfully',
                'data' => [
                    'siswa' => $siswa,
                    'kredit_poin' => $kreditPoin,
                    'summary' => [
                        'total_poin_positif' => $totalPositif,
                        'total_poin_negatif' => $totalNegatif,
                        'total_poin_bersih' => $totalPositif - abs($totalNegatif)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve siswa kredit poin data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending kredit poin for approval
     */
    public function pending(Request $request): JsonResponse
    {
        try {
            \Log::info('Pending kredit poin request started');
            
            $query = KreditPoin::where('status', 'pending')
                              ->with(['siswa.user', 'kategori', 'pelapor']);

            \Log::info('Query built, count: ' . $query->count());

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            $perPage = $request->get('per_page', 15);
            $kreditPoin = $query->orderBy('created_at', 'asc')
                              ->paginate($perPage);

            \Log::info('Pagination completed, count: ' . $kreditPoin->count());

            return response()->json([
                'message' => 'Pending kredit poin data retrieved successfully',
                'data' => $kreditPoin
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in pending kredit poin: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Failed to retrieve pending kredit poin data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
