<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfileSekolah;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\KreditPoin;
use App\Models\Public\PublicActivity;
use App\Models\Public\GeneralPost;
use App\Models\Public\Program;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PublicContentController extends Controller
{
    /**
     * Get public activities/events
     */
    public function getActivities(): JsonResponse
    {
        try {
            $activities = PublicActivity::published()
                ->orderBy('activity_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'title' => $activity->title,
                        'description' => $activity->excerpt,
                        'date' => $activity->activity_date->format('Y-m-d'),
                        'formatted_date' => $activity->formatted_date,
                        'category' => $activity->category,
                        'subcategory' => $activity->subcategory,
                        'location' => $activity->location,
                        'participant_count' => $activity->participant_count,
                        'gallery_count' => $activity->gallery_count,
                        'is_featured' => $activity->is_featured,
                        'category_display' => $activity->getCategoryDisplayName(),
                        'subcategory_display' => $activity->getSubcategoryDisplayName(),
                    ];
                });
            
            return response()->json([
                'message' => 'Activities retrieved successfully',
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public news/posts
     */
    public function getNews(): JsonResponse
    {
        try {
            $news = GeneralPost::published()
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'content' => $post->excerpt,
                        'full_content' => $post->content,
                        'published_at' => $post->published_at->format('Y-m-d H:i:s'),
                        'formatted_date' => $post->published_at->format('d F Y'),
                        'author_role' => $post->author_role,
                        'author_id' => $post->author_id,
                        'category' => $post->category,
                        'subcategory' => $post->subcategory,
                        'tags' => $post->tags,
                        'formatted_tags' => $post->formatted_tags,
                        'is_featured' => $post->is_featured,
                        'is_pinned' => $post->is_pinned,
                        'attachments' => $post->attachments,
                    ];
                });
            
            return response()->json([
                'message' => 'News retrieved successfully',
                'data' => $news
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public programs
     */
    public function getPrograms(): JsonResponse
    {
        try {
            $programs = Program::active()
                ->orderBy('start_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($program) {
                    return [
                        'id' => $program->id,
                        'name' => $program->name,
                        'description' => $program->description,
                        'category' => $program->category,
                        'status' => $program->status,
                        'start_date' => $program->start_date->format('Y-m-d'),
                        'end_date' => $program->end_date->format('Y-m-d'),
                        'duration' => $program->duration,
                        'objectives' => $program->objectives,
                        'formatted_objectives' => $program->formatted_objectives,
                        'target_audience' => $program->target_audience,
                        'responsible_role' => $program->responsible_role,
                        'responsible_id' => $program->responsible_id,
                        'completion_percentage' => $program->completion_percentage,
                        'is_active' => $program->is_active,
                        'is_completed' => $program->is_completed,
                    ];
                });
            
            return response()->json([
                'message' => 'Programs retrieved successfully',
                'data' => $programs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve programs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured content
     */
    public function getFeaturedContent(): JsonResponse
    {
        try {
            $profile = ProfileSekolah::getActive();
            
            $featured = [
                'school_profile' => $profile ? [
                    'nama' => $profile->nama,
                    'npsn' => $profile->npsn,
                    'alamat' => $profile->alamat,
                    'telepon' => $profile->telepon,
                    'email' => $profile->email,
                    'website' => $profile->website,
                    'jenjang' => $profile->jenjang,
                    'status' => $profile->status,
                    'akreditasi' => $profile->akreditasi,
                    'logo' => $profile->logo ? asset('storage/' . $profile->logo) : null
                ] : null,
                'quick_stats' => [
                    'total_siswa' => Siswa::where('status_aktif', true)->count(),
                    'total_guru' => Guru::where('status_aktif', true)->count(),
                    'total_kelas' => Kelas::count()
                ]
            ];
            
            return response()->json([
                'message' => 'Featured content retrieved successfully',
                'data' => $featured
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve featured content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content statistics
     */
    public function getContentStats(): JsonResponse
    {
        try {
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('status', 'aktif')->count()
                ],
                'students' => [
                    'total' => Siswa::count(),
                    'active' => Siswa::where('status_aktif', true)->count(),
                    'by_class' => Siswa::select('kelas_id', DB::raw('count(*) as count'))
                        ->where('status_aktif', true)
                        ->groupBy('kelas_id')
                        ->get()
                        ->pluck('count', 'kelas_id')
                ],
                'teachers' => [
                    'total' => Guru::count(),
                    'active' => Guru::where('status_aktif', true)->count(),
                    'wali_kelas' => Guru::where('is_wali_kelas', true)->count(),
                    'bk' => Guru::where('is_konselor_bk', true)->count()
                ],
                'classes' => [
                    'total' => Kelas::count(),
                    'active' => Kelas::where('status', 'aktif')->count()
                ],
                'attendance' => [
                    'today' => Presensi::whereDate('tanggal', today())->count(),
                    'present_today' => Presensi::whereDate('tanggal', today())->where('status', 'hadir')->count(),
                    'late_today' => Presensi::whereDate('tanggal', today())->where('status', 'terlambat')->count(),
                    'absent_today' => Presensi::whereDate('tanggal', today())->where('status', 'alpha')->count()
                ],
                'credit_points' => [
                    'total' => KreditPoin::count(),
                    'pending' => KreditPoin::where('status', 'pending')->count(),
                    'approved' => KreditPoin::where('status', 'approved')->count(),
                    'rejected' => KreditPoin::where('status', 'rejected')->count()
                ]
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
     * Search public content
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', 'all');
            
            if (empty($query)) {
                return response()->json([
                    'message' => 'Search query is required',
                    'data' => []
                ], 400);
            }
            
            $results = [];
            
            // Search in school profile
            if ($type === 'all' || $type === 'profile') {
                $profile = ProfileSekolah::getActive();
                if ($profile && (
                    stripos($profile->nama, $query) !== false ||
                    stripos($profile->alamat, $query) !== false ||
                    stripos($profile->visi, $query) !== false ||
                    stripos($profile->misi, $query) !== false
                )) {
                    $results[] = [
                        'type' => 'profile',
                        'title' => $profile->nama,
                        'description' => $profile->alamat,
                        'url' => '/profile-sekolah'
                    ];
                }
            }
            
            // Search in students
            if ($type === 'all' || $type === 'students') {
                $students = Siswa::with('user')
                    ->whereHas('user', function($q) use ($query) {
                        $q->where('username', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->orWhere('nama_lengkap', 'like', "%{$query}%")
                    ->orWhere('nis', 'like', "%{$query}%")
                    ->limit(5)
                    ->get();
                
                foreach ($students as $student) {
                    $results[] = [
                        'type' => 'student',
                        'title' => $student->nama_lengkap,
                        'description' => 'NIS: ' . $student->nis,
                        'url' => '/siswa/' . $student->id
                    ];
                }
            }
            
            // Search in teachers
            if ($type === 'all' || $type === 'teachers') {
                $teachers = Guru::with('user')
                    ->whereHas('user', function($q) use ($query) {
                        $q->where('username', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->orWhere('nama_lengkap', 'like', "%{$query}%")
                    ->orWhere('nip', 'like', "%{$query}%")
                    ->limit(5)
                    ->get();
                
                foreach ($teachers as $teacher) {
                    $results[] = [
                        'type' => 'teacher',
                        'title' => $teacher->nama_lengkap,
                        'description' => 'NIP: ' . $teacher->nip,
                        'url' => '/guru/' . $teacher->id
                    ];
                }
            }
            
            return response()->json([
                'message' => 'Search completed successfully',
                'data' => [
                    'query' => $query,
                    'type' => $type,
                    'results' => $results,
                    'total' => count($results)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
