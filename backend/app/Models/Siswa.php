<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Siswa",
 *     type="object",
 *     title="Siswa",
 *     description="Siswa model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="nisn", type="string", example="1234567890"),
 *     @OA\Property(property="nis", type="string", example="2024001"),
 *     @OA\Property(property="nama_lengkap", type="string", example="Ahmad Fauzi"),
 *     @OA\Property(property="nama_panggilan", type="string", example="Ahmad"),
 *     @OA\Property(property="jenis_kelamin", type="string", example="Laki-laki"),
 *     @OA\Property(property="tempat_lahir", type="string", example="Jakarta"),
 *     @OA\Property(property="tanggal_lahir", type="string", format="date", example="2005-01-01"),
 *     @OA\Property(property="agama", type="string", example="Islam"),
 *     @OA\Property(property="kewarganegaraan", type="string", example="Indonesia"),
 *     @OA\Property(property="nik", type="string", example="1234567890123456"),
 *     @OA\Property(property="alamat_lengkap", type="string", example="Jl. Contoh No. 123"),
 *     @OA\Property(property="nomor_hp", type="string", example="081234567890"),
 *     @OA\Property(property="email", type="string", format="email", example="ahmad@example.com"),
 *     @OA\Property(property="kelas_id", type="integer", example=1),
 *     @OA\Property(property="tahun_ajaran_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="aktif"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Siswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'kewarganegaraan',
        'nik',
        'no_kk',
        'no_akta_kelahiran',
        'anak_ke',
        'jumlah_saudara',
        'bahasa_sehari_hari',
        'tinggi_badan',
        'berat_badan',
        'golongan_darah',
        'riwayat_penyakit',
        'kelainan_fisik',
        'alamat_lengkap',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'kode_pos',
        'nomor_hp_siswa',
        'email_siswa',
        'kelas_id',
        'kelas',
        'jurusan',
        'angkatan',
        'tahun_ajaran_id',
        'asal_sekolah',
        'tanggal_masuk',
        'status_siswa',
        'jarak_ke_sekolah',
        'transportasi',
        'waktu_tempuh',
        'penerima_beasiswa',
        'jenis_beasiswa',
        'penerima_kip',
        'no_kip',
        'penerima_pkh',
        'hobi',
        'cita_cita',
        'prestasi',
        'ekstrakurikuler',
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
        'anak_ke' => 'integer',
        'jumlah_saudara' => 'integer',
        'tinggi_badan' => 'decimal:2',
        'berat_badan' => 'decimal:2',
        'angkatan' => 'integer',
        'waktu_tempuh' => 'integer',
        'jarak_ke_sekolah' => 'decimal:2',
        'riwayat_penyakit' => 'array',
        'hobi' => 'array',
        'cita_cita' => 'array',
        'prestasi' => 'array',
        'ekstrakurikuler' => 'array',
        'penerima_beasiswa' => 'boolean',
        'penerima_kip' => 'boolean',
        'penerima_pkh' => 'boolean',
        'status_aktif' => 'boolean',
    ];

    /**
     * Get the user that owns the siswa profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kelas that this siswa belongs to.
     */
    public function kelasRelation()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the tahun ajaran for this siswa.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Get the orang tua for this siswa.
     */
    public function orangTua()
    {
        return $this->hasOne(OrangTua::class);
    }

    /**
     * Get the kredit poin records for this siswa.
     */
    public function kreditPoin()
    {
        return $this->hasMany(KreditPoin::class);
    }

    /**
     * Get the konseling records for this siswa.
     */
    public function konseling()
    {
        return $this->hasMany(Konseling::class);
    }

    /**
     * Get the home visit records for this siswa.
     */
    public function homeVisit()
    {
        return $this->hasMany(HomeVisit::class);
    }

    /**
     * Get the ekstrakurikuler records for this siswa.
     */
    public function ekstrakurikuler()
    {
        return $this->belongsToMany(Ekstrakurikuler::class, 'ekstrakurikuler_siswa')
                    ->withPivot(['tanggal_daftar', 'status', 'alasan_keluar', 'catatan', 'is_aktif'])
                    ->withTimestamps();
    }

    /**
     * Check if siswa is active.
     */
    public function isActive()
    {
        return $this->status_aktif && $this->status_siswa === 'Aktif';
    }

    /**
     * Get wali kelas for this siswa.
     */
    public function getWaliKelas()
    {
        return $this->kelasRelation?->waliKelas;
    }

    /**
     * Get full name.
     */
    public function getFullNameAttribute()
    {
        return $this->nama_lengkap;
    }

    /**
     * Get display name (panggilan or lengkap).
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama_panggilan ?: $this->nama_lengkap;
    }

    /**
     * Check if siswa receives scholarship.
     */
    public function hasScholarship()
    {
        return $this->penerima_beasiswa || $this->penerima_kip;
    }

    /**
     * Scope to filter active siswa.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true)->where('status_siswa', 'Aktif');
    }

    /**
     * Scope to filter by kelas.
     */
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Scope to filter by angkatan.
     */
    public function scopeByAngkatan($query, $angkatan)
    {
        return $query->where('angkatan', $angkatan);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_siswa', $status);
    }
}