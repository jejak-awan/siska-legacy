<?php

namespace App\Services;

use App\Models\Presensi;
use App\Models\JadwalPresensi;
use App\Models\User;
use App\Services\NotificationService;
use App\Events\PresensiCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresensiService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create attendance record
     */
    public function createPresensi(array $data): Presensi
    {
        DB::beginTransaction();
        
        try {
            // Validate user exists and is active
            $user = User::findOrFail($data['user_id']);
            if ($user->status !== 'aktif') {
                throw new \Exception('User is not active');
            }

            // Check if attendance already exists for today
            $existingPresensi = Presensi::where('user_id', $data['user_id'])
                ->whereDate('tanggal', $data['tanggal'])
                ->first();

            if ($existingPresensi) {
                throw new \Exception('Attendance already exists for this date');
            }

            // Validate attendance time against schedule
            $this->validateAttendanceTime($data);

            // Create attendance record
            $presensi = Presensi::create([
                'user_id' => $data['user_id'],
                'tanggal' => $data['tanggal'],
                'jam_masuk' => $data['jam_masuk'],
                'jam_keluar' => $data['jam_keluar'] ?? null,
                'status' => $this->determineStatus($data),
                'lokasi_lat' => $data['lokasi_lat'] ?? null,
                'lokasi_lng' => $data['lokasi_lng'] ?? null,
                'qr_code' => $data['qr_code'] ?? null,
                'foto_absen' => $data['foto_absen'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
                'divalidasi_oleh' => $data['divalidasi_oleh'] ?? null,
            ]);

            // Send notification if late or absent
            $this->sendAttendanceNotification($presensi);

            // Broadcast event for real-time updates
            event(new PresensiCreated($presensi));

            DB::commit();
            return $presensi;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create presensi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update attendance record
     */
    public function updatePresensi(Presensi $presensi, array $data): bool
    {
        DB::beginTransaction();
        
        try {
            // Validate attendance time if provided
            if (isset($data['jam_masuk']) || isset($data['jam_keluar'])) {
                $this->validateAttendanceTime(array_merge($presensi->toArray(), $data));
            }

            // Update attendance record
            $updated = $presensi->update([
                'jam_masuk' => $data['jam_masuk'] ?? $presensi->jam_masuk,
                'jam_keluar' => $data['jam_keluar'] ?? $presensi->jam_keluar,
                'status' => $data['status'] ?? $this->determineStatus(array_merge($presensi->toArray(), $data)),
                'lokasi_lat' => $data['lokasi_lat'] ?? $presensi->lokasi_lat,
                'lokasi_lng' => $data['lokasi_lng'] ?? $presensi->lokasi_lng,
                'qr_code' => $data['qr_code'] ?? $presensi->qr_code,
                'foto_absen' => $data['foto_absen'] ?? $presensi->foto_absen,
                'keterangan' => $data['keterangan'] ?? $presensi->keterangan,
                'divalidasi_oleh' => $data['divalidasi_oleh'] ?? $presensi->divalidasi_oleh,
            ]);

            if ($updated) {
                // Send notification if status changed
                $this->sendAttendanceUpdateNotification($presensi);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update presensi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate attendance time against schedule
     */
    private function validateAttendanceTime(array $data): void
    {
        $user = User::find($data['user_id']);
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Get user's schedule for the date
        $jadwal = JadwalPresensi::where('kelas_id', $user->siswa->kelas_id ?? null)
            ->where('is_aktif', true)
            ->whereDate('tanggal_mulai', '<=', $data['tanggal'])
            ->whereDate('tanggal_selesai', '>=', $data['tanggal'])
            ->first();

        if (!$jadwal) {
            // No schedule found, allow attendance
            return;
        }

        $jamMasuk = $data['jam_masuk'];
        $jamMulai = $jadwal->jam_mulai;
        $jamSelesai = $jadwal->jam_selesai;

        // Check if attendance time is within schedule
        if ($jamMasuk < $jamMulai || $jamMasuk > $jamSelesai) {
            Log::warning('Attendance time outside schedule', [
                'user_id' => $data['user_id'],
                'attendance_time' => $jamMasuk,
                'schedule_start' => $jamMulai,
                'schedule_end' => $jamSelesai
            ]);
        }
    }

    /**
     * Determine attendance status
     */
    private function determineStatus(array $data): string
    {
        $jamMasuk = $data['jam_masuk'];
        
        // Get user's schedule
        $user = User::find($data['user_id']);
        $jadwal = JadwalPresensi::where('kelas_id', $user->siswa->kelas_id ?? null)
            ->where('is_aktif', true)
            ->first();

        if (!$jadwal) {
            return 'hadir';
        }

        $jamMulai = $jadwal->jam_mulai;
        $toleransi = 15; // 15 minutes tolerance

        // Calculate difference in minutes
        $masukTime = strtotime($jamMasuk);
        $mulaiTime = strtotime($jamMulai);
        $diffMinutes = ($masukTime - $mulaiTime) / 60;

        if ($diffMinutes <= $toleransi) {
            return 'hadir';
        } elseif ($diffMinutes <= 30) {
            return 'terlambat';
        } else {
            return 'alfa';
        }
    }

    /**
     * Get attendance statistics
     */
    public function getStatistics(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');

        return [
            'total_presensi' => Presensi::count(),
            'presensi_hari_ini' => Presensi::whereDate('tanggal', $today)->count(),
            'presensi_bulan_ini' => Presensi::whereRaw('strftime("%Y-%m", tanggal) = ?', [$thisMonth])->count(),
            'presensi_bulan_lalu' => Presensi::whereRaw('strftime("%Y-%m", tanggal) = ?', [$lastMonth])->count(),
            'by_status' => Presensi::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_month' => Presensi::selectRaw('strftime("%Y-%m", tanggal) as month, count(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray(),
            'attendance_rate' => $this->calculateAttendanceRate(),
            'late_rate' => $this->calculateLateRate(),
            'absent_rate' => $this->calculateAbsentRate()
        ];
    }

    /**
     * Calculate attendance rate
     */
    private function calculateAttendanceRate(): float
    {
        $total = Presensi::count();
        if ($total === 0) return 0;

        $present = Presensi::whereIn('status', ['hadir', 'terlambat'])->count();
        return round(($present / $total) * 100, 2);
    }

    /**
     * Calculate late rate
     */
    private function calculateLateRate(): float
    {
        $total = Presensi::count();
        if ($total === 0) return 0;

        $late = Presensi::where('status', 'terlambat')->count();
        return round(($late / $total) * 100, 2);
    }

    /**
     * Calculate absent rate
     */
    private function calculateAbsentRate(): float
    {
        $total = Presensi::count();
        if ($total === 0) return 0;

        $absent = Presensi::where('status', 'alfa')->count();
        return round(($absent / $total) * 100, 2);
    }

    /**
     * Get user attendance summary
     */
    public function getUserAttendanceSummary(int $userId, int $days = 30): array
    {
        $startDate = now()->subDays($days)->toDateString();
        $endDate = now()->toDateString();

        $presensi = Presensi::where('user_id', $userId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        return [
            'total_days' => $days,
            'present' => $presensi->whereIn('status', ['hadir', 'terlambat'])->count(),
            'late' => $presensi->where('status', 'terlambat')->count(),
            'absent' => $presensi->where('status', 'alfa')->count(),
            'attendance_rate' => $days > 0 ? round(($presensi->whereIn('status', ['hadir', 'terlambat'])->count() / $days) * 100, 2) : 0,
            'recent_attendance' => $presensi->sortByDesc('tanggal')->take(7)->values()
        ];
    }

    /**
     * Send attendance notification
     */
    private function sendAttendanceNotification(Presensi $presensi): void
    {
        $user = $presensi->user;
        $status = $presensi->status;

        if ($status === 'terlambat') {
            $this->notificationService->sendNotification([
                'user_id' => $user->id,
                'judul' => 'Anda Terlambat',
                'pesan' => "Anda terlambat pada tanggal {$presensi->tanggal} pukul {$presensi->jam_masuk}.",
                'tipe' => 'warning',
                'action_url' => "/dashboard/presensi",
                'action_text' => 'Lihat Presensi',
                'data' => [
                    'presensi_id' => $presensi->id,
                    'status' => $status,
                    'tanggal' => $presensi->tanggal
                ]
            ]);
        } elseif ($status === 'alfa') {
            $this->notificationService->sendNotification([
                'user_id' => $user->id,
                'judul' => 'Anda Tidak Hadir',
                'pesan' => "Anda tidak hadir pada tanggal {$presensi->tanggal}.",
                'tipe' => 'error',
                'action_url' => "/dashboard/presensi",
                'action_text' => 'Lihat Presensi',
                'data' => [
                    'presensi_id' => $presensi->id,
                    'status' => $status,
                    'tanggal' => $presensi->tanggal
                ]
            ]);
        }
    }

    /**
     * Send attendance update notification
     */
    private function sendAttendanceUpdateNotification(Presensi $presensi): void
    {
        $user = $presensi->user;

        $this->notificationService->sendNotification([
            'user_id' => $user->id,
            'judul' => 'Presensi Diperbarui',
            'pesan' => "Presensi Anda pada tanggal {$presensi->tanggal} telah diperbarui.",
            'tipe' => 'info',
            'action_url' => "/dashboard/presensi",
            'action_text' => 'Lihat Presensi',
            'data' => [
                'presensi_id' => $presensi->id,
                'status' => $presensi->status,
                'tanggal' => $presensi->tanggal
            ]
        ]);
    }

    /**
     * Generate attendance report
     */
    public function generateAttendanceReport(array $filters = []): array
    {
        $query = Presensi::with(['user', 'validator']);

        if (isset($filters['start_date'])) {
            $query->whereDate('tanggal', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('tanggal', '<=', $filters['end_date']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('tanggal', 'desc')->get()->toArray();
    }

    /**
     * Bulk update attendance
     */
    public function bulkUpdateAttendance(array $presensiIds, array $data): int
    {
        $count = 0;
        
        foreach ($presensiIds as $id) {
            $presensi = Presensi::find($id);
            if ($presensi) {
                if ($this->updatePresensi($presensi, $data)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }
}
