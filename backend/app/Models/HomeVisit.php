<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeVisit extends Model
{
    use HasFactory;

    protected $table = 'home_visit';

    protected $fillable = [
        'siswa_id',
        'konselor_id',
        'tanggal_kunjungan',
        'jam_mulai',
        'jam_selesai',
        'status',
        'tujuan_kunjungan',
        'hasil_kunjungan',
        'catatan_kunjungan',
        'tindak_lanjut',
        'data_keluarga',
        'data_lingkungan',
        'alamat_kunjungan',
        'koordinat_lat',
        'koordinat_lng',
        'foto_kunjungan',
        'is_urgent',
        'is_confidential'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'data_keluarga' => 'array',
        'data_lingkungan' => 'array',
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
        return $query->whereBetween('tanggal_kunjungan', [$startDate, $endDate]);
    }

    // Accessors
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

    public function getDurasiKunjunganAttribute(): string
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

    public function getKoordinatFormattedAttribute(): string
    {
        if ($this->koordinat_lat && $this->koordinat_lng) {
            return $this->koordinat_lat . ', ' . $this->koordinat_lng;
        }
        return 'Tidak tersedia';
    }
}