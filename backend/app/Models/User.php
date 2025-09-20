<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="username", type="string", example="johndoe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="role_type", type="string", example="guru"),
 *     @OA\Property(property="status", type="string", example="aktif"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="two_factor_enabled", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/Role")),
 *     @OA\Property(property="guru", ref="#/components/schemas/Guru"),
 *     @OA\Property(property="siswa", ref="#/components/schemas/Siswa")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role_type',
        'status',
        'email_verified_at',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
    ];

    /**
     * Get the roles assigned to this user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * Get the guru profile if user is a guru.
     */
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Get the siswa profile if user is a siswa.
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    /**
     * Get the presensi records for this user.
     */
    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * Get the notifications for this user.
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    /**
     * Get the WhatsApp logs for this user.
     */
    public function whatsappLogs()
    {
        return $this->hasMany(WhatsAppLog::class);
    }

    /**
     * Get the kredit poin records reported by this user.
     */
    public function kreditPoinDilaporkan()
    {
        return $this->hasMany(KreditPoin::class, 'pelapor_id');
    }

    /**
     * Get the presensi records validated by this user.
     */
    public function presensiDivalidasi()
    {
        return $this->hasMany(Presensi::class, 'divalidasi_oleh');
    }

    /**
     * Get the konseling records where this user is the counselor.
     */
    public function konselingSebagaiKonselor()
    {
        return $this->hasMany(Konseling::class, 'konselor_id');
    }

    /**
     * Get the home visit records where this user is the counselor.
     */
    public function homeVisitSebagaiKonselor()
    {
        return $this->hasMany(HomeVisit::class, 'konselor_id');
    }

    /**
     * Get the ekstrakurikuler records where this user is the pembina.
     */
    public function ekstrakurikulerSebagaiPembina()
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }

    /**
     * Get the ekstrakurikuler records where this user is the ketua.
     */
    public function ekstrakurikulerSebagaiKetua()
    {
        return $this->hasMany(Ekstrakurikuler::class, 'ketua_id');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists() || $this->role_type === $role;
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    /**
     * Scope to filter active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope to filter by role type.
     */
    public function scopeByRoleType($query, $roleType)
    {
        return $query->where('role_type', $roleType);
    }
}