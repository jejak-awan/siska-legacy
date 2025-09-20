<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    private $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload single image
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'folder' => 'nullable|string|max:255',
            'optimize' => 'nullable|boolean',
            'width' => 'nullable|integer|min:1|max:4000',
            'height' => 'nullable|integer|min:1|max:4000',
            'quality' => 'nullable|integer|min:1|max:100',
        ]);

        try {
            $file = $request->file('image');
            $folder = $request->input('folder', 'uploads');
            $optimize = $request->input('optimize', true);
            $width = $request->input('width');
            $height = $request->input('height');
            $quality = $request->input('quality', 85);

            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $path = $folder . '/' . $filename;

            if ($optimize) {
                // Process image with Intervention Image
                $image = $this->imageManager->read($file);
                
                // Resize if dimensions provided
                if ($width || $height) {
                    $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Optimize and save
                $optimizedImage = $image->toJpeg($quality);
                Storage::disk('public')->put($path, $optimizedImage);
            } else {
                // Save original file
                Storage::disk('public')->putFileAs($folder, $file, $filename);
            }

            $url = Storage::disk('public')->url($path);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diupload',
                'data' => [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => $url,
                    'size' => Storage::disk('public')->size($path),
                    'mime_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                    'optimized' => $optimize
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload multiple images
     */
    public function uploadMultiple(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'folder' => 'nullable|string|max:255',
            'optimize' => 'nullable|boolean',
            'width' => 'nullable|integer|min:1|max:4000',
            'height' => 'nullable|integer|min:1|max:4000',
            'quality' => 'nullable|integer|min:1|max:100',
        ]);

        try {
            $files = $request->file('images');
            $folder = $request->input('folder', 'uploads');
            $optimize = $request->input('optimize', true);
            $width = $request->input('width');
            $height = $request->input('height');
            $quality = $request->input('quality', 85);

            $uploadedImages = [];

            foreach ($files as $file) {
                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $folder . '/' . $filename;

                if ($optimize) {
                    // Process image with Intervention Image
                    $image = $this->imageManager->read($file);
                    
                    // Resize if dimensions provided
                    if ($width || $height) {
                        $image->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    }

                    // Optimize and save
                    $optimizedImage = $image->toJpeg($quality);
                    Storage::disk('public')->put($path, $optimizedImage);
                } else {
                    // Save original file
                    Storage::disk('public')->putFileAs($folder, $file, $filename);
                }

                $url = Storage::disk('public')->url($path);

                $uploadedImages[] = [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => $url,
                    'size' => Storage::disk('public')->size($path),
                    'mime_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                    'optimized' => $optimize
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' gambar berhasil diupload',
                'data' => $uploadedImages
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Resize existing image
     */
    public function resize(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'width' => 'required|integer|min:1|max:4000',
            'height' => 'required|integer|min:1|max:4000',
            'quality' => 'nullable|integer|min:1|max:100',
        ]);

        try {
            $path = $request->input('path');
            $width = $request->input('width');
            $height = $request->input('height');
            $quality = $request->input('quality', 85);

            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }

            // Read existing image
            $imagePath = Storage::disk('public')->path($path);
            $image = $this->imageManager->read($imagePath);

            // Resize image
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Generate new filename
            $pathInfo = pathinfo($path);
            $newFilename = $pathInfo['filename'] . '_' . $width . 'x' . $height . '.' . $pathInfo['extension'];
            $newPath = $pathInfo['dirname'] . '/' . $newFilename;

            // Save resized image
            $resizedImage = $image->toJpeg($quality);
            Storage::disk('public')->put($newPath, $resizedImage);

            $url = Storage::disk('public')->url($newPath);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diresize',
                'data' => [
                    'filename' => $newFilename,
                    'path' => $newPath,
                    'url' => $url,
                    'size' => Storage::disk('public')->size($newPath),
                    'dimensions' => [
                        'width' => $width,
                        'height' => $height
                    ]
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal meresize gambar: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete image
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');

            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }

            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get image info
     */
    public function info(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');

            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }

            $imagePath = Storage::disk('public')->path($path);
            $image = $this->imageManager->read($imagePath);

            return response()->json([
                'success' => true,
                'data' => [
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'size' => Storage::disk('public')->size($path),
                    'mime_type' => Storage::disk('public')->mimeType($path),
                    'dimensions' => [
                        'width' => $image->width(),
                        'height' => $image->height()
                    ],
                    'created_at' => Storage::disk('public')->lastModified($path)
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan info gambar: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
