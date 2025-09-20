<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalPresensi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_presensi';

    protected $fillable = [
        'nama_jadwal',
        'jenis',
        'jam_mulai',
        'jam_selesai',
        'hari_aktif',
        'kelas_id',
        'tahun_ajaran_id',
        'is_aktif',
        'keterangan'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
        'hari_aktif' => 'array',
        'is_aktif' => 'boolean',
    ];

    // Relationships
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    // Accessors & Mutators
    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'masuk' => 'Jam Masuk',
            'pulang' => 'Jam Pulang',
            'istirahat' => 'Jam Istirahat',
            'kegiatan' => 'Jam Kegiatan',
            default => 'Tidak Diketahui'
        };
    }

    public function getHariAktifLabelAttribute(): string
    {
        if (!$this->hari_aktif) {
            return 'Semua Hari';
        }

        $hariLabels = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu'
        ];

        return collect($this->hari_aktif)
            ->map(fn($hari) => $hariLabels[$hari] ?? $hari)
            ->join(', ');
    }
}
