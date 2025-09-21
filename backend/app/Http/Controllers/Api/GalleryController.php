<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $category = $request->get('category');
            $subcategory = $request->get('subcategory');
            $status = $request->get('status');
            $featured = $request->get('featured');
            $public = $request->get('public');

            $query = Gallery::query();

            // Apply filters
            if ($category) {
                $query->byCategory($category);
            }

            if ($subcategory) {
                $query->bySubcategory($subcategory);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($featured !== null) {
                $query->where('is_featured', $featured);
            }

            if ($public !== null) {
                $query->where('is_public', $public);
            }

            // Order by created_at desc
            $query->orderBy('created_at', 'desc');

            $galleries = $query->paginate($perPage);

            return response()->json([
                'message' => 'Galleries retrieved successfully',
                'data' => $galleries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve galleries',
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
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:kegiatan,prestasi,ekstrakurikuler,osis,akademik,karakter,umum',
                'subcategory' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'is_featured' => 'boolean',
                'is_public' => 'boolean',
                'status' => 'in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $image = $request->file('image');
            
            // Generate unique filename
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = 'galleries/' . date('Y/m') . '/' . $filename;
            
            // Store original image
            $imagePath = $image->storeAs('galleries/' . date('Y/m'), $filename, 'public');
            
            // Create thumbnail
            $thumbnailPath = $this->createThumbnail($image, $filename);
            
            // Get image dimensions
            $imageInfo = getimagesize($image->getPathname());
            $width = $imageInfo[0] ?? null;
            $height = $imageInfo[1] ?? null;

            $gallery = Gallery::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'image_path' => $imagePath,
                'thumbnail_path' => $thumbnailPath,
                'original_filename' => $image->getClientOriginalName(),
                'file_size' => $image->getSize(),
                'mime_type' => $image->getMimeType(),
                'width' => $width,
                'height' => $height,
                'metadata' => [
                    'uploaded_at' => now()->toISOString(),
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ],
                'uploaded_by_role' => $user->role_type,
                'uploaded_by_id' => $user->id,
                'is_featured' => $request->boolean('is_featured', false),
                'is_public' => $request->boolean('is_public', true),
                'status' => $request->get('status', 'draft'),
                'tags' => $request->tags ?? [],
                'published_at' => $request->get('status') === 'published' ? now() : null,
            ]);

            return response()->json([
                'message' => 'Gallery created successfully',
                'data' => $gallery->load('uploader')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Gallery retrieved successfully',
                'data' => $gallery
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Check if user can edit this gallery
            if (!$gallery->canBeEditedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to edit this gallery'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:kegiatan,prestasi,ekstrakurikuler,osis,akademik,karakter,umum',
                'subcategory' => 'nullable|string',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'is_featured' => 'boolean',
                'is_public' => 'boolean',
                'status' => 'in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'is_featured' => $request->boolean('is_featured', $gallery->is_featured),
                'is_public' => $request->boolean('is_public', $gallery->is_public),
                'tags' => $request->tags ?? $gallery->tags,
            ];

            // Handle status change
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
                if ($request->status === 'published' && !$gallery->published_at) {
                    $updateData['published_at'] = now();
                } elseif ($request->status !== 'published') {
                    $updateData['published_at'] = null;
                }
            }

            $gallery->update($updateData);

            return response()->json([
                'message' => 'Gallery updated successfully',
                'data' => $gallery
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery): JsonResponse
    {
        try {
            $user = request()->user();
            
            // Check if user can delete this gallery
            if (!$gallery->canBeDeletedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to delete this gallery'
                ], 403);
            }

            // Delete files from storage
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            if ($gallery->thumbnail_path && Storage::disk('public')->exists($gallery->thumbnail_path)) {
                Storage::disk('public')->delete($gallery->thumbnail_path);
            }

            $gallery->delete();

            return response()->json([
                'message' => 'Gallery deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gallery statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => Gallery::count(),
                'published' => Gallery::where('status', 'published')->count(),
                'draft' => Gallery::where('status', 'draft')->count(),
                'archived' => Gallery::where('status', 'archived')->count(),
                'featured' => Gallery::where('is_featured', true)->count(),
                'public' => Gallery::where('is_public', true)->count(),
                'by_category' => Gallery::selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->get()
                    ->pluck('count', 'category'),
                'recent_uploads' => Gallery::where('created_at', '>=', now()->subDays(30))->count(),
                'total_size' => Gallery::sum('file_size'),
            ];

            return response()->json([
                'message' => 'Gallery statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve gallery statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public galleries
     */
    public function public(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 12);
            $category = $request->get('category');
            $featured = $request->get('featured');

            $query = Gallery::published()->public();

            if ($category) {
                $query->byCategory($category);
            }

            if ($featured) {
                $query->featured();
            }

            $galleries = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Public galleries retrieved successfully',
                'data' => $galleries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve public galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create thumbnail for image
     */
    private function createThumbnail($image, $filename): ?string
    {
        try {
            $thumbnailFilename = 'thumb_' . $filename;
            $thumbnailPath = 'galleries/' . date('Y/m') . '/thumbnails/' . $thumbnailFilename;
            
            // Create thumbnail directory if it doesn't exist
            $thumbnailDir = storage_path('app/public/galleries/' . date('Y/m') . '/thumbnails');
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Create thumbnail using Intervention Image
            $thumbnail = Image::make($image->getPathname())
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save(storage_path('app/public/' . $thumbnailPath));

            return $thumbnailPath;
        } catch (\Exception $e) {
            // If thumbnail creation fails, return null
            return null;
        }
    }
}