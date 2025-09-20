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
            // TODO: Implement activities/events model
            $activities = [
                [
                    'id' => 1,
                    'title' => 'Upacara Bendera',
                    'description' => 'Upacara bendera setiap hari Senin',
                    'date' => '2024-01-15',
                    'type' => 'rutin'
                ],
                [
                    'id' => 2,
                    'title' => 'Pertandingan Olahraga',
                    'description' => 'Lomba olahraga antar kelas',
                    'date' => '2024-01-20',
                    'type' => 'kompetisi'
                ]
            ];
            
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
            // TODO: Implement news/posts model
            $news = [
                [
                    'id' => 1,
                    'title' => 'Penerimaan Siswa Baru 2024',
                    'content' => 'Pendaftaran siswa baru telah dibuka...',
                    'published_at' => '2024-01-10',
                    'author' => 'Admin Sekolah'
                ],
                [
                    'id' => 2,
                    'title' => 'Hasil Ujian Semester',
                    'content' => 'Hasil ujian semester telah diumumkan...',
                    'published_at' => '2024-01-08',
                    'author' => 'Wakil Kepala Sekolah'
                ]
            ];
            
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
            // TODO: Implement programs model
            $programs = [
                [
                    'id' => 1,
                    'name' => 'Program Unggulan',
                    'description' => 'Program khusus untuk siswa berprestasi',
                    'type' => 'akademik'
                ],
                [
                    'id' => 2,
                    'name' => 'Ekstrakurikuler',
                    'description' => 'Berbagai kegiatan ekstrakurikuler',
                    'type' => 'non_akademik'
                ]
            ];
            
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
