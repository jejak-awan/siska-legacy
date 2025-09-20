<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferenceCategory;
use App\Models\ReferenceData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ReferenceDataController extends Controller
{
    /**
     * Get all reference categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = ReferenceCategory::active()
            ->ordered()
            ->withCount(['referenceData as data_count'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get reference data by category
     */
    public function getDataByCategory(Request $request, $categoryId): JsonResponse
    {
        $category = ReferenceCategory::findOrFail($categoryId);
        
        $query = $category->referenceData()->ordered();
        
        if ($request->has('active_only') && $request->boolean('active_only')) {
            $query->active();
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $data
        ]);
    }

    /**
     * Create new reference category
     */
    public function createCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:reference_categories,name',
            'description' => 'nullable|string',
            'fields_config' => 'nullable|array',
            'fields_config.*.name' => 'required|string',
            'fields_config.*.label' => 'required|string',
            'fields_config.*.type' => 'required|string|in:text,number,email,date,select,textarea',
            'fields_config.*.required' => 'boolean',
            'fields_config.*.max_length' => 'nullable|integer',
            'fields_config.*.options' => 'nullable|array'
        ]);

        $category = ReferenceCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'fields_config' => $request->fields_config,
            'sort_order' => ReferenceCategory::max('sort_order') + 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori referensi berhasil dibuat',
            'data' => $category
        ], 201);
    }

    /**
     * Update reference category
     */
    public function updateCategory(Request $request, $categoryId): JsonResponse
    {
        $category = ReferenceCategory::findOrFail($categoryId);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('reference_categories', 'name')->ignore($categoryId)
            ],
            'description' => 'nullable|string',
            'fields_config' => 'nullable|array',
            'fields_config.*.name' => 'required|string',
            'fields_config.*.label' => 'required|string',
            'fields_config.*.type' => 'required|string|in:text,number,email,date,select,textarea',
            'fields_config.*.required' => 'boolean',
            'fields_config.*.max_length' => 'nullable|integer',
            'fields_config.*.options' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $category->update($request->only([
            'name', 'description', 'fields_config', 'is_active'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Kategori referensi berhasil diperbarui',
            'data' => $category
        ]);
    }

    /**
     * Delete reference category
     */
    public function deleteCategory($categoryId): JsonResponse
    {
        $category = ReferenceCategory::findOrFail($categoryId);
        
        // Check if category has reference data
        if ($category->referenceData()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus kategori yang memiliki data referensi'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori referensi berhasil dihapus'
        ]);
    }

    /**
     * Create new reference data
     */
    public function createData(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => 'required|exists:reference_categories,id',
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'custom_fields' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        // Validate unique code within category
        if ($request->code) {
            $request->validate([
                'code' => [
                    Rule::unique('reference_data', 'code')->where('category_id', $request->category_id)
                ]
            ]);
        }

        $data = ReferenceData::create([
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'custom_fields' => $request->custom_fields,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => ReferenceData::where('category_id', $request->category_id)->max('sort_order') + 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data referensi berhasil dibuat',
            'data' => $data->load('category')
        ], 201);
    }

    /**
     * Update reference data
     */
    public function updateData(Request $request, $dataId): JsonResponse
    {
        $data = ReferenceData::findOrFail($dataId);

        $request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'custom_fields' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        // Validate unique code within category
        if ($request->code) {
            $request->validate([
                'code' => [
                    Rule::unique('reference_data', 'code')
                        ->where('category_id', $data->category_id)
                        ->ignore($dataId)
                ]
            ]);
        }

        $data->update($request->only([
            'code', 'name', 'description', 'custom_fields', 'is_active'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data referensi berhasil diperbarui',
            'data' => $data->load('category')
        ]);
    }

    /**
     * Delete reference data
     */
    public function deleteData($dataId): JsonResponse
    {
        $data = ReferenceData::findOrFail($dataId);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data referensi berhasil dihapus'
        ]);
    }

    /**
     * Get reference data for dropdown/select
     */
    public function getForSelect(Request $request, $categoryId): JsonResponse
    {
        $category = ReferenceCategory::findOrFail($categoryId);
        
        $data = $category->activeReferenceData()
            ->ordered()
            ->select('id', 'code', 'name')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->display_name,
                    'code' => $item->code,
                    'name' => $item->name
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Search reference data
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'category_id' => 'nullable|exists:reference_categories,id'
        ]);

        $query = ReferenceData::with('category')
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('code', 'like', '%' . $request->query . '%')
                  ->orWhere('description', 'like', '%' . $request->query . '%');
            });

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $results = $query->active()->ordered()->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}
