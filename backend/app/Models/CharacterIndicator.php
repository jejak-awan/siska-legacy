<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'nama',
        'deskripsi',
        'criteria',
        'is_active',
        'urutan'
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_active' => 'boolean',
        'urutan' => 'integer'
    ];

    /**
     * Get the dimension that owns the indicator.
     */
    public function dimension(): BelongsTo
    {
        return $this->belongsTo(CharacterDimension::class, 'dimension_id');
    }

    /**
     * Scope a query to only include active indicators.
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
     * Get the criteria for a specific score.
     */
    public function getCriteriaForScore($score)
    {
        return $this->criteria[$score] ?? null;
    }

    /**
     * Get all available scores for this indicator.
     */
    public function getAvailableScores()
    {
        return array_keys($this->criteria ?? []);
    }
}
