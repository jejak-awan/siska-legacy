<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guru';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nip',
        'nuptk',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_pernikahan',
        'alamat_lengkap',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'kode_pos',
        'nomor_hp',
        'email',
        'nomor_hp_darurat',
        'jenis_ptk',
        'status_kepegawaian',
        'golongan',
        'jabatan',
        'unit_kerja',
        'tanggal_masuk',
        'tanggal_keluar',
        'masa_kerja',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'nama_universitas',
        'tahun_lulus',
        'no_ijazah',
        'mata_pelajaran',
        'kelas_yang_diajar',
        'is_wali_kelas',
        'kelas_wali',
        'status_sertifikasi',
        'no_sertifikat_pendidik',
        'tanggal_sertifikasi',
        'lembaga_sertifikasi',
        'sertifikat_lainnya',
        'is_konselor_bk',
        'is_pembina_osis',
        'ekstrakurikuler',
        'tugas_tambahan',
        'status_aktif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'tanggal_sertifikasi' => 'date',
        'tahun_lulus' => 'integer',
        'masa_kerja' => 'integer',
        'kelas_yang_diajar' => 'array',
        'sertifikat_lainnya' => 'array',
        'ekstrakurikuler' => 'array',
        'tugas_tambahan' => 'array',
        'is_wali_kelas' => 'boolean',
        'is_konselor_bk' => 'boolean',
        'is_pembina_osis' => 'boolean',
        'status_aktif' => 'boolean',
    ];

    /**
     * Get the user that owns the guru profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kelas that this guru is wali kelas of.
     */
    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    /**
     * Check if guru is active.
     */
    public function isActive()
    {
        return $this->status_aktif;
    }

    /**
     * Check if guru is wali kelas.
     */
    public function isWaliKelas()
    {
        return $this->is_wali_kelas;
    }

    /**
     * Check if guru is konselor BK.
     */
    public function isKonselorBK()
    {
        return $this->is_konselor_bk;
    }

    /**
     * Check if guru is pembina OSIS.
     */
    public function isPembinaOSIS()
    {
        return $this->is_pembina_osis;
    }

    /**
     * Get full name with title.
     */
    public function getFullNameAttribute()
    {
        return $this->nama_lengkap;
    }

    /**
     * Scope to filter active guru.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope to filter wali kelas.
     */
    public function scopeWaliKelas($query)
    {
        return $query->where('is_wali_kelas', true);
    }

    /**
     * Scope to filter konselor BK.
     */
    public function scopeKonselorBK($query)
    {
        return $query->where('is_konselor_bk', true);
    }

    /**
     * Scope to filter pembina OSIS.
     */
    public function scopePembinaOSIS($query)
    {
        return $query->where('is_pembina_osis', true);
    }
}