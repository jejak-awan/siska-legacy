<?php

namespace App\Services;

use App\Models\Konseling;
use App\Models\HomeVisit;
use App\Models\Siswa;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BKService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create counseling session
     */
    public function createKonseling(array $data): Konseling
    {
        DB::beginTransaction();
        
        try {
            // Validate student exists
            $siswa = Siswa::findOrFail($data['siswa_id']);
            
            // Validate counselor exists and has BK role
            $konselor = User::findOrFail($data['konselor_id']);
            if (!$konselor->hasRole('bk')) {
                throw new \Exception('User is not a counselor');
            }

            // Create counseling session
            $konseling = Konseling::create([
                'siswa_id' => $data['siswa_id'],
                'konselor_id' => $data['konselor_id'],
                'tanggal_konseling' => $data['tanggal_konseling'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'jenis_konseling' => $data['jenis_konseling'],
                'status' => $data['status'] ?? 'terjadwal',
                'masalah' => $data['masalah'],
                'tujuan_konseling' => $data['tujuan_konseling'],
                'intervensi' => $data['intervensi'] ?? null,
                'hasil_konseling' => $data['hasil_konseling'] ?? null,
                'tindak_lanjut' => $data['tindak_lanjut'] ?? null,
                'catatan_konselor' => $data['catatan_konselor'] ?? null,
                'tempat_konseling' => $data['tempat_konseling'] ?? 'Ruang BK',
                'is_urgent' => $data['is_urgent'] ?? false,
                'is_confidential' => $data['is_confidential'] ?? true,
            ]);

            // Send notifications
            $this->sendKonselingNotifications($konseling);

            DB::commit();
            return $konseling;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create konseling: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update counseling session
     */
    public function updateKonseling(Konseling $konseling, array $data): bool
    {
        DB::beginTransaction();
        
        try {
            $updated = $konseling->update([
                'tanggal_konseling' => $data['tanggal_konseling'] ?? $konseling->tanggal_konseling,
                'jam_mulai' => $data['jam_mulai'] ?? $konseling->jam_mulai,
                'jam_selesai' => $data['jam_selesai'] ?? $konseling->jam_selesai,
                'jenis_konseling' => $data['jenis_konseling'] ?? $konseling->jenis_konseling,
                'status' => $data['status'] ?? $konseling->status,
                'masalah' => $data['masalah'] ?? $konseling->masalah,
                'tujuan_konseling' => $data['tujuan_konseling'] ?? $konseling->tujuan_konseling,
                'intervensi' => $data['intervensi'] ?? $konseling->intervensi,
                'hasil_konseling' => $data['hasil_konseling'] ?? $konseling->hasil_konseling,
                'tindak_lanjut' => $data['tindak_lanjut'] ?? $konseling->tindak_lanjut,
                'catatan_konselor' => $data['catatan_konselor'] ?? $konseling->catatan_konselor,
                'tempat_konseling' => $data['tempat_konseling'] ?? $konseling->tempat_konseling,
                'is_urgent' => $data['is_urgent'] ?? $konseling->is_urgent,
                'is_confidential' => $data['is_confidential'] ?? $konseling->is_confidential,
            ]);

            if ($updated) {
                $this->sendKonselingUpdateNotifications($konseling);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update konseling: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create home visit
     */
    public function createHomeVisit(array $data): HomeVisit
    {
        DB::beginTransaction();
        
        try {
            // Validate student exists
            $siswa = Siswa::findOrFail($data['siswa_id']);
            
            // Validate counselor exists and has BK role
            $konselor = User::findOrFail($data['konselor_id']);
            if (!$konselor->hasRole('bk')) {
                throw new \Exception('User is not a counselor');
            }

            // Create home visit
            $homeVisit = HomeVisit::create([
                'siswa_id' => $data['siswa_id'],
                'konselor_id' => $data['konselor_id'],
                'tanggal_kunjungan' => $data['tanggal_kunjungan'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'status' => $data['status'] ?? 'terjadwal',
                'alamat_kunjungan' => $data['alamat_kunjungan'],
                'koordinat_lat' => $data['koordinat_lat'] ?? null,
                'koordinat_lng' => $data['koordinat_lng'] ?? null,
                'tujuan_kunjungan' => $data['tujuan_kunjungan'],
                'hasil_kunjungan' => $data['hasil_kunjungan'] ?? null,
                'catatan_kunjungan' => $data['catatan_kunjungan'] ?? null,
                'tindak_lanjut' => $data['tindak_lanjut'] ?? null,
                'foto_kunjungan' => $data['foto_kunjungan'] ?? null,
                'is_urgent' => $data['is_urgent'] ?? false,
                'is_confidential' => $data['is_confidential'] ?? true,
            ]);

            // Send notifications
            $this->sendHomeVisitNotifications($homeVisit);

            DB::commit();
            return $homeVisit;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create home visit: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update home visit
     */
    public function updateHomeVisit(HomeVisit $homeVisit, array $data): bool
    {
        DB::beginTransaction();
        
        try {
            $updated = $homeVisit->update([
                'tanggal_kunjungan' => $data['tanggal_kunjungan'] ?? $homeVisit->tanggal_kunjungan,
                'jam_mulai' => $data['jam_mulai'] ?? $homeVisit->jam_mulai,
                'jam_selesai' => $data['jam_selesai'] ?? $homeVisit->jam_selesai,
                'status' => $data['status'] ?? $homeVisit->status,
                'alamat_kunjungan' => $data['alamat_kunjungan'] ?? $homeVisit->alamat_kunjungan,
                'koordinat_lat' => $data['koordinat_lat'] ?? $homeVisit->koordinat_lat,
                'koordinat_lng' => $data['koordinat_lng'] ?? $homeVisit->koordinat_lng,
                'tujuan_kunjungan' => $data['tujuan_kunjungan'] ?? $homeVisit->tujuan_kunjungan,
                'hasil_kunjungan' => $data['hasil_kunjungan'] ?? $homeVisit->hasil_kunjungan,
                'catatan_kunjungan' => $data['catatan_kunjungan'] ?? $homeVisit->catatan_kunjungan,
                'tindak_lanjut' => $data['tindak_lanjut'] ?? $homeVisit->tindak_lanjut,
                'foto_kunjungan' => $data['foto_kunjungan'] ?? $homeVisit->foto_kunjungan,
                'is_urgent' => $data['is_urgent'] ?? $homeVisit->is_urgent,
                'is_confidential' => $data['is_confidential'] ?? $homeVisit->is_confidential,
            ]);

            if ($updated) {
                $this->sendHomeVisitUpdateNotifications($homeVisit);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update home visit: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get BK statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_konseling' => Konseling::count(),
            'konseling_hari_ini' => Konseling::whereDate('tanggal_konseling', now()->toDateString())->count(),
            'konseling_urgent' => Konseling::where('is_urgent', true)->count(),
            'konseling_selesai' => Konseling::where('status', 'selesai')->count(),
            'total_home_visit' => HomeVisit::count(),
            'home_visit_hari_ini' => HomeVisit::whereDate('tanggal_kunjungan', now()->toDateString())->count(),
            'home_visit_urgent' => HomeVisit::where('is_urgent', true)->count(),
            'home_visit_selesai' => HomeVisit::where('status', 'selesai')->count(),
            'by_status_konseling' => Konseling::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_status_home_visit' => HomeVisit::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_jenis_konseling' => Konseling::selectRaw('jenis_konseling, count(*) as count')
                ->groupBy('jenis_konseling')
                ->pluck('count', 'jenis_konseling')
                ->toArray(),
        ];
    }

    /**
     * Get student counseling history
     */
    public function getStudentCounselingHistory(int $siswaId): array
    {
        $konseling = Konseling::where('siswa_id', $siswaId)
            ->with(['konselor'])
            ->orderBy('tanggal_konseling', 'desc')
            ->get();

        $homeVisits = HomeVisit::where('siswa_id', $siswaId)
            ->with(['konselor'])
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();

        return [
            'konseling' => $konseling,
            'home_visits' => $homeVisits,
            'total_sessions' => $konseling->count() + $homeVisits->count(),
            'last_session' => $konseling->first() ?? $homeVisits->first()
        ];
    }

    /**
     * Get counselor workload
     */
    public function getCounselorWorkload(int $konselorId): array
    {
        $konseling = Konseling::where('konselor_id', $konselorId)
            ->where('status', '!=', 'selesai')
            ->count();

        $homeVisits = HomeVisit::where('konselor_id', $konselorId)
            ->where('status', '!=', 'selesai')
            ->count();

        return [
            'active_konseling' => $konseling,
            'active_home_visits' => $homeVisits,
            'total_workload' => $konseling + $homeVisits,
            'urgent_cases' => Konseling::where('konselor_id', $konselorId)
                ->where('is_urgent', true)
                ->where('status', '!=', 'selesai')
                ->count() + HomeVisit::where('konselor_id', $konselorId)
                ->where('is_urgent', true)
                ->where('status', '!=', 'selesai')
                ->count()
        ];
    }

    /**
     * Send counseling notifications
     */
    private function sendKonselingNotifications(Konseling $konseling): void
    {
        // Notify student
        $this->notificationService->sendNotification([
            'user_id' => $konseling->siswa->user_id,
            'judul' => 'Jadwal Konseling',
            'pesan' => "Anda memiliki jadwal konseling pada {$konseling->tanggal_konseling} pukul {$konseling->jam_mulai}.",
            'tipe' => 'info',
            'action_url' => "/dashboard/bimbingan-konseling",
            'action_text' => 'Lihat Detail',
            'data' => [
                'konseling_id' => $konseling->id,
                'tanggal' => $konseling->tanggal_konseling,
                'status' => $konseling->status
            ]
        ]);

        // Notify counselor
        $this->notificationService->sendNotification([
            'user_id' => $konseling->konselor_id,
            'judul' => 'Jadwal Konseling Baru',
            'pesan' => "Anda memiliki jadwal konseling dengan {$konseling->siswa->nama_lengkap} pada {$konseling->tanggal_konseling}.",
            'tipe' => 'info',
            'action_url' => "/dashboard/bimbingan-konseling",
            'action_text' => 'Lihat Detail',
            'data' => [
                'konseling_id' => $konseling->id,
                'siswa_id' => $konseling->siswa_id,
                'tanggal' => $konseling->tanggal_konseling
            ]
        ]);

        // Notify parents if urgent
        if ($konseling->is_urgent && $konseling->siswa->orangTua) {
            $this->notificationService->sendNotification([
                'user_id' => $konseling->siswa->orangTua->user_id ?? null,
                'judul' => 'Konseling Urgent',
                'pesan' => "Anak Anda {$konseling->siswa->nama_lengkap} memerlukan konseling urgent.",
                'tipe' => 'warning',
                'action_url' => "/dashboard/bimbingan-konseling",
                'action_text' => 'Lihat Detail',
                'data' => [
                    'konseling_id' => $konseling->id,
                    'siswa_id' => $konseling->siswa_id,
                    'is_urgent' => true
                ]
            ]);
        }
    }

    /**
     * Send counseling update notifications
     */
    private function sendKonselingUpdateNotifications(Konseling $konseling): void
    {
        $this->notificationService->sendNotification([
            'user_id' => $konseling->siswa->user_id,
            'judul' => 'Konseling Diperbarui',
            'pesan' => "Jadwal konseling Anda telah diperbarui.",
            'tipe' => 'info',
            'action_url' => "/dashboard/bimbingan-konseling",
            'action_text' => 'Lihat Detail',
            'data' => [
                'konseling_id' => $konseling->id,
                'status' => $konseling->status
            ]
        ]);
    }

    /**
     * Send home visit notifications
     */
    private function sendHomeVisitNotifications(HomeVisit $homeVisit): void
    {
        // Notify student
        $this->notificationService->sendNotification([
            'user_id' => $homeVisit->siswa->user_id,
            'judul' => 'Jadwal Home Visit',
            'pesan' => "Akan ada kunjungan rumah pada {$homeVisit->tanggal_kunjungan} pukul {$homeVisit->jam_mulai}.",
            'tipe' => 'info',
            'action_url' => "/dashboard/bimbingan-konseling",
            'action_text' => 'Lihat Detail',
            'data' => [
                'home_visit_id' => $homeVisit->id,
                'tanggal' => $homeVisit->tanggal_kunjungan,
                'status' => $homeVisit->status
            ]
        ]);

        // Notify counselor
        $this->notificationService->sendNotification([
            'user_id' => $homeVisit->konselor_id,
            'judul' => 'Jadwal Home Visit Baru',
            'pesan' => "Anda memiliki jadwal home visit ke {$homeVisit->siswa->nama_lengkap} pada {$homeVisit->tanggal_kunjungan}.",
            'tipe' => 'info',
            'action_url' => "/dashboard/bimbingan-konseling",
            'action_text' => 'Lihat Detail',
            'data' => [
                'home_visit_id' => $homeVisit->id,
                'siswa_id' => $homeVisit->siswa_id,
                'tanggal' => $homeVisit->tanggal_kunjungan
            ]
        ]);
    }

    /**
     * Send home visit update notifications
     */
    private function sendHomeVisitUpdateNotifications(HomeVisit $homeVisit): void
    {
        $this->notificationService->sendNotification([
            'user_id' => $homeVisit->siswa->user_id,
            'judul' => 'Home Visit Diperbarui',
            'pesan' => "Jadwal home visit telah diperbarui.",
            'tipe' => 'info',
            'action_url' => "/dashboard/bimbingan-konseling",
            'action_text' => 'Lihat Detail',
            'data' => [
                'home_visit_id' => $homeVisit->id,
                'status' => $homeVisit->status
            ]
        ]);
    }
}
