<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'assessor_id',
        'periode',
        'semester',
        'tanggal_penilaian',
        'scores',
        'total_score',
        'comments',
        'evidence',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'scores' => 'array',
        'evidence' => 'array',
        'tanggal_penilaian' => 'date',
        'approved_at' => 'datetime',
        'total_score' => 'decimal:2'
    ];

    /**
     * Get the student that owns the assessment.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get the assessor that created the assessment.
     */
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    /**
     * Get the user who approved the assessment.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the assessment history for this assessment.
     */
    public function history(): HasMany
    {
        return $this->hasMany(CharacterAssessmentHistory::class, 'assessment_id');
    }

    /**
     * Scope a query to only include assessments for a specific period.
     */
    public function scopeForPeriod($query, $periode, $semester = null)
    {
        $query = $query->where('periode', $periode);
        
        if ($semester) {
            $query->where('semester', $semester);
        }
        
        return $query;
    }

    /**
     * Scope a query to only include assessments by a specific assessor.
     */
    public function scopeByAssessor($query, $assessorId)
    {
        return $query->where('assessor_id', $assessorId);
    }

    /**
     * Scope a query to only include assessments with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include approved assessments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include draft assessments.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get the average score for a specific dimension.
     */
    public function getDimensionScore($dimensionId)
    {
        return $this->scores[$dimensionId] ?? null;
    }

    /**
     * Get the score label for the total score.
     */
    public function getTotalScoreLabel()
    {
        if ($this->total_score >= 3.5) return 'Sangat Baik';
        if ($this->total_score >= 3.0) return 'Baik';
        if ($this->total_score >= 2.5) return 'Cukup';
        return 'Perlu Bimbingan';
    }

    /**
     * Get the score color for the total score.
     */
    public function getTotalScoreColor()
    {
        if ($this->total_score >= 3.5) return 'green';
        if ($this->total_score >= 3.0) return 'yellow';
        if ($this->total_score >= 2.5) return 'orange';
        return 'red';
    }

    /**
     * Check if the assessment can be edited.
     */
    public function canBeEdited()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the assessment can be approved.
     */
    public function canBeApproved()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if the assessment can be rejected.
     */
    public function canBeRejected()
    {
        return $this->status === 'submitted';
    }
}
