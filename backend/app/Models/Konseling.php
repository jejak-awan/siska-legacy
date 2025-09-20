<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konseling extends Model
{
    use HasFactory;

    protected $table = 'konseling';

    protected $fillable = [
        'siswa_id',
        'konselor_id',
        'tanggal_konseling',
        'jam_mulai',
        'jam_selesai',
        'jenis_konseling',
        'status',
        'masalah',
        'tujuan_konseling',
        'intervensi',
        'hasil_konseling',
        'tindak_lanjut',
        'catatan_konselor',
        'data_tambahan',
        'tempat_konseling',
        'is_urgent',
        'is_confidential'
    ];

    protected $casts = [
        'tanggal_konseling' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'data_tambahan' => 'array',
        'is_urgent' => 'boolean',
        'is_confidential' => 'boolean'
    ];

    // Relationships
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function konselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'konselor_id');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByKonselor($query, $konselorId)
    {
        return $query->where('konselor_id', $konselorId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_konseling', $jenis);
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeConfidential($query)
    {
        return $query->where('is_confidential', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_konseling', [$startDate, $endDate]);
    }

    // Accessors
    public function getJenisKonselingLabelAttribute(): string
    {
        $labels = [
            'individual' => 'Individual',
            'kelompok' => 'Kelompok',
            'keluarga' => 'Keluarga',
            'krisis' => 'Krisis'
        ];
        return $labels[$this->jenis_konseling] ?? $this->jenis_konseling;
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'terjadwal' => 'Terjadwal',
            'berlangsung' => 'Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'terjadwal' => 'blue',
            'berlangsung' => 'yellow',
            'selesai' => 'green',
            'dibatalkan' => 'red'
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getJenisKonselingColorAttribute(): string
    {
        $colors = [
            'individual' => 'blue',
            'kelompok' => 'green',
            'keluarga' => 'purple',
            'krisis' => 'red'
        ];
        return $colors[$this->jenis_konseling] ?? 'gray';
    }

    public function getDurasiKonselingAttribute(): string
    {
        $jamMulai = \Carbon\Carbon::parse($this->jam_mulai);
        $jamSelesai = \Carbon\Carbon::parse($this->jam_selesai);
        $durasi = $jamMulai->diffInMinutes($jamSelesai);
        
        if ($durasi >= 60) {
            $jam = floor($durasi / 60);
            $menit = $durasi % 60;
            return $jam . ' jam ' . $menit . ' menit';
        }
        
        return $durasi . ' menit';
    }
}