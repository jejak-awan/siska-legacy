<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Public\GeneralPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContentController extends Controller
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
            $pinned = $request->get('pinned');
            $author_role = $request->get('author_role');
            $author_id = $request->get('author_id');

            $query = GeneralPost::query();

            // Apply filters
            if ($category) {
                $query->byCategory($category);
            }

            if ($subcategory) {
                $query->where('subcategory', $subcategory);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($featured !== null) {
                $query->where('is_featured', $featured);
            }

            if ($pinned !== null) {
                $query->where('is_pinned', $pinned);
            }

            if ($author_role) {
                $query->where('author_role', $author_role);
            }

            if ($author_id) {
                $query->where('author_id', $author_id);
            }

            // Order by pinned first, then by published_at desc
            $query->orderBy('is_pinned', 'desc')
                  ->orderBy('published_at', 'desc');

            $posts = $query->paginate($perPage);

            return response()->json([
                'message' => 'Content retrieved successfully',
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve content',
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
                'content' => 'required|string',
                'category' => 'required|string|in:berita,pengumuman,artikel,agenda,prestasi,umum',
                'subcategory' => 'nullable|string|max:100',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'attachments' => 'nullable|array',
                'attachments.*' => 'string',
                'is_featured' => 'boolean',
                'is_pinned' => 'boolean',
                'status' => 'in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            $post = GeneralPost::create([
                'title' => $request->title,
                'content' => $request->content,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'status' => $request->get('status', 'draft'),
                'tags' => $request->tags ?? [],
                'attachments' => $request->attachments ?? [],
                'author_role' => $user->role_type,
                'author_id' => $user->id,
                'is_featured' => $request->boolean('is_featured', false),
                'is_pinned' => $request->boolean('is_pinned', false),
                'published_at' => $request->get('status') === 'published' ? now() : null,
            ]);

            return response()->json([
                'message' => 'Content created successfully',
                'data' => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneralPost $content): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Content retrieved successfully',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralPost $content): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Check if user can edit this content
            if (!$content->canBeEditedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to edit this content'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|in:berita,pengumuman,artikel,agenda,prestasi,umum',
                'subcategory' => 'nullable|string|max:100',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'attachments' => 'nullable|array',
                'attachments.*' => 'string',
                'is_featured' => 'boolean',
                'is_pinned' => 'boolean',
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
                'content' => $request->content,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'tags' => $request->tags ?? $content->tags,
                'attachments' => $request->attachments ?? $content->attachments,
                'is_featured' => $request->boolean('is_featured', $content->is_featured),
                'is_pinned' => $request->boolean('is_pinned', $content->is_pinned),
            ];

            // Handle status change
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
                if ($request->status === 'published' && !$content->published_at) {
                    $updateData['published_at'] = now();
                } elseif ($request->status !== 'published') {
                    $updateData['published_at'] = null;
                }
            }

            $content->update($updateData);

            return response()->json([
                'message' => 'Content updated successfully',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralPost $content): JsonResponse
    {
        try {
            $user = request()->user();
            
            // Check if user can delete this content
            if (!$content->canBeDeletedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to delete this content'
                ], 403);
            }

            $content->delete();

            return response()->json([
                'message' => 'Content deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => GeneralPost::count(),
                'published' => GeneralPost::where('status', 'published')->count(),
                'draft' => GeneralPost::where('status', 'draft')->count(),
                'archived' => GeneralPost::where('status', 'archived')->count(),
                'featured' => GeneralPost::where('is_featured', true)->count(),
                'pinned' => GeneralPost::where('is_pinned', true)->count(),
                'by_category' => GeneralPost::selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->get()
                    ->pluck('count', 'category'),
                'by_author' => GeneralPost::selectRaw('author_role, count(*) as count')
                    ->groupBy('author_role')
                    ->get()
                    ->pluck('count', 'author_role'),
                'recent_posts' => GeneralPost::where('created_at', '>=', now()->subDays(30))->count(),
                'published_this_month' => GeneralPost::where('status', 'published')
                    ->whereMonth('published_at', now()->month)
                    ->whereYear('published_at', now()->year)
                    ->count(),
            ];

            return response()->json([
                'message' => 'Content statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve content statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public content
     */
    public function public(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 12);
            $category = $request->get('category');
            $featured = $request->get('featured');
            $pinned = $request->get('pinned');

            $query = GeneralPost::published();

            if ($category) {
                $query->byCategory($category);
            }

            if ($featured) {
                $query->featured();
            }

            if ($pinned) {
                $query->pinned();
            }

            $posts = $query->orderBy('is_pinned', 'desc')
                ->orderBy('published_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Public content retrieved successfully',
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve public content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish content
     */
    public function publish(GeneralPost $content): JsonResponse
    {
        try {
            $user = request()->user();
            
            if (!$content->canBeEditedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to publish this content'
                ], 403);
            }

            $content->publish();

            return response()->json([
                'message' => 'Content published successfully',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to publish content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish content
     */
    public function unpublish(GeneralPost $content): JsonResponse
    {
        try {
            $user = request()->user();
            
            if (!$content->canBeEditedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to unpublish this content'
                ], 403);
            }

            $content->unpublish();

            return response()->json([
                'message' => 'Content unpublished successfully',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to unpublish content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archive content
     */
    public function archive(GeneralPost $content): JsonResponse
    {
        try {
            $user = request()->user();
            
            if (!$content->canBeEditedBy($user)) {
                return response()->json([
                    'message' => 'You are not authorized to archive this content'
                ], 403);
            }

            $content->archive();

            return response()->json([
                'message' => 'Content archived successfully',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to archive content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = [
                'berita' => 'Berita',
                'pengumuman' => 'Pengumuman',
                'artikel' => 'Artikel',
                'agenda' => 'Agenda',
                'prestasi' => 'Prestasi',
                'umum' => 'Umum',
            ];

            return response()->json([
                'message' => 'Content categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve content categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}