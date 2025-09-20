<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tahun_ajaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'tahun_mulai',
        'tahun_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_mulai' => 'integer',
        'tahun_selesai' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif' => 'boolean',
    ];

    /**
     * Get the kelas for this tahun ajaran.
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    /**
     * Get the siswa for this tahun ajaran.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    /**
     * Check if this is the active academic year.
     */
    public function isActive()
    {
        return $this->is_aktif;
    }

    /**
     * Get formatted academic year name.
     */
    public function getFormattedNameAttribute()
    {
        return $this->nama;
    }

    /**
     * Get duration in years.
     */
    public function getDurationAttribute()
    {
        return $this->tahun_selesai - $this->tahun_mulai;
    }

    /**
     * Check if current date is within this academic year.
     */
    public function isCurrentPeriod()
    {
        $today = now()->toDateString();
        return $today >= $this->tanggal_mulai && $today <= $this->tanggal_selesai;
    }

    /**
     * Scope to get active academic year.
     */
    public function scopeActive($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope to get current academic year (based on date).
     */
    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where('tanggal_mulai', '<=', $today)
                    ->where('tanggal_selesai', '>=', $today);
    }

    /**
     * Get the next academic year.
     */
    public static function getNext()
    {
        return static::where('tahun_mulai', '>', now()->year)->orderBy('tahun_mulai')->first();
    }

    /**
     * Get the previous academic year.
     */
    public static function getPrevious()
    {
        return static::where('tahun_selesai', '<', now()->year)->orderBy('tahun_selesai', 'desc')->first();
    }

    /**
     * Activate this academic year (deactivate others).
     */
    public function activate()
    {
        // Deactivate all other academic years
        static::where('id', '!=', $this->id)->update(['is_aktif' => false]);
        
        // Activate this one
        $this->update(['is_aktif' => true]);
        
        return $this;
    }
}