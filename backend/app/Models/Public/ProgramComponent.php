<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramComponent extends Model
{
    use HasFactory;

    protected $connection = 'public';
    protected $table = 'program_components';

    protected $fillable = [
        'program_id',
        'component_type',
        'title',
        'content',
        'data',
        'status',
        'is_required',
        'is_completed',
        'assigned_role',
        'assigned_user_id',
        'completed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_required' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Scopes
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('component_type', $type);
    }

    public function scopeAssignedTo($query, $role, $userId)
    {
        return $query->where('assigned_role', $role)
                    ->where('assigned_user_id', $userId);
    }

    // Accessors
    public function getComponentTypeNameAttribute()
    {
        $types = [
            'agenda' => 'Agenda Program',
            'proposal' => 'Proposal Program',
            'internal_report' => 'Laporan Internal',
            'public_report' => 'Laporan Publik',
        ];

        return $types[$this->component_type] ?? $this->component_type;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'reviewed' => 'info',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Methods
    public function markAsCompleted($userId = null)
    {
        $this->update([
            'status' => 'completed',
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Update program completion status
        $this->program->updateCompletionStatus();
    }

    public function markAsInProgress()
    {
        $this->update([
            'status' => 'in_progress',
        ]);
    }

    public function canBeEditedBy($user)
    {
        // Admin can edit all components
        if ($user->role === 'admin') {
            return true;
        }

        // Assigned user can edit their components
        if ($this->assigned_role === $user->role && $this->assigned_user_id === $user->id) {
            return true;
        }

        // Staff can edit components in their domain
        if ($user->role === 'staff') {
            return true;
        }

        return false;
    }

    public function getValidationRules()
    {
        $rules = [
            'agenda' => [
                'timeline' => 'required|string',
                'activities' => 'required|array',
                'milestones' => 'required|array',
                'deadlines' => 'required|array',
            ],
            'proposal' => [
                'background' => 'required|string',
                'objectives' => 'required|array',
                'methodology' => 'required|string',
                'budget' => 'required|numeric',
                'timeline' => 'required|string',
            ],
            'internal_report' => [
                'execution_summary' => 'required|string',
                'challenges' => 'required|string',
                'lessons_learned' => 'required|string',
                'recommendations' => 'required|string',
            ],
            'public_report' => [
                'summary' => 'required|string',
                'achievements' => 'required|array',
                'impact' => 'required|string',
                'gallery' => 'nullable|array',
                'testimonials' => 'nullable|array',
            ],
        ];

        return $rules[$this->component_type] ?? [];
    }
}
