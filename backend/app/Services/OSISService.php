<?php

namespace App\Services;

use App\Models\OSISKegiatan;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OSISService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create OSIS activity
     */
    public function createKegiatan(array $data): OSISKegiatan
    {
        DB::beginTransaction();
        
        try {
            // Validate penanggung jawab exists and has OSIS role
            $penanggungJawab = User::findOrFail($data['penanggung_jawab_id']);
            if (!$penanggungJawab->hasRole('osis')) {
                throw new \Exception('User is not an OSIS member');
            }

            // Create OSIS activity
            $kegiatan = OSISKegiatan::create([
                'nama_kegiatan' => $data['nama_kegiatan'],
                'deskripsi' => $data['deskripsi'],
                'jenis_kegiatan' => $data['jenis_kegiatan'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'jam_mulai' => $data['jam_mulai'] ?? null,
                'jam_selesai' => $data['jam_selesai'] ?? null,
                'lokasi' => $data['lokasi'],
                'penanggung_jawab_id' => $data['penanggung_jawab_id'],
                'status' => $data['status'] ?? 'perencanaan',
                'anggaran' => $data['anggaran'] ?? 0,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'jumlah_peserta' => $data['jumlah_peserta'] ?? 0,
                'catatan_evaluasi' => $data['catatan_evaluasi'] ?? null,
                'foto_dokumentasi' => $data['foto_dokumentasi'] ?? null,
                'data_tambahan' => $data['data_tambahan'] ?? null,
            ]);

            // Send notifications
            $this->sendKegiatanNotifications($kegiatan);

            DB::commit();
            return $kegiatan;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create OSIS kegiatan: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update OSIS activity
     */
    public function updateKegiatan(OSISKegiatan $kegiatan, array $data): bool
    {
        DB::beginTransaction();
        
        try {
            $updated = $kegiatan->update([
                'nama_kegiatan' => $data['nama_kegiatan'] ?? $kegiatan->nama_kegiatan,
                'deskripsi' => $data['deskripsi'] ?? $kegiatan->deskripsi,
                'jenis_kegiatan' => $data['jenis_kegiatan'] ?? $kegiatan->jenis_kegiatan,
                'tanggal_mulai' => $data['tanggal_mulai'] ?? $kegiatan->tanggal_mulai,
                'tanggal_selesai' => $data['tanggal_selesai'] ?? $kegiatan->tanggal_selesai,
                'jam_mulai' => $data['jam_mulai'] ?? $kegiatan->jam_mulai,
                'jam_selesai' => $data['jam_selesai'] ?? $kegiatan->jam_selesai,
                'lokasi' => $data['lokasi'] ?? $kegiatan->lokasi,
                'penanggung_jawab_id' => $data['penanggung_jawab_id'] ?? $kegiatan->penanggung_jawab_id,
                'status' => $data['status'] ?? $kegiatan->status,
                'anggaran' => $data['anggaran'] ?? $kegiatan->anggaran,
                'sumber_dana' => $data['sumber_dana'] ?? $kegiatan->sumber_dana,
                'jumlah_peserta' => $data['jumlah_peserta'] ?? $kegiatan->jumlah_peserta,
                'catatan_evaluasi' => $data['catatan_evaluasi'] ?? $kegiatan->catatan_evaluasi,
                'foto_dokumentasi' => $data['foto_dokumentasi'] ?? $kegiatan->foto_dokumentasi,
                'data_tambahan' => $data['data_tambahan'] ?? $kegiatan->data_tambahan,
            ]);

            if ($updated) {
                $this->sendKegiatanUpdateNotifications($kegiatan);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update OSIS kegiatan: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get OSIS statistics
     */
    public function getStatistics(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');

        return [
            'total_kegiatan' => OSISKegiatan::count(),
            'kegiatan_hari_ini' => OSISKegiatan::whereDate('tanggal_mulai', $today)->count(),
            'kegiatan_minggu_ini' => OSISKegiatan::whereBetween('tanggal_mulai', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString()
            ])->count(),
            'kegiatan_bulan_ini' => OSISKegiatan::whereRaw('strftime("%Y-%m", tanggal_mulai) = ?', [$thisMonth])->count(),
            'kegiatan_urgent' => OSISKegiatan::where('tanggal_mulai', '<=', now()->addDays(3)->toDateString())
                ->where('status', '!=', 'selesai')
                ->count(),
            'kegiatan_aktif' => OSISKegiatan::whereIn('status', ['perencanaan', 'persiapan', 'berlangsung'])->count(),
            'kegiatan_selesai' => OSISKegiatan::where('status', 'selesai')->count(),
            'kegiatan_berlangsung' => OSISKegiatan::where('status', 'berlangsung')->count(),
            'by_status' => OSISKegiatan::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_jenis' => OSISKegiatan::selectRaw('jenis_kegiatan, count(*) as count')
                ->groupBy('jenis_kegiatan')
                ->pluck('count', 'jenis_kegiatan')
                ->toArray(),
            'by_month' => OSISKegiatan::selectRaw('strftime("%Y-%m", tanggal_mulai) as month, count(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray(),
            'total_anggaran' => OSISKegiatan::sum('anggaran'),
            'total_peserta' => OSISKegiatan::sum('jumlah_peserta'),
        ];
    }

    /**
     * Get upcoming activities
     */
    public function getUpcomingActivities(int $days = 7): array
    {
        return OSISKegiatan::where('tanggal_mulai', '>', now()->toDateString())
            ->where('tanggal_mulai', '<=', now()->addDays($days)->toDateString())
            ->whereIn('status', ['perencanaan', 'persiapan'])
            ->with(['penanggungJawab'])
            ->orderBy('tanggal_mulai')
            ->get()
            ->toArray();
    }

    /**
     * Get ongoing activities
     */
    public function getOngoingActivities(): array
    {
        return OSISKegiatan::where('status', 'berlangsung')
            ->orWhere(function ($query) {
                $query->where('tanggal_mulai', '<=', now()->toDateString())
                    ->where('tanggal_selesai', '>=', now()->toDateString())
                    ->where('status', 'persiapan');
            })
            ->with(['penanggungJawab'])
            ->orderBy('tanggal_mulai')
            ->get()
            ->toArray();
    }

    /**
     * Get activities by status
     */
    public function getActivitiesByStatus(string $status): array
    {
        return OSISKegiatan::where('status', $status)
            ->with(['penanggungJawab'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get activities by type
     */
    public function getActivitiesByType(string $jenis): array
    {
        return OSISKegiatan::where('jenis_kegiatan', $jenis)
            ->with(['penanggungJawab'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get activities by date range
     */
    public function getActivitiesByDateRange(string $startDate, string $endDate): array
    {
        return OSISKegiatan::whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->with(['penanggungJawab'])
            ->orderBy('tanggal_mulai')
            ->get()
            ->toArray();
    }

    /**
     * Get activity performance metrics
     */
    public function getActivityPerformance(): array
    {
        $totalKegiatan = OSISKegiatan::count();
        $selesaiKegiatan = OSISKegiatan::where('status', 'selesai')->count();
        $dibatalkanKegiatan = OSISKegiatan::where('status', 'dibatalkan')->count();

        return [
            'total_kegiatan' => $totalKegiatan,
            'selesai_kegiatan' => $selesaiKegiatan,
            'dibatalkan_kegiatan' => $dibatalkanKegiatan,
            'success_rate' => $totalKegiatan > 0 ? round(($selesaiKegiatan / $totalKegiatan) * 100, 2) : 0,
            'cancellation_rate' => $totalKegiatan > 0 ? round(($dibatalkanKegiatan / $totalKegiatan) * 100, 2) : 0,
            'average_participants' => $totalKegiatan > 0 ? round(OSISKegiatan::avg('jumlah_peserta'), 2) : 0,
            'total_budget_used' => OSISKegiatan::where('status', 'selesai')->sum('anggaran'),
            'budget_efficiency' => $this->calculateBudgetEfficiency(),
        ];
    }

    /**
     * Calculate budget efficiency
     */
    private function calculateBudgetEfficiency(): float
    {
        $totalBudget = OSISKegiatan::sum('anggaran');
        $usedBudget = OSISKegiatan::where('status', 'selesai')->sum('anggaran');
        
        if ($totalBudget == 0) return 0;
        
        return round(($usedBudget / $totalBudget) * 100, 2);
    }

    /**
     * Send activity notifications
     */
    private function sendKegiatanNotifications(OSISKegiatan $kegiatan): void
    {
        // Notify OSIS members
        $this->notificationService->sendNotificationByRole([
            'role' => 'osis',
            'judul' => 'Kegiatan OSIS Baru',
            'pesan' => "Kegiatan baru '{$kegiatan->nama_kegiatan}' telah dibuat.",
            'tipe' => 'info',
            'action_url' => "/dashboard/osis",
            'action_text' => 'Lihat Detail',
            'data' => [
                'kegiatan_id' => $kegiatan->id,
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'tanggal_mulai' => $kegiatan->tanggal_mulai
            ]
        ]);

        // Notify admin
        $this->notificationService->sendNotificationByRole([
            'role' => 'admin',
            'judul' => 'Kegiatan OSIS Baru',
            'pesan' => "Kegiatan OSIS '{$kegiatan->nama_kegiatan}' telah dibuat oleh {$kegiatan->penanggungJawab->name}.",
            'tipe' => 'info',
            'action_url' => "/dashboard/osis",
            'action_text' => 'Lihat Detail',
            'data' => [
                'kegiatan_id' => $kegiatan->id,
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'penanggung_jawab' => $kegiatan->penanggungJawab->name
            ]
        ]);

        // Notify students about upcoming activities
        if ($kegiatan->tanggal_mulai <= now()->addDays(7)->toDateString()) {
            $this->notificationService->sendNotificationByRole([
                'role' => 'siswa',
                'judul' => 'Kegiatan OSIS Mendatang',
                'pesan' => "Akan ada kegiatan OSIS '{$kegiatan->nama_kegiatan}' pada {$kegiatan->tanggal_mulai}.",
                'tipe' => 'info',
                'action_url' => "/dashboard/osis",
                'action_text' => 'Lihat Detail',
                'data' => [
                    'kegiatan_id' => $kegiatan->id,
                    'nama_kegiatan' => $kegiatan->nama_kegiatan,
                    'tanggal_mulai' => $kegiatan->tanggal_mulai
                ]
            ]);
        }
    }

    /**
     * Send activity update notifications
     */
    private function sendKegiatanUpdateNotifications(OSISKegiatan $kegiatan): void
    {
        $this->notificationService->sendNotificationByRole([
            'role' => 'osis',
            'judul' => 'Kegiatan OSIS Diperbarui',
            'pesan' => "Kegiatan '{$kegiatan->nama_kegiatan}' telah diperbarui.",
            'tipe' => 'info',
            'action_url' => "/dashboard/osis",
            'action_text' => 'Lihat Detail',
            'data' => [
                'kegiatan_id' => $kegiatan->id,
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'status' => $kegiatan->status
            ]
        ]);
    }

    /**
     * Bulk update activities
     */
    public function bulkUpdateActivities(array $kegiatanIds, array $data): int
    {
        $count = 0;
        
        foreach ($kegiatanIds as $id) {
            $kegiatan = OSISKegiatan::find($id);
            if ($kegiatan) {
                if ($this->updateKegiatan($kegiatan, $data)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    /**
     * Generate activity report
     */
    public function generateActivityReport(array $filters = []): array
    {
        $query = OSISKegiatan::with(['penanggungJawab']);

        if (isset($filters['start_date'])) {
            $query->whereDate('tanggal_mulai', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('tanggal_selesai', '<=', $filters['end_date']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['jenis_kegiatan'])) {
            $query->where('jenis_kegiatan', $filters['jenis_kegiatan']);
        }

        return $query->orderBy('tanggal_mulai', 'desc')->get()->toArray();
    }
}
