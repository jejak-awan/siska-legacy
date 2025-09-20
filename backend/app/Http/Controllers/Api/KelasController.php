<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $kelas = Kelas::orderBy('nama')->get();
            
            return response()->json([
                'message' => 'Kelas retrieved successfully',
                'data' => $kelas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'wali_kelas_id' => 'nullable|exists:guru,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'deskripsi' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kelas = Kelas::create($request->all());
            
            return response()->json([
                'message' => 'Kelas created successfully',
                'data' => $kelas
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create kelas',
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
            $kelas = Kelas::with(['waliKelas', 'siswa', 'tahunAjaran'])->findOrFail($id);
            
            return response()->json([
                'message' => 'Kelas retrieved successfully',
                'data' => $kelas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kelas not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'tingkat' => 'sometimes|required|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'kapasitas' => 'sometimes|required|integer|min:1',
            'wali_kelas_id' => 'nullable|exists:guru,id',
            'tahun_ajaran_id' => 'sometimes|required|exists:tahun_ajaran,id',
            'deskripsi' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->update($request->all());
            
            return response()->json([
                'message' => 'Kelas updated successfully',
                'data' => $kelas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update kelas',
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
            $kelas = Kelas::findOrFail($id);
            
            // Check if kelas has students
            if ($kelas->siswa()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete kelas that has students',
                    'error' => 'Kelas memiliki siswa'
                ], 422);
            }
            
            $kelas->delete();
            
            return response()->json([
                'message' => 'Kelas deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for kelas
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => Kelas::count(),
                'by_tingkat' => Kelas::selectRaw('tingkat, count(*) as count')
                    ->groupBy('tingkat')
                    ->get()
                    ->pluck('count', 'tingkat'),
                'by_jurusan' => Kelas::selectRaw('jurusan, count(*) as count')
                    ->whereNotNull('jurusan')
                    ->groupBy('jurusan')
                    ->get()
                    ->pluck('count', 'jurusan'),
                'total_siswa' => Kelas::withCount('siswa')->get()->sum('siswa_count'),
                'average_siswa_per_kelas' => Kelas::withCount('siswa')->get()->avg('siswa_count')
            ];
            
            return response()->json([
                'message' => 'Kelas statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
