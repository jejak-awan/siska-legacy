<?php

namespace App\Services;

use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerSiswa;
use App\Models\Siswa;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EkstrakurikulerService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create extracurricular activity
     */
    public function createEkstrakurikuler(array $data): Ekstrakurikuler
    {
        DB::beginTransaction();
        
        try {
            // Validate pembina exists and has appropriate role
            $pembina = User::findOrFail($data['pembina_id']);
            if (!$pembina->hasRole(['guru', 'admin'])) {
                throw new \Exception('User is not authorized to be a pembina');
            }

            // Validate ketua exists and has student role
            if (isset($data['ketua_id'])) {
                $ketua = User::findOrFail($data['ketua_id']);
                if (!$ketua->hasRole('siswa')) {
                    throw new \Exception('Ketua must be a student');
                }
            }

            // Create extracurricular activity
            $ekstrakurikuler = Ekstrakurikuler::create([
                'nama_ekskul' => $data['nama_ekskul'],
                'deskripsi' => $data['deskripsi'],
                'jenis_ekskul' => $data['jenis_ekskul'],
                'pembina_id' => $data['pembina_id'],
                'ketua_id' => $data['ketua_id'] ?? null,
                'jadwal_latihan' => $data['jadwal_latihan'] ?? null,
                'lokasi_latihan' => $data['lokasi_latihan'] ?? null,
                'biaya_pendaftaran' => $data['biaya_pendaftaran'] ?? 0,
                'kuota_peserta' => $data['kuota_peserta'] ?? 0,
                'status' => $data['status'] ?? 'aktif',
                'prestasi' => $data['prestasi'] ?? null,
                'fasilitas' => $data['fasilitas'] ?? null,
                'catatan_tambahan' => $data['catatan_tambahan'] ?? null,
            ]);

            // Send notifications
            $this->sendEkstrakurikulerNotifications($ekstrakurikuler);

            DB::commit();
            return $ekstrakurikuler;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create ekstrakurikuler: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update extracurricular activity
     */
    public function updateEkstrakurikuler(Ekstrakurikuler $ekstrakurikuler, array $data): bool
    {
        DB::beginTransaction();
        
        try {
            $updated = $ekstrakurikuler->update([
                'nama_ekskul' => $data['nama_ekskul'] ?? $ekstrakurikuler->nama_ekskul,
                'deskripsi' => $data['deskripsi'] ?? $ekstrakurikuler->deskripsi,
                'jenis_ekskul' => $data['jenis_ekskul'] ?? $ekstrakurikuler->jenis_ekskul,
                'pembina_id' => $data['pembina_id'] ?? $ekstrakurikuler->pembina_id,
                'ketua_id' => $data['ketua_id'] ?? $ekstrakurikuler->ketua_id,
                'jadwal_latihan' => $data['jadwal_latihan'] ?? $ekstrakurikuler->jadwal_latihan,
                'lokasi_latihan' => $data['lokasi_latihan'] ?? $ekstrakurikuler->lokasi_latihan,
                'biaya_pendaftaran' => $data['biaya_pendaftaran'] ?? $ekstrakurikuler->biaya_pendaftaran,
                'kuota_peserta' => $data['kuota_peserta'] ?? $ekstrakurikuler->kuota_peserta,
                'status' => $data['status'] ?? $ekstrakurikuler->status,
                'prestasi' => $data['prestasi'] ?? $ekstrakurikuler->prestasi,
                'fasilitas' => $data['fasilitas'] ?? $ekstrakurikuler->fasilitas,
                'catatan_tambahan' => $data['catatan_tambahan'] ?? $ekstrakurikuler->catatan_tambahan,
            ]);

            if ($updated) {
                $this->sendEkstrakurikulerUpdateNotifications($ekstrakurikuler);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update ekstrakurikuler: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Register student to extracurricular
     */
    public function registerStudent(int $ekstrakurikulerId, int $siswaId, string $alasan = null): EkstrakurikulerSiswa
    {
        DB::beginTransaction();
        
        try {
            // Check if student is already registered
            $existing = EkstrakurikulerSiswa::where('ekstrakurikuler_id', $ekstrakurikulerId)
                ->where('siswa_id', $siswaId)
                ->first();

            if ($existing) {
                throw new \Exception('Student is already registered in this extracurricular');
            }

            // Check quota
            $ekstrakurikuler = Ekstrakurikuler::findOrFail($ekstrakurikulerId);
            $currentCount = EkstrakurikulerSiswa::where('ekstrakurikuler_id', $ekstrakurikulerId)
                ->where('status', 'aktif')
                ->count();

            if ($ekstrakurikuler->kuota_peserta > 0 && $currentCount >= $ekstrakurikuler->kuota_peserta) {
                throw new \Exception('Extracurricular quota is full');
            }

            // Register student
            $registration = EkstrakurikulerSiswa::create([
                'ekstrakurikuler_id' => $ekstrakurikulerId,
                'siswa_id' => $siswaId,
                'tanggal_daftar' => now()->toDateString(),
                'status' => 'aktif',
                'alasan_keluar' => null,
                'catatan' => $alasan,
                'is_aktif' => true,
            ]);

            // Send notifications
            $this->sendRegistrationNotifications($registration);

            DB::commit();
            return $registration;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to register student: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Unregister student from extracurricular
     */
    public function unregisterStudent(int $ekstrakurikulerId, int $siswaId, string $alasanKeluar): bool
    {
        DB::beginTransaction();
        
        try {
            $registration = EkstrakurikulerSiswa::where('ekstrakurikuler_id', $ekstrakurikulerId)
                ->where('siswa_id', $siswaId)
                ->where('status', 'aktif')
                ->first();

            if (!$registration) {
                throw new \Exception('Student is not registered in this extracurricular');
            }

            $updated = $registration->update([
                'status' => 'keluar',
                'alasan_keluar' => $alasanKeluar,
                'is_aktif' => false,
                'tanggal_keluar' => now()->toDateString(),
            ]);

            if ($updated) {
                $this->sendUnregistrationNotifications($registration);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to unregister student: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get extracurricular statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_ekstrakurikuler' => Ekstrakurikuler::count(),
            'ekstrakurikuler_aktif' => Ekstrakurikuler::where('status', 'aktif')->count(),
            'ekstrakurikuler_olahraga' => Ekstrakurikuler::where('jenis_ekskul', 'olahraga')->count(),
            'ekstrakurikuler_seni' => Ekstrakurikuler::where('jenis_ekskul', 'seni')->count(),
            'ekstrakurikuler_akademik' => Ekstrakurikuler::where('jenis_ekskul', 'akademik')->count(),
            'ekstrakurikuler_keagamaan' => Ekstrakurikuler::where('jenis_ekskul', 'keagamaan')->count(),
            'total_peserta' => EkstrakurikulerSiswa::where('status', 'aktif')->count(),
            'by_jenis' => Ekstrakurikuler::selectRaw('jenis_ekskul, count(*) as count')
                ->groupBy('jenis_ekskul')
                ->pluck('count', 'jenis_ekskul')
                ->toArray(),
            'by_status' => Ekstrakurikuler::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'participation_rate' => $this->calculateParticipationRate(),
            'average_participants' => $this->calculateAverageParticipants(),
        ];
    }

    /**
     * Get students in extracurricular
     */
    public function getStudents(int $ekstrakurikulerId): array
    {
        return EkstrakurikulerSiswa::where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->with(['siswa'])
            ->orderBy('tanggal_daftar', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get available extracurriculars for student
     */
    public function getAvailableForStudent(int $siswaId): array
    {
        $registeredIds = EkstrakurikulerSiswa::where('siswa_id', $siswaId)
            ->where('status', 'aktif')
            ->pluck('ekstrakurikuler_id')
            ->toArray();

        return Ekstrakurikuler::where('status', 'aktif')
            ->whereNotIn('id', $registeredIds)
            ->with(['pembina', 'ketua'])
            ->get()
            ->toArray();
    }

    /**
     * Get student's extracurricular activities
     */
    public function getStudentActivities(int $siswaId): array
    {
        return EkstrakurikulerSiswa::where('siswa_id', $siswaId)
            ->with(['ekstrakurikuler.pembina', 'ekstrakurikuler.ketua'])
            ->orderBy('tanggal_daftar', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Calculate participation rate
     */
    private function calculateParticipationRate(): float
    {
        $totalStudents = Siswa::count();
        $participatingStudents = EkstrakurikulerSiswa::where('status', 'aktif')
            ->distinct('siswa_id')
            ->count();

        if ($totalStudents == 0) return 0;

        return round(($participatingStudents / $totalStudents) * 100, 2);
    }

    /**
     * Calculate average participants per extracurricular
     */
    private function calculateAverageParticipants(): float
    {
        $totalEkstrakurikuler = Ekstrakurikuler::count();
        if ($totalEkstrakurikuler == 0) return 0;

        $totalParticipants = EkstrakurikulerSiswa::where('status', 'aktif')->count();
        return round($totalParticipants / $totalEkstrakurikuler, 2);
    }

    /**
     * Send extracurricular notifications
     */
    private function sendEkstrakurikulerNotifications(Ekstrakurikuler $ekstrakurikuler): void
    {
        // Notify students about new extracurricular
        $this->notificationService->sendNotificationByRole([
            'role' => 'siswa',
            'judul' => 'Ekstrakurikuler Baru',
            'pesan' => "Ekstrakurikuler baru '{$ekstrakurikuler->nama_ekskul}' telah dibuka.",
            'tipe' => 'info',
            'action_url' => "/dashboard/ekstrakurikuler",
            'action_text' => 'Lihat Detail',
            'data' => [
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'nama_ekskul' => $ekstrakurikuler->nama_ekskul,
                'jenis_ekskul' => $ekstrakurikuler->jenis_ekskul
            ]
        ]);

        // Notify pembina
        $this->notificationService->sendNotification([
            'user_id' => $ekstrakurikuler->pembina_id,
            'judul' => 'Ekstrakurikuler Dibuat',
            'pesan' => "Ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}' telah dibuat dan Anda ditunjuk sebagai pembina.",
            'tipe' => 'info',
            'action_url' => "/dashboard/ekstrakurikuler",
            'action_text' => 'Lihat Detail',
            'data' => [
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'nama_ekskul' => $ekstrakurikuler->nama_ekskul
            ]
        ]);

        // Notify ketua if assigned
        if ($ekstrakurikuler->ketua_id) {
            $this->notificationService->sendNotification([
                'user_id' => $ekstrakurikuler->ketua_id,
                'judul' => 'Ditunjuk sebagai Ketua',
                'pesan' => "Anda ditunjuk sebagai ketua ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}'.",
                'tipe' => 'success',
                'action_url' => "/dashboard/ekstrakurikuler",
                'action_text' => 'Lihat Detail',
                'data' => [
                    'ekstrakurikuler_id' => $ekstrakurikuler->id,
                    'nama_ekskul' => $ekstrakurikuler->nama_ekskul
                ]
            ]);
        }
    }

    /**
     * Send extracurricular update notifications
     */
    private function sendEkstrakurikulerUpdateNotifications(Ekstrakurikuler $ekstrakurikuler): void
    {
        // Notify registered students
        $registeredStudents = EkstrakurikulerSiswa::where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('status', 'aktif')
            ->with('siswa')
            ->get();

        foreach ($registeredStudents as $registration) {
            $this->notificationService->sendNotification([
                'user_id' => $registration->siswa->user_id,
                'judul' => 'Ekstrakurikuler Diperbarui',
                'pesan' => "Informasi ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}' telah diperbarui.",
                'tipe' => 'info',
                'action_url' => "/dashboard/ekstrakurikuler",
                'action_text' => 'Lihat Detail',
                'data' => [
                    'ekstrakurikuler_id' => $ekstrakurikuler->id,
                    'nama_ekskul' => $ekstrakurikuler->nama_ekskul
                ]
            ]);
        }
    }

    /**
     * Send registration notifications
     */
    private function sendRegistrationNotifications(EkstrakurikulerSiswa $registration): void
    {
        $ekstrakurikuler = $registration->ekstrakurikuler;
        $siswa = $registration->siswa;

        // Notify student
        $this->notificationService->sendNotification([
            'user_id' => $siswa->user_id,
            'judul' => 'Pendaftaran Berhasil',
            'pesan' => "Pendaftaran Anda ke ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}' berhasil.",
            'tipe' => 'success',
            'action_url' => "/dashboard/ekstrakurikuler",
            'action_text' => 'Lihat Detail',
            'data' => [
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'nama_ekskul' => $ekstrakurikuler->nama_ekskul
            ]
        ]);

        // Notify pembina
        $this->notificationService->sendNotification([
            'user_id' => $ekstrakurikuler->pembina_id,
            'judul' => 'Peserta Baru',
            'pesan' => "{$siswa->nama_lengkap} telah mendaftar ke ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}'.",
            'tipe' => 'info',
            'action_url' => "/dashboard/ekstrakurikuler",
            'action_text' => 'Lihat Detail',
            'data' => [
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'siswa_id' => $siswa->id,
                'nama_siswa' => $siswa->nama_lengkap
            ]
        ]);
    }

    /**
     * Send unregistration notifications
     */
    private function sendUnregistrationNotifications(EkstrakurikulerSiswa $registration): void
    {
        $ekstrakurikuler = $registration->ekstrakurikuler;
        $siswa = $registration->siswa;

        // Notify student
        $this->notificationService->sendNotification([
            'user_id' => $siswa->user_id,
            'judul' => 'Keluar dari Ekstrakurikuler',
            'pesan' => "Anda telah keluar dari ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}'.",
            'tipe' => 'info',
            'action_url' => "/dashboard/ekstrakurikuler",
            'action_text' => 'Lihat Detail',
            'data' => [
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'nama_ekskul' => $ekstrakurikuler->nama_ekskul,
                'alasan_keluar' => $registration->alasan_keluar
            ]
        ]);

        // Notify pembina
        $this->notificationService->sendNotification([
            'user_id' => $ekstrakurikuler->pembina_id,
            'judul' => 'Peserta Keluar',
            'pesan' => "{$siswa->nama_lengkap} telah keluar dari ekstrakurikuler '{$ekstrakurikuler->nama_ekskul}'.",
            'tipe' => 'warning',
            'action_url' => "/dashboard/ekstrakurikuler",
            'action_text' => 'Lihat Detail',
            'data' => [
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'siswa_id' => $siswa->id,
                'nama_siswa' => $siswa->nama_lengkap,
                'alasan_keluar' => $registration->alasan_keluar
            ]
        ]);
    }

    /**
     * Bulk update extracurriculars
     */
    public function bulkUpdateEkstrakurikuler(array $ekstrakurikulerIds, array $data): int
    {
        $count = 0;
        
        foreach ($ekstrakurikulerIds as $id) {
            $ekstrakurikuler = Ekstrakurikuler::find($id);
            if ($ekstrakurikuler) {
                if ($this->updateEkstrakurikuler($ekstrakurikuler, $data)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    /**
     * Generate extracurricular report
     */
    public function generateEkstrakurikulerReport(array $filters = []): array
    {
        $query = Ekstrakurikuler::with(['pembina', 'ketua']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['jenis_ekskul'])) {
            $query->where('jenis_ekskul', $filters['jenis_ekskul']);
        }

        return $query->orderBy('nama_ekskul')->get()->toArray();
    }
}
