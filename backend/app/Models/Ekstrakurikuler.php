<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ekstrakurikuler extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler';

    protected $fillable = [
        'nama_ekstrakurikuler',
        'deskripsi',
        'jenis',
        'status',
        'pembina_id',
        'ketua_id',
        'jadwal_latihan',
        'tempat_latihan',
        'persyaratan_anggota',
        'kuota_anggota',
        'biaya_pendaftaran',
        'fasilitas',
        'prestasi',
        'foto_ekstrakurikuler',
        'is_aktif'
    ];

    protected $casts = [
        'biaya_pendaftaran' => 'decimal:2',
        'kuota_anggota' => 'integer',
        'is_aktif' => 'boolean'
    ];

    // Relationships
    public function pembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'ekstrakurikuler_siswa')
                    ->withPivot(['tanggal_daftar', 'status', 'alasan_keluar', 'catatan', 'is_aktif'])
                    ->withTimestamps();
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

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeByPembina($query, $pembinaId)
    {
        return $query->where('pembina_id', $pembinaId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'aktif')
                    ->where('is_aktif', true);
    }

    // Accessors
    public function getJenisLabelAttribute(): string
    {
        $labels = [
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'akademik' => 'Akademik',
            'keagamaan' => 'Keagamaan',
            'keterampilan' => 'Keterampilan',
            'sosial' => 'Sosial'
        ];
        return $labels[$this->jenis] ?? $this->jenis;
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'ditutup' => 'Ditutup'
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'aktif' => 'green',
            'tidak_aktif' => 'yellow',
            'ditutup' => 'red'
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getJenisColorAttribute(): string
    {
        $colors = [
            'olahraga' => 'orange',
            'seni' => 'pink',
            'akademik' => 'blue',
            'keagamaan' => 'indigo',
            'keterampilan' => 'purple',
            'sosial' => 'green'
        ];
        return $colors[$this->jenis] ?? 'gray';
    }

    public function getBiayaPendaftaranFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya_pendaftaran, 0, ',', '.');
    }

    public function getJumlahAnggotaAttribute(): int
    {
        return $this->siswa()->wherePivot('status', 'aktif')->count();
    }

    public function getKuotaTersisaAttribute(): int
    {
        if (!$this->kuota_anggota) {
            return 999; // Unlimited
        }
        
        return $this->kuota_anggota - $this->jumlah_anggota;
    }

    public function getKuotaTersisaLabelAttribute(): string
    {
        if (!$this->kuota_anggota) {
            return 'Tidak terbatas';
        }
        
        $tersisa = $this->kuota_tersisa;
        if ($tersisa <= 0) {
            return 'Penuh';
        }
        
        return $tersisa . ' slot tersisa';
    }

    public function getKuotaTersisaColorAttribute(): string
    {
        if (!$this->kuota_anggota) {
            return 'green';
        }
        
        $tersisa = $this->kuota_tersisa;
        if ($tersisa <= 0) {
            return 'red';
        } elseif ($tersisa <= 5) {
            return 'yellow';
        }
        
        return 'green';
    }
}