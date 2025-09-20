<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\KreditPoin;
use App\Models\Notifikasi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics for admin
     */
    public function adminStats(): JsonResponse
    {
        try {
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('status', 'aktif')->count(),
                    'inactive' => User::where('status', 'tidak_aktif')->count(),
                    'by_role' => User::select('role_type', DB::raw('count(*) as count'))
                                   ->groupBy('role_type')
                                   ->get()
                                   ->pluck('count', 'role_type')
                ],
                'guru' => [
                    'total' => Guru::count(),
                    'active' => Guru::where('status_aktif', true)->count(),
                    'wali_kelas' => Guru::where('is_wali_kelas', true)->count(),
                    'bk' => Guru::where('is_konselor_bk', true)->count(),
                ],
                'siswa' => [
                    'total' => Siswa::count(),
                    'active' => Siswa::where('status_aktif', true)->count(),
                    'by_kelas' => Siswa::select('kelas_id', DB::raw('count(*) as count'))
                                     ->where('status_aktif', true)
                                     ->groupBy('kelas_id')
                                     ->get()
                                     ->pluck('count', 'kelas_id')
                ],
                'presensi' => [
                    'today' => Presensi::whereDate('tanggal', today())->count(),
                    'hadir_today' => Presensi::whereDate('tanggal', today())->where('status', 'hadir')->count(),
                    'terlambat_today' => Presensi::whereDate('tanggal', today())->where('status', 'terlambat')->count(),
                    'alpha_today' => Presensi::whereDate('tanggal', today())->where('status', 'alpha')->count(),
                ],
                'kredit_poin' => [
                    'pending' => KreditPoin::where('status', 'pending')->count(),
                    'approved_today' => KreditPoin::whereDate('created_at', today())->where('status', 'approved')->count(),
                    'total_positif' => KreditPoin::whereHas('kategori', function($q) {
                        $q->where('jenis', 'positif');
                    })->where('status', 'approved')->sum('nilai'),
                    'total_negatif' => KreditPoin::whereHas('kategori', function($q) {
                        $q->where('jenis', 'negatif');
                    })->where('status', 'approved')->sum('nilai'),
                ],
                'notifications' => [
                    'total' => Notifikasi::count(),
                    'unread' => Notifikasi::where('status', 'unread')->count(),
                    'today' => Notifikasi::whereDate('created_at', today())->count(),
                ]
            ];

            return response()->json([
                'message' => 'Admin dashboard statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve admin dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics for guru
     */
    public function guruStats(): JsonResponse
    {
        try {
            $user = Auth::user();
            $guru = $user->guru;

            if (!$guru) {
                return response()->json([
                    'message' => 'Guru profile not found'
                ], 404);
            }

            $stats = [
                'profile' => [
                    'nama' => $guru->nama_lengkap,
                    'jabatan' => $guru->jabatan,
                    'is_wali_kelas' => $guru->is_wali_kelas,
                    'kelas_wali' => $guru->kelas_wali,
                ],
                'kelas_ampu' => $guru->kelas_yang_diajar ?? [],
                'presensi' => [
                    'today' => Presensi::where('user_id', $user->id)
                                     ->whereDate('tanggal', today())
                                     ->first(),
                    'this_month' => Presensi::where('user_id', $user->id)
                                          ->whereMonth('tanggal', now()->month)
                                          ->whereYear('tanggal', now()->year)
                                          ->count(),
                    'hadir_this_month' => Presensi::where('user_id', $user->id)
                                                ->whereMonth('tanggal', now()->month)
                                                ->whereYear('tanggal', now()->year)
                                                ->where('status', 'hadir')
                                                ->count(),
                ],
                'kredit_poin' => [
                    'reported_today' => KreditPoin::where('pelapor_id', $user->id)
                                                ->whereDate('created_at', today())
                                                ->count(),
                    'reported_this_month' => KreditPoin::where('pelapor_id', $user->id)
                                                     ->whereMonth('created_at', now()->month)
                                                     ->whereYear('created_at', now()->year)
                                                     ->count(),
                ],
                'notifications' => [
                    'unread' => Notifikasi::where('user_id', $user->id)
                                        ->where('status', 'unread')
                                        ->count(),
                ]
            ];

            return response()->json([
                'message' => 'Guru dashboard statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve guru dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics for siswa
     */
    public function siswaStats(): JsonResponse
    {
        try {
            $user = Auth::user();
            $siswa = $user->siswa;

            if (!$siswa) {
                return response()->json([
                    'message' => 'Siswa profile not found'
                ], 404);
            }

            $stats = [
                'profile' => [
                    'nama' => $siswa->nama_lengkap,
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas?->nama_kelas,
                    'angkatan' => $siswa->angkatan,
                ],
                'presensi' => [
                    'today' => Presensi::where('user_id', $user->id)
                                     ->whereDate('tanggal', today())
                                     ->first(),
                    'this_month' => Presensi::where('user_id', $user->id)
                                          ->whereMonth('tanggal', now()->month)
                                          ->whereYear('tanggal', now()->year)
                                          ->count(),
                    'hadir_this_month' => Presensi::where('user_id', $user->id)
                                                ->whereMonth('tanggal', now()->month)
                                                ->whereYear('tanggal', now()->year)
                                                ->where('status', 'hadir')
                                                ->count(),
                ],
                'kredit_poin' => [
                    'total_positif' => KreditPoin::where('siswa_id', $siswa->id)
                                                ->whereHas('kategori', function($q) {
                                                    $q->where('jenis', 'positif');
                                                })
                                                ->where('status', 'approved')
                                                ->sum('nilai'),
                    'total_negatif' => KreditPoin::where('siswa_id', $siswa->id)
                                                ->whereHas('kategori', function($q) {
                                                    $q->where('jenis', 'negatif');
                                                })
                                                ->where('status', 'approved')
                                                ->sum('nilai'),
                    'pending' => KreditPoin::where('siswa_id', $siswa->id)
                                         ->where('status', 'pending')
                                         ->count(),
                ],
                'notifications' => [
                    'unread' => Notifikasi::where('user_id', $user->id)
                                        ->where('status', 'unread')
                                        ->count(),
                ]
            ];

            $stats['kredit_poin']['total_bersih'] = $stats['kredit_poin']['total_positif'] - abs($stats['kredit_poin']['total_negatif']);

            return response()->json([
                'message' => 'Siswa dashboard statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve siswa dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics based on user role
     */
    public function getStats(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            switch ($user->role_type) {
                case 'admin':
                    return $this->adminStats();
                case 'guru':
                    return $this->guruStats();
                case 'siswa':
                    return $this->siswaStats();
                default:
                    return response()->json([
                        'message' => 'Dashboard not available for this role'
                    ], 403);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}