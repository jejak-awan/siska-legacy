<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="MataPelajaran",
 *     type="object",
 *     title="Mata Pelajaran",
 *     description="Model untuk mata pelajaran",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="kode", type="string", example="MAT-001"),
 *     @OA\Property(property="nama", type="string", example="Matematika"),
 *     @OA\Property(property="deskripsi", type="string", example="Mata pelajaran matematika untuk semua tingkat"),
 *     @OA\Property(property="kelompok", type="string", enum={"A", "B", "C"}, example="A"),
 *     @OA\Property(property="sub_kelompok", type="string", example="IPA"),
 *     @OA\Property(property="jam_per_minggu", type="integer", example=4),
 *     @OA\Property(property="jam_per_semester", type="integer", example=64),
 *     @OA\Property(property="kkm", type="integer", example=75),
 *     @OA\Property(property="is_wajib", type="boolean", example=true),
 *     @OA\Property(property="is_peminatan", type="boolean", example=false),
 *     @OA\Property(property="tingkat_kelas", type="array", @OA\Items(type="integer"), example={10, 11, 12}),
 *     @OA\Property(property="prasyarat", type="array", @OA\Items(type="string"), example={"MAT-001"}),
 *     @OA\Property(property="guru_id", type="string", example="G001"),
 *     @OA\Property(property="guru_pengampu", type="array", @OA\Items(type="string"), example={"G001", "G002"}),
 *     @OA\Property(property="kurikulum", type="string", example="2013"),
 *     @OA\Property(property="tujuan_pembelajaran", type="string", example="Memahami konsep matematika dasar"),
 *     @OA\Property(property="materi_pokok", type="string", example="Aljabar, Geometri, Statistika"),
 *     @OA\Property(property="metode_pembelajaran", type="array", @OA\Items(type="string"), example={"Ceramah", "Diskusi", "Praktikum"}),
 *     @OA\Property(property="media_pembelajaran", type="array", @OA\Items(type="string"), example={"Papan Tulis", "Proyektor", "Laptop"}),
 *     @OA\Property(property="sumber_belajar", type="array", @OA\Items(type="string"), example={"Buku Paket", "LKS", "Internet"}),
 *     @OA\Property(property="penilaian", type="string", example="Penilaian harian, UTS, UAS"),
 *     @OA\Property(property="status_aktif", type="boolean", example=true),
 *     @OA\Property(property="created_by", type="string", example="U001"),
 *     @OA\Property(property="updated_by", type="string", example="U001"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class MataPelajaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mata_pelajaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'kelompok',
        'sub_kelompok',
        'jam_per_minggu',
        'jam_per_semester',
        'kkm',
        'is_wajib',
        'is_peminatan',
        'tingkat_kelas',
        'prasyarat',
        'guru_id',
        'guru_pengampu',
        'kurikulum',
        'tujuan_pembelajaran',
        'materi_pokok',
        'metode_pembelajaran',
        'media_pembelajaran',
        'sumber_belajar',
        'penilaian',
        'status_aktif',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tingkat_kelas' => 'array',
        'prasyarat' => 'array',
        'guru_pengampu' => 'array',
        'metode_pembelajaran' => 'array',
        'media_pembelajaran' => 'array',
        'sumber_belajar' => 'array',
        'is_wajib' => 'boolean',
        'is_peminatan' => 'boolean',
        'status_aktif' => 'boolean',
        'jam_per_minggu' => 'integer',
        'jam_per_semester' => 'integer',
        'kkm' => 'integer',
    ];

    /**
     * Get the guru pengampu utama.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }

    /**
     * Get all guru pengampu.
     */
    public function guruPengampu(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'mata_pelajaran_guru', 'mata_pelajaran_id', 'guru_id')
                    ->withTimestamps();
    }

    /**
     * Get the user who created this record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated this record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Get jadwal pelajaran for this mata pelajaran.
     */
    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'mata_pelajaran_id');
    }

    /**
     * Get nilai siswa for this mata pelajaran.
     */
    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class, 'mata_pelajaran_id');
    }

    /**
     * Scope a query to only include active mata pelajaran.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope a query to only include wajib mata pelajaran.
     */
    public function scopeWajib($query)
    {
        return $query->where('is_wajib', true);
    }

    /**
     * Scope a query to only include peminatan mata pelajaran.
     */
    public function scopePeminatan($query)
    {
        return $query->where('is_peminatan', true);
    }

    /**
     * Scope a query to filter by kelompok.
     */
    public function scopeByKelompok($query, $kelompok)
    {
        return $query->where('kelompok', $kelompok);
    }

    /**
     * Scope a query to filter by tingkat kelas.
     */
    public function scopeByTingkatKelas($query, $tingkat)
    {
        return $query->whereJsonContains('tingkat_kelas', $tingkat);
    }

    /**
     * Scope a query to filter by guru.
     */
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId)
                    ->orWhereJsonContains('guru_pengampu', $guruId);
    }

    /**
     * Get the kelompok display name.
     */
    public function getKelompokDisplayNameAttribute(): string
    {
        $kelompokNames = [
            'A' => 'Kelompok A (Wajib)',
            'B' => 'Kelompok B (Wajib)',
            'C' => 'Kelompok C (Peminatan)',
        ];

        return $kelompokNames[$this->kelompok] ?? $this->kelompok;
    }

    /**
     * Get the sub kelompok display name.
     */
    public function getSubKelompokDisplayNameAttribute(): string
    {
        $subKelompokNames = [
            'IPA' => 'Ilmu Pengetahuan Alam',
            'IPS' => 'Ilmu Pengetahuan Sosial',
            'Bahasa' => 'Bahasa dan Sastra',
            'Agama' => 'Pendidikan Agama',
            'Olahraga' => 'Pendidikan Jasmani',
            'Seni' => 'Seni dan Budaya',
        ];

        return $subKelompokNames[$this->sub_kelompok] ?? $this->sub_kelompok;
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayNameAttribute(): string
    {
        return $this->status_aktif ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get the tingkat kelas display.
     */
    public function getTingkatKelasDisplayAttribute(): string
    {
        if (!$this->tingkat_kelas) {
            return 'Semua Tingkat';
        }

        return implode(', ', array_map(fn($tingkat) => "Kelas $tingkat", $this->tingkat_kelas));
    }

    /**
     * Get the guru pengampu display.
     */
    public function getGuruPengampuDisplayAttribute(): string
    {
        if (!$this->guru_pengampu) {
            return 'Belum ditentukan';
        }

        $guruNames = Guru::whereIn('id', $this->guru_pengampu)
                         ->pluck('nama_lengkap')
                         ->toArray();

        return implode(', ', $guruNames);
    }

    /**
     * Get the total jam per semester.
     */
    public function getTotalJamPerSemesterAttribute(): int
    {
        if ($this->jam_per_semester) {
            return $this->jam_per_semester;
        }

        // Calculate based on jam_per_minggu * 16 weeks per semester
        return $this->jam_per_minggu * 16;
    }

    /**
     * Check if mata pelajaran is for specific tingkat.
     */
    public function isForTingkat(int $tingkat): bool
    {
        if (!$this->tingkat_kelas) {
            return true; // If no specific tingkat, assume all
        }

        return in_array($tingkat, $this->tingkat_kelas);
    }

    /**
     * Check if guru is pengampu for this mata pelajaran.
     */
    public function isGuruPengampu(string $guruId): bool
    {
        if ($this->guru_id === $guruId) {
            return true;
        }

        if (!$this->guru_pengampu) {
            return false;
        }

        return in_array($guruId, $this->guru_pengampu);
    }

    /**
     * Get mata pelajaran statistics.
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'active' => self::active()->count(),
            'inactive' => self::where('status_aktif', false)->count(),
            'wajib' => self::wajib()->count(),
            'peminatan' => self::peminatan()->count(),
            'by_kelompok' => self::selectRaw('kelompok, count(*) as count')
                ->groupBy('kelompok')
                ->get()
                ->pluck('count', 'kelompok'),
            'by_sub_kelompok' => self::selectRaw('sub_kelompok, count(*) as count')
                ->whereNotNull('sub_kelompok')
                ->groupBy('sub_kelompok')
                ->get()
                ->pluck('count', 'sub_kelompok'),
            'total_jam_per_minggu' => self::active()->sum('jam_per_minggu'),
            'average_jam_per_minggu' => self::active()->avg('jam_per_minggu'),
        ];
    }
}