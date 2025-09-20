<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileSekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'npsn',
        'alamat',
        'telepon',
        'email',
        'website',
        'jenjang',
        'status',
        'akreditasi',
        'kepala_sekolah',
        'visi',
        'misi',
        'tujuan',
        'logo',
        'foto_kepala_sekolah',
        'kontak_lain',
        'sosial_media',
        'sejarah',
        'prestasi',
        'struktur_organisasi',
        'is_active'
    ];

    protected $casts = [
        'kontak_lain' => 'array',
        'sosial_media' => 'array',
        'struktur_organisasi' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active school profile
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set as active profile
     */
    public function setActive()
    {
        // Deactivate all other profiles
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this profile
        $this->update(['is_active' => true]);
    }
}
