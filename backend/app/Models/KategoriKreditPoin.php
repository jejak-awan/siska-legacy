<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriKreditPoin extends Model
{
    use HasFactory;

    protected $table = 'kategori_kredit_poin';

    protected $fillable = [
        'nama',
        'jenis',
        'nilai_default',
        'deskripsi',
        'is_aktif'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    // Relationships
    public function kreditPoin(): HasMany
    {
        return $this->hasMany(KreditPoin::class, 'kategori_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopePositif($query)
    {
        return $query->where('jenis', 'positif');
    }

    public function scopeNegatif($query)
    {
        return $query->where('jenis', 'negatif');
    }

    // Accessors & Mutators
    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'positif' => 'Poin Positif',
            'negatif' => 'Poin Negatif',
            default => 'Tidak Diketahui'
        };
    }

    public function getJenisColorAttribute(): string
    {
        return match($this->jenis) {
            'positif' => 'green',
            'negatif' => 'red',
            default => 'gray'
        };
    }
}
