<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $connection = 'public';
    protected $table = 'programs';

    protected $fillable = [
        'name',
        'description',
        'category',
        'status',
        'start_date',
        'end_date',
        'objectives',
        'target_audience',
        'responsible_role',
        'responsible_id',
        'components',
        'completion_status',
        'completion_percentage',
    ];

    protected $casts = [
        'objectives' => 'array',
        'target_audience' => 'array',
        'components' => 'array',
        'completion_status' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function programComponents()
    {
        return $this->hasMany(ProgramComponent::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByResponsible($query, $role, $id)
    {
        return $query->where('responsible_role', $role)
                    ->where('responsible_id', $id);
    }

    // Accessors
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getIsActiveAttribute()
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getFormattedObjectivesAttribute()
    {
        return is_array($this->objectives) ? implode(', ', $this->objectives) : '';
    }

    // Methods
    public function updateCompletionStatus()
    {
        $requiredComponents = $this->programComponents()
            ->where('is_required', true)
            ->get();

        $completedComponents = $requiredComponents
            ->where('is_completed', true);

        $completionPercentage = $requiredComponents->count() > 0 
            ? round(($completedComponents->count() / $requiredComponents->count()) * 100)
            : 0;

        $this->update([
            'completion_percentage' => $completionPercentage,
            'status' => $completionPercentage === 100 ? 'completed' : $this->status
        ]);

        return $completionPercentage;
    }

    public function getMissingComponents()
    {
        return $this->programComponents()
            ->where('is_required', true)
            ->where('is_completed', false)
            ->get();
    }

    public function canBeEditedBy($user)
    {
        // Admin can edit all programs
        if ($user->role === 'admin') {
            return true;
        }

        // Responsible user can edit their programs
        return $this->responsible_role === $user->role && $this->responsible_id === $user->id;
    }
}
