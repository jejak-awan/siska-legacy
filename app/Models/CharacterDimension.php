<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'indikator',
        'skala_min',
        'skala_max',
        'skala_label',
        'is_active',
        'urutan'
    ];

    protected $casts = [
        'indikator' => 'array',
        'is_active' => 'boolean',
        'skala_min' => 'integer',
        'skala_max' => 'integer',
        'urutan' => 'integer'
    ];

    /**
     * Get the indicators for this dimension.
     */
    public function indicators(): HasMany
    {
        return $this->hasMany(CharacterIndicator::class, 'dimension_id');
    }

    /**
     * Get the assessment history for this dimension.
     */
    public function assessmentHistory(): HasMany
    {
        return $this->hasMany(CharacterAssessmentHistory::class, 'dimension_id');
    }

    /**
     * Scope a query to only include active dimensions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by urutan.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }

    /**
     * Get the score label for a given score.
     */
    public function getScoreLabel($score)
    {
        $labels = [
            1 => 'Perlu Bimbingan',
            2 => 'Cukup',
            3 => 'Baik',
            4 => 'Sangat Baik'
        ];

        return $labels[$score] ?? 'Tidak Diketahui';
    }

    /**
     * Get the score color for a given score.
     */
    public function getScoreColor($score)
    {
        if ($score >= 3.5) return 'green';
        if ($score >= 3.0) return 'yellow';
        if ($score >= 2.5) return 'orange';
        return 'red';
    }
}
