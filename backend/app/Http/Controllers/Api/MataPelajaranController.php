<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $kelompok = $request->get('kelompok');
            $statusAktif = $request->get('status_aktif');

            $query = MataPelajaran::with(['guru']);

            if ($kelompok) {
                $query->byKelompok($kelompok);
            }

            if ($statusAktif !== null) {
                $query->where('status_aktif', $statusAktif);
            }

            $mataPelajaran = $query->orderBy('kelompok')->orderBy('nama')->paginate($perPage);

            return response()->json([
                'message' => 'Mata pelajaran retrieved successfully',
                'data' => $mataPelajaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode' => 'required|string|max:20|unique:mata_pelajaran,kode',
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'kelompok' => 'required|in:A,B,C',
                'jam_per_minggu' => 'required|integer|min:1|max:10',
                'kkm' => 'nullable|integer|min:0|max:100',
                'is_wajib' => 'boolean',
                'is_peminatan' => 'boolean',
                'guru_id' => 'nullable|string|max:20|exists:guru,id',
                'status_aktif' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            $mataPelajaran = MataPelajaran::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'kelompok' => $request->kelompok,
                'jam_per_minggu' => $request->jam_per_minggu,
                'kkm' => $request->get('kkm', 75),
                'is_wajib' => $request->boolean('is_wajib', true),
                'is_peminatan' => $request->boolean('is_peminatan', false),
                'guru_id' => $request->guru_id,
                'status_aktif' => $request->boolean('status_aktif', true),
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            return response()->json([
                'message' => 'Mata pelajaran created successfully',
                'data' => $mataPelajaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(MataPelajaran $mataPelajaran): JsonResponse
    {
        try {
            $mataPelajaran->load(['guru']);

            return response()->json([
                'message' => 'Mata pelajaran retrieved successfully',
                'data' => $mataPelajaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, MataPelajaran $mataPelajaran): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode' => 'required|string|max:20|unique:mata_pelajaran,kode,' . $mataPelajaran->id,
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'kelompok' => 'required|in:A,B,C',
                'jam_per_minggu' => 'required|integer|min:1|max:10',
                'kkm' => 'nullable|integer|min:0|max:100',
                'is_wajib' => 'boolean',
                'is_peminatan' => 'boolean',
                'guru_id' => 'nullable|string|max:20|exists:guru,id',
                'status_aktif' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            $mataPelajaran->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'kelompok' => $request->kelompok,
                'jam_per_minggu' => $request->jam_per_minggu,
                'kkm' => $request->get('kkm', $mataPelajaran->kkm),
                'is_wajib' => $request->boolean('is_wajib', $mataPelajaran->is_wajib),
                'is_peminatan' => $request->boolean('is_peminatan', $mataPelajaran->is_peminatan),
                'guru_id' => $request->guru_id,
                'status_aktif' => $request->boolean('status_aktif', $mataPelajaran->status_aktif),
                'updated_by' => $user->id,
            ]);

            return response()->json([
                'message' => 'Mata pelajaran updated successfully',
                'data' => $mataPelajaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(MataPelajaran $mataPelajaran): JsonResponse
    {
        try {
            $mataPelajaran->delete();

            return response()->json([
                'message' => 'Mata pelajaran deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function statistics(): JsonResponse
    {
        try {
            $stats = MataPelajaran::getStatistics();

            return response()->json([
                'message' => 'Mata pelajaran statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve mata pelajaran statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
