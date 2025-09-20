<?php

namespace App\Services;

use App\Models\KreditPoin;
use App\Models\KategoriKreditPoin;
use App\Models\Siswa;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KreditPoinService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create new credit point record
     */
    public function createKreditPoin(array $data): KreditPoin
    {
        DB::beginTransaction();
        
        try {
            // Validate category
            $kategori = KategoriKreditPoin::findOrFail($data['kategori_id']);
            
            // Auto-fill default values
            if (!isset($data['nilai'])) {
                $data['nilai'] = $kategori->nilai_default;
            }

            // Create credit point record
            $kreditPoin = KreditPoin::create([
                'siswa_id' => $data['siswa_id'],
                'kategori_id' => $data['kategori_id'],
                'nilai' => $data['nilai'],
                'deskripsi' => $data['deskripsi'],
                'tanggal' => $data['tanggal'] ?? now()->toDateString(),
                'pelapor_id' => $data['pelapor_id'],
                'status' => 'pending',
                'catatan_admin' => $data['catatan_admin'] ?? null,
            ]);

            // Send notification to admin for approval
            $this->sendApprovalNotification($kreditPoin);

            DB::commit();
            return $kreditPoin;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create kredit poin: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Approve credit point
     */
    public function approveKreditPoin(KreditPoin $kreditPoin, int $approverId, string $catatanAdmin = null): bool
    {
        DB::beginTransaction();
        
        try {
            // Update status
            $kreditPoin->update([
                'status' => 'approved',
                'catatan_admin' => $catatanAdmin,
                'approved_at' => now(),
                'approved_by' => $approverId,
            ]);

            // Send notification to student
            $this->sendApprovalNotificationToStudent($kreditPoin);

            // Send notification to reporter
            $this->sendApprovalNotificationToReporter($kreditPoin);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve kredit poin: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reject credit point
     */
    public function rejectKreditPoin(KreditPoin $kreditPoin, int $rejectorId, string $catatanAdmin): bool
    {
        DB::beginTransaction();
        
        try {
            // Update status
            $kreditPoin->update([
                'status' => 'rejected',
                'catatan_admin' => $catatanAdmin,
                'rejected_at' => now(),
                'rejected_by' => $rejectorId,
            ]);

            // Send notification to student
            $this->sendRejectionNotificationToStudent($kreditPoin);

            // Send notification to reporter
            $this->sendRejectionNotificationToReporter($kreditPoin);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject kredit poin: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get student's total credit points
     */
    public function getStudentTotalPoints(int $siswaId): array
    {
        $approvedPoints = KreditPoin::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->get();

        $totalPositif = $approvedPoints->where('kategori.jenis', 'positif')->sum('nilai');
        $totalNegatif = $approvedPoints->where('kategori.jenis', 'negatif')->sum('nilai');
        $total = $totalPositif - $totalNegatif;

        return [
            'total_positif' => $totalPositif,
            'total_negatif' => $totalNegatif,
            'total' => $total,
            'count' => $approvedPoints->count(),
            'pending_count' => KreditPoin::where('siswa_id', $siswaId)
                ->where('status', 'pending')
                ->count()
        ];
    }

    /**
     * Get credit point statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_kredit_poin' => KreditPoin::count(),
            'pending' => KreditPoin::where('status', 'pending')->count(),
            'approved' => KreditPoin::where('status', 'approved')->count(),
            'rejected' => KreditPoin::where('status', 'rejected')->count(),
            'by_kategori' => KreditPoin::with('kategori')
                ->selectRaw('kategori_id, count(*) as count, sum(nilai) as total_nilai')
                ->groupBy('kategori_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'kategori' => $item->kategori->nama,
                        'count' => $item->count,
                        'total_nilai' => $item->total_nilai
                    ];
                }),
            'by_status' => KreditPoin::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_month' => KreditPoin::selectRaw('strftime("%Y-%m", created_at) as month, count(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray()
        ];
    }

    /**
     * Get top students by credit points
     */
    public function getTopStudents(int $limit = 10): array
    {
        return KreditPoin::where('status', 'approved')
            ->selectRaw('siswa_id, sum(nilai) as total_points')
            ->groupBy('siswa_id')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->with('siswa')
            ->get()
            ->map(function ($item) {
                return [
                    'siswa' => $item->siswa,
                    'total_points' => $item->total_points
                ];
            })
            ->toArray();
    }

    /**
     * Send approval notification to admin
     */
    private function sendApprovalNotification(KreditPoin $kreditPoin): void
    {
        $this->notificationService->sendNotificationByRole([
            'role' => 'admin',
            'judul' => 'Kredit Poin Baru Menunggu Persetujuan',
            'pesan' => "Kredit poin baru dari {$kreditPoin->siswa->nama_lengkap} menunggu persetujuan Anda.",
            'tipe' => 'warning',
            'action_url' => "/dashboard/kredit-poin/{$kreditPoin->id}",
            'action_text' => 'Lihat Detail',
            'data' => [
                'kredit_poin_id' => $kreditPoin->id,
                'siswa_id' => $kreditPoin->siswa_id,
                'nilai' => $kreditPoin->nilai
            ]
        ]);
    }

    /**
     * Send approval notification to student
     */
    private function sendApprovalNotificationToStudent(KreditPoin $kreditPoin): void
    {
        $this->notificationService->sendNotification([
            'user_id' => $kreditPoin->siswa->user_id,
            'judul' => 'Kredit Poin Disetujui',
            'pesan' => "Kredit poin Anda sebesar {$kreditPoin->nilai} poin telah disetujui.",
            'tipe' => 'success',
            'action_url' => "/dashboard/kredit-poin",
            'action_text' => 'Lihat Kredit Poin',
            'data' => [
                'kredit_poin_id' => $kreditPoin->id,
                'nilai' => $kreditPoin->nilai,
                'status' => 'approved'
            ]
        ]);
    }

    /**
     * Send approval notification to reporter
     */
    private function sendApprovalNotificationToReporter(KreditPoin $kreditPoin): void
    {
        if ($kreditPoin->pelapor_id) {
            $this->notificationService->sendNotification([
                'user_id' => $kreditPoin->pelapor_id,
                'judul' => 'Laporan Kredit Poin Disetujui',
                'pesan' => "Laporan kredit poin untuk {$kreditPoin->siswa->nama_lengkap} telah disetujui.",
                'tipe' => 'success',
                'action_url' => "/dashboard/kredit-poin",
                'action_text' => 'Lihat Detail',
                'data' => [
                    'kredit_poin_id' => $kreditPoin->id,
                    'siswa_id' => $kreditPoin->siswa_id,
                    'status' => 'approved'
                ]
            ]);
        }
    }

    /**
     * Send rejection notification to student
     */
    private function sendRejectionNotificationToStudent(KreditPoin $kreditPoin): void
    {
        $this->notificationService->sendNotification([
            'user_id' => $kreditPoin->siswa->user_id,
            'judul' => 'Kredit Poin Ditolak',
            'pesan' => "Kredit poin Anda sebesar {$kreditPoin->nilai} poin telah ditolak. Alasan: {$kreditPoin->catatan_admin}",
            'tipe' => 'error',
            'action_url' => "/dashboard/kredit-poin",
            'action_text' => 'Lihat Detail',
            'data' => [
                'kredit_poin_id' => $kreditPoin->id,
                'nilai' => $kreditPoin->nilai,
                'status' => 'rejected',
                'reason' => $kreditPoin->catatan_admin
            ]
        ]);
    }

    /**
     * Send rejection notification to reporter
     */
    private function sendRejectionNotificationToReporter(KreditPoin $kreditPoin): void
    {
        if ($kreditPoin->pelapor_id) {
            $this->notificationService->sendNotification([
                'user_id' => $kreditPoin->pelapor_id,
                'judul' => 'Laporan Kredit Poin Ditolak',
                'pesan' => "Laporan kredit poin untuk {$kreditPoin->siswa->nama_lengkap} telah ditolak. Alasan: {$kreditPoin->catatan_admin}",
                'tipe' => 'error',
                'action_url' => "/dashboard/kredit-poin",
                'action_text' => 'Lihat Detail',
                'data' => [
                    'kredit_poin_id' => $kreditPoin->id,
                    'siswa_id' => $kreditPoin->siswa_id,
                    'status' => 'rejected',
                    'reason' => $kreditPoin->catatan_admin
                ]
            ]);
        }
    }

    /**
     * Bulk approve credit points
     */
    public function bulkApprove(array $kreditPoinIds, int $approverId, string $catatanAdmin = null): int
    {
        $count = 0;
        
        foreach ($kreditPoinIds as $id) {
            $kreditPoin = KreditPoin::find($id);
            if ($kreditPoin && $kreditPoin->status === 'pending') {
                if ($this->approveKreditPoin($kreditPoin, $approverId, $catatanAdmin)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    /**
     * Bulk reject credit points
     */
    public function bulkReject(array $kreditPoinIds, int $rejectorId, string $catatanAdmin): int
    {
        $count = 0;
        
        foreach ($kreditPoinIds as $id) {
            $kreditPoin = KreditPoin::find($id);
            if ($kreditPoin && $kreditPoin->status === 'pending') {
                if ($this->rejectKreditPoin($kreditPoin, $rejectorId, $catatanAdmin)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }
}
