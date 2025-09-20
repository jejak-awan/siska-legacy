<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriKreditPoin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class KategoriKreditPoinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $kategori = KategoriKreditPoin::orderBy('nama')->get();
            
            return response()->json([
                'message' => 'Kategori kredit poin retrieved successfully',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve kategori kredit poin',
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
            'jenis' => 'required|in:positif,negatif',
            'nilai_default' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'warna' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategori = KategoriKreditPoin::create($request->all());
            
            return response()->json([
                'message' => 'Kategori kredit poin created successfully',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create kategori kredit poin',
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
            $kategori = KategoriKreditPoin::findOrFail($id);
            
            return response()->json([
                'message' => 'Kategori kredit poin retrieved successfully',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kategori kredit poin not found',
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
            'jenis' => 'sometimes|required|in:positif,negatif',
            'nilai_default' => 'sometimes|required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'warna' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategori = KategoriKreditPoin::findOrFail($id);
            $kategori->update($request->all());
            
            return response()->json([
                'message' => 'Kategori kredit poin updated successfully',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update kategori kredit poin',
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
            $kategori = KategoriKreditPoin::findOrFail($id);
            
            // Check if kategori is being used
            if ($kategori->kreditPoin()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete kategori that is being used by kredit poin records',
                    'error' => 'Kategori sedang digunakan'
                ], 422);
            }
            
            $kategori->delete();
            
            return response()->json([
                'message' => 'Kategori kredit poin deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete kategori kredit poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for kategori kredit poin
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => KategoriKreditPoin::count(),
                'positif' => KategoriKreditPoin::where('jenis', 'positif')->count(),
                'negatif' => KategoriKreditPoin::where('jenis', 'negatif')->count(),
                'most_used' => KategoriKreditPoin::withCount('kreditPoin')
                    ->orderBy('kredit_poin_count', 'desc')
                    ->first()
            ];
            
            return response()->json([
                'message' => 'Kategori kredit poin statistics retrieved successfully',
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
