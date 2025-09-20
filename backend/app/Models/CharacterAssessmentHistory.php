<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterAssessmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'dimension_id',
        'score',
        'notes',
        'evidence'
    ];

    protected $casts = [
        'evidence' => 'array',
        'score' => 'decimal:2'
    ];

    /**
     * Get the assessment that owns the history.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(CharacterAssessment::class, 'assessment_id');
    }

    /**
     * Get the dimension that owns the history.
     */
    public function dimension(): BelongsTo
    {
        return $this->belongsTo(CharacterDimension::class, 'dimension_id');
    }

    /**
     * Get the score label for this history.
     */
    public function getScoreLabel()
    {
        if ($this->score >= 3.5) return 'Sangat Baik';
        if ($this->score >= 3.0) return 'Baik';
        if ($this->score >= 2.5) return 'Cukup';
        return 'Perlu Bimbingan';
    }

    /**
     * Get the score color for this history.
     */
    public function getScoreColor()
    {
        if ($this->score >= 3.5) return 'green';
        if ($this->score >= 3.0) return 'yellow';
        if ($this->score >= 2.5) return 'orange';
        return 'red';
    }
}
