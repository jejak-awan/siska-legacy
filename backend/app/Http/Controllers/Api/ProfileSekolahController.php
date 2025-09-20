<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileSekolahController extends Controller
{
    /**
     * Get the active school profile
     */
    public function index(): JsonResponse
    {
        try {
            $profile = ProfileSekolah::getActive();
            
            if (!$profile) {
                // Create default profile if none exists
                $profile = ProfileSekolah::create([
                    'nama' => 'Nama Sekolah',
                    'npsn' => '',
                    'alamat' => '',
                    'telepon' => '',
                    'email' => '',
                    'website' => '',
                    'jenjang' => 'SMA',
                    'status' => 'Negeri',
                    'akreditasi' => 'A',
                    'kepala_sekolah' => '',
                    'visi' => '',
                    'misi' => '',
                    'tujuan' => '',
                    'is_active' => true
                ]);
            }
            
            return response()->json([
                'message' => 'School profile retrieved successfully',
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve school profile',
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
            'npsn' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'jenjang' => 'required|in:SD,SMP,SMA,SMK',
            'status' => 'required|in:Negeri,Swasta',
            'akreditasi' => 'nullable|in:A,B,C,TT',
            'kepala_sekolah' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_kepala_sekolah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kontak_lain' => 'nullable|array',
            'sosial_media' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['logo', 'foto_kepala_sekolah']);
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('school/logos', 'public');
                $data['logo'] = $logoPath;
            }
            
            // Handle kepala sekolah photo upload
            if ($request->hasFile('foto_kepala_sekolah')) {
                $fotoPath = $request->file('foto_kepala_sekolah')->store('school/headmaster', 'public');
                $data['foto_kepala_sekolah'] = $fotoPath;
            }
            
            $profile = ProfileSekolah::create($data);
            $profile->setActive();
            
            return response()->json([
                'message' => 'School profile created successfully',
                'data' => $profile
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create school profile',
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
            $profile = ProfileSekolah::findOrFail($id);
            
            return response()->json([
                'message' => 'School profile retrieved successfully',
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'School profile not found',
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
            'npsn' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'jenjang' => 'sometimes|required|in:SD,SMP,SMA,SMK',
            'status' => 'sometimes|required|in:Negeri,Swasta',
            'akreditasi' => 'nullable|in:A,B,C,TT',
            'kepala_sekolah' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_kepala_sekolah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kontak_lain' => 'nullable|array',
            'sosial_media' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $profile = ProfileSekolah::findOrFail($id);
            $data = $request->except(['logo', 'foto_kepala_sekolah']);
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($profile->logo && Storage::disk('public')->exists($profile->logo)) {
                    Storage::disk('public')->delete($profile->logo);
                }
                
                $logoPath = $request->file('logo')->store('school/logos', 'public');
                $data['logo'] = $logoPath;
            }
            
            // Handle kepala sekolah photo upload
            if ($request->hasFile('foto_kepala_sekolah')) {
                // Delete old photo
                if ($profile->foto_kepala_sekolah && Storage::disk('public')->exists($profile->foto_kepala_sekolah)) {
                    Storage::disk('public')->delete($profile->foto_kepala_sekolah);
                }
                
                $fotoPath = $request->file('foto_kepala_sekolah')->store('school/headmaster', 'public');
                $data['foto_kepala_sekolah'] = $fotoPath;
            }
            
            $profile->update($data);
            
            return response()->json([
                'message' => 'School profile updated successfully',
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update school profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update basic info only
     */
    public function updateBasicInfo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'jenjang' => 'required|in:SD,SMP,SMA,SMK',
            'status' => 'required|in:Negeri,Swasta',
            'akreditasi' => 'nullable|in:A,B,C,TT'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $profile = ProfileSekolah::getActive();
            
            if (!$profile) {
                return response()->json([
                    'message' => 'No active school profile found',
                    'error' => 'Profile not found'
                ], 404);
            }
            
            $profile->update($request->only([
                'nama', 'npsn', 'alamat', 'telepon', 'email', 
                'website', 'jenjang', 'status', 'akreditasi'
            ]));
            
            return response()->json([
                'message' => 'Basic info updated successfully',
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update basic info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update school details (visi, misi, etc.)
     */
    public function updateSchoolDetails(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kepala_sekolah' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'kontak_lain' => 'nullable|array',
            'sosial_media' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $profile = ProfileSekolah::getActive();
            
            if (!$profile) {
                return response()->json([
                    'message' => 'No active school profile found',
                    'error' => 'Profile not found'
                ], 404);
            }
            
            $profile->update($request->only([
                'kepala_sekolah', 'visi', 'misi', 'tujuan', 
                'sejarah', 'prestasi', 'kontak_lain', 'sosial_media'
            ]));
            
            return response()->json([
                'message' => 'School details updated successfully',
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update school details',
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
            $profile = ProfileSekolah::findOrFail($id);
            
            // Don't allow deletion of active profile
            if ($profile->is_active) {
                return response()->json([
                    'message' => 'Cannot delete active school profile',
                    'error' => 'Active profile cannot be deleted'
                ], 422);
            }
            
            // Delete associated files
            if ($profile->logo && Storage::disk('public')->exists($profile->logo)) {
                Storage::disk('public')->delete($profile->logo);
            }
            
            if ($profile->foto_kepala_sekolah && Storage::disk('public')->exists($profile->foto_kepala_sekolah)) {
                Storage::disk('public')->delete($profile->foto_kepala_sekolah);
            }
            
            $profile->delete();
            
            return response()->json([
                'message' => 'School profile deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete school profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
