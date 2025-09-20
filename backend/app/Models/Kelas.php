<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'rombel',
        'kapasitas',
        'jumlah_siswa',
        'wali_kelas_id',
        'tahun_ajaran_id',
        'status',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tingkat' => 'integer',
        'kapasitas' => 'integer',
        'jumlah_siswa' => 'integer',
    ];

    /**
     * Get the wali kelas for this kelas.
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /**
     * Get the tahun ajaran for this kelas.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Get the siswa in this kelas.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    /**
     * Check if kelas is active.
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    /**
     * Check if kelas is full.
     */
    public function isFull()
    {
        return $this->jumlah_siswa >= $this->kapasitas;
    }

    /**
     * Get available slots.
     */
    public function getAvailableSlotsAttribute()
    {
        return $this->kapasitas - $this->jumlah_siswa;
    }

    /**
     * Get occupancy percentage.
     */
    public function getOccupancyPercentageAttribute()
    {
        return $this->kapasitas > 0 ? round(($this->jumlah_siswa / $this->kapasitas) * 100, 2) : 0;
    }

    /**
     * Get full kelas name with tahun ajaran.
     */
    public function getFullNameAttribute()
    {
        return $this->nama_kelas . ' (' . $this->tahunAjaran->nama . ')';
    }

    /**
     * Update jumlah siswa count.
     */
    public function updateJumlahSiswa()
    {
        $this->jumlah_siswa = $this->siswa()->count();
        $this->save();
        return $this;
    }

    /**
     * Scope to filter active kelas.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope to filter by tingkat.
     */
    public function scopeByTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    /**
     * Scope to filter by jurusan.
     */
    public function scopeByJurusan($query, $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }

    /**
     * Scope to filter by tahun ajaran.
     */
    public function scopeByTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    /**
     * Scope to get kelas with available slots.
     */
    public function scopeWithAvailableSlots($query)
    {
        return $query->whereRaw('jumlah_siswa < kapasitas');
    }

    /**
     * Scope to get full kelas.
     */
    public function scopeFull($query)
    {
        return $query->whereRaw('jumlah_siswa >= kapasitas');
    }
}