<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TahunAjaranController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');
            $tahun = $request->get('tahun');

            $query = TahunAjaran::query();

            // Apply filters
            if ($status === 'aktif') {
                $query->where('is_aktif', true);
            } elseif ($status === 'tidak-aktif') {
                $query->where('is_aktif', false);
            } elseif ($status === 'selesai') {
                $query->where('tanggal_selesai', '<', now()->toDateString());
            }

            if ($tahun) {
                $query->where('tahun_mulai', $tahun)
                      ->orWhere('tahun_selesai', $tahun);
            }

            // Order by tahun_mulai desc
            $tahunAjaran = $query->orderBy('tahun_mulai', 'desc')->paginate($perPage);

            return response()->json([
                'message' => 'Tahun ajaran retrieved successfully',
                'data' => $tahunAjaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tahun ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:20|unique:tahun_ajaran,nama',
                'tahun_mulai' => 'required|integer|min:2020|max:2030',
                'tahun_selesai' => 'required|integer|min:2020|max:2030|gt:tahun_mulai',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after:tanggal_mulai',
                'is_aktif' => 'boolean',
                'keterangan' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $tahunAjaran = TahunAjaran::create([
                'nama' => $request->nama,
                'tahun_mulai' => $request->tahun_mulai,
                'tahun_selesai' => $request->tahun_selesai,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_aktif' => $request->boolean('is_aktif', false),
                'keterangan' => $request->keterangan,
            ]);

            // If this is set as active, deactivate others
            if ($tahunAjaran->is_aktif) {
                $tahunAjaran->activate();
            }

            return response()->json([
                'message' => 'Tahun ajaran created successfully',
                'data' => $tahunAjaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create tahun ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(TahunAjaran $tahunAjaran): JsonResponse
    {
        try {
            $tahunAjaran->load(['kelas', 'siswa']);

            return response()->json([
                'message' => 'Tahun ajaran retrieved successfully',
                'data' => $tahunAjaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tahun ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, TahunAjaran $tahunAjaran): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:20|unique:tahun_ajaran,nama,' . $tahunAjaran->id,
                'tahun_mulai' => 'required|integer|min:2020|max:2030',
                'tahun_selesai' => 'required|integer|min:2020|max:2030|gt:tahun_mulai',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after:tanggal_mulai',
                'is_aktif' => 'boolean',
                'keterangan' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $tahunAjaran->update([
                'nama' => $request->nama,
                'tahun_mulai' => $request->tahun_mulai,
                'tahun_selesai' => $request->tahun_selesai,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_aktif' => $request->boolean('is_aktif', $tahunAjaran->is_aktif),
                'keterangan' => $request->keterangan,
            ]);

            // If this is set as active, deactivate others
            if ($tahunAjaran->is_aktif) {
                $tahunAjaran->activate();
            }

            return response()->json([
                'message' => 'Tahun ajaran updated successfully',
                'data' => $tahunAjaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update tahun ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(TahunAjaran $tahunAjaran): JsonResponse
    {
        try {
            // Check if tahun ajaran is being used
            $hasKelas = $tahunAjaran->kelas()->exists();
            $hasSiswa = $tahunAjaran->siswa()->exists();

            if ($hasKelas || $hasSiswa) {
                return response()->json([
                    'message' => 'Cannot delete tahun ajaran that is being used in kelas or siswa',
                    'details' => [
                        'has_kelas' => $hasKelas,
                        'has_siswa' => $hasSiswa
                    ]
                ], 422);
            }

            $tahunAjaran->delete();

            return response()->json([
                'message' => 'Tahun ajaran deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete tahun ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function activate(TahunAjaran $tahunAjaran): JsonResponse
    {
        try {
            $tahunAjaran->activate();

            return response()->json([
                'message' => 'Tahun ajaran activated successfully',
                'data' => $tahunAjaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to activate tahun ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function current(): JsonResponse
    {
        try {
            $current = TahunAjaran::current()->first();

            if (!$current) {
                return response()->json([
                    'message' => 'No current academic year found',
                    'data' => null
                ]);
            }

            return response()->json([
                'message' => 'Current academic year retrieved successfully',
                'data' => $current
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve current academic year',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function active(): JsonResponse
    {
        try {
            $active = TahunAjaran::active()->first();

            if (!$active) {
                return response()->json([
                    'message' => 'No active academic year found',
                    'data' => null
                ]);
            }

            return response()->json([
                'message' => 'Active academic year retrieved successfully',
                'data' => $active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve active academic year',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => TahunAjaran::count(),
                'active' => TahunAjaran::where('is_aktif', true)->count(),
                'inactive' => TahunAjaran::where('is_aktif', false)->count(),
                'current' => TahunAjaran::current()->count(),
                'completed' => TahunAjaran::where('tanggal_selesai', '<', now()->toDateString())->count(),
                'upcoming' => TahunAjaran::where('tanggal_mulai', '>', now()->toDateString())->count(),
                'by_year' => TahunAjaran::selectRaw('tahun_mulai, count(*) as count')
                    ->groupBy('tahun_mulai')
                    ->get()
                    ->pluck('count', 'tahun_mulai'),
            ];

            return response()->json([
                'message' => 'Tahun ajaran statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tahun ajaran statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function options(): JsonResponse
    {
        try {
            $options = TahunAjaran::select('id', 'nama', 'tahun_mulai', 'tahun_selesai', 'is_aktif')
                ->orderBy('tahun_mulai', 'desc')
                ->get()
                ->map(function ($tahunAjaran) {
                    return [
                        'value' => $tahunAjaran->id,
                        'label' => $tahunAjaran->nama,
                        'tahun_mulai' => $tahunAjaran->tahun_mulai,
                        'tahun_selesai' => $tahunAjaran->tahun_selesai,
                        'is_aktif' => $tahunAjaran->is_aktif,
                    ];
                });

            return response()->json([
                'message' => 'Tahun ajaran options retrieved successfully',
                'data' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tahun ajaran options',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}