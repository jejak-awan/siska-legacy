<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EkstrakurikulerSiswa extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler_siswa';

    protected $fillable = [
        'ekstrakurikuler_id',
        'siswa_id',
        'tanggal_daftar',
        'status',
        'alasan_keluar',
        'catatan',
        'is_aktif'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'is_aktif' => 'boolean'
    ];

    // Relationships
    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByEkstrakurikuler($query, $ekstrakurikulerId)
    {
        return $query->where('ekstrakurikuler_id', $ekstrakurikulerId);
    }

    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_daftar', [$startDate, $endDate]);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'keluar' => 'Keluar'
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'aktif' => 'green',
            'tidak_aktif' => 'yellow',
            'keluar' => 'red'
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getLamaKeanggotaanAttribute(): string
    {
        $tanggalDaftar = \Carbon\Carbon::parse($this->tanggal_daftar);
        $durasi = $tanggalDaftar->diffInDays(now());
        
        if ($durasi < 30) {
            return $durasi . ' hari';
        } elseif ($durasi < 365) {
            $bulan = floor($durasi / 30);
            return $bulan . ' bulan';
        } else {
            $tahun = floor($durasi / 365);
            $bulan = floor(($durasi % 365) / 30);
            return $tahun . ' tahun ' . $bulan . ' bulan';
        }
    }
}