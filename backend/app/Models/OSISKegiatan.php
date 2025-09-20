<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OSISKegiatan extends Model
{
    use HasFactory;

    protected $table = 'osis_kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'jenis_kegiatan',
        'status',
        'tempat_kegiatan',
        'tujuan_kegiatan',
        'sasaran_kegiatan',
        'anggaran',
        'sumber_dana',
        'penanggung_jawab',
        'peserta',
        'panitia',
        'keterangan',
        'foto_kegiatan',
        'is_aktif',
        'is_urgent'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'anggaran' => 'decimal:2',
        'peserta' => 'array',
        'panitia' => 'array',
        'is_aktif' => 'boolean',
        'is_urgent' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_kegiatan', $jenis);
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_mulai', [$startDate, $endDate]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_selesai', '>=', now());
    }

    // Accessors
    public function getJenisKegiatanLabelAttribute(): string
    {
        $labels = [
            'akademik' => 'Akademik',
            'non_akademik' => 'Non Akademik',
            'sosial' => 'Sosial',
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'keagamaan' => 'Keagamaan'
        ];
        return $labels[$this->jenis_kegiatan] ?? $this->jenis_kegiatan;
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'perencanaan' => 'Perencanaan',
            'persiapan' => 'Persiapan',
            'berlangsung' => 'Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'perencanaan' => 'blue',
            'persiapan' => 'yellow',
            'berlangsung' => 'green',
            'selesai' => 'gray',
            'dibatalkan' => 'red'
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getJenisKegiatanColorAttribute(): string
    {
        $colors = [
            'akademik' => 'blue',
            'non_akademik' => 'green',
            'sosial' => 'purple',
            'olahraga' => 'orange',
            'seni' => 'pink',
            'keagamaan' => 'indigo'
        ];
        return $colors[$this->jenis_kegiatan] ?? 'gray';
    }

    public function getDurasiKegiatanAttribute(): string
    {
        $tanggalMulai = \Carbon\Carbon::parse($this->tanggal_mulai);
        $tanggalSelesai = \Carbon\Carbon::parse($this->tanggal_selesai);
        $durasi = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
        
        if ($durasi == 1) {
            return '1 hari';
        }
        
        return $durasi . ' hari';
    }

    public function getAnggaranFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->anggaran, 0, ',', '.');
    }

    public function getPesertaCountAttribute(): int
    {
        return is_array($this->peserta) ? count($this->peserta) : 0;
    }

    public function getPanitiaCountAttribute(): int
    {
        return is_array($this->panitia) ? count($this->panitia) : 0;
    }
}