<?php

namespace App\Services;

use App\Models\Public\Program;
use App\Models\Public\ProgramComponent;
use App\Models\Public\ContentAudit;

class ProgramCompletionService
{
    /**
     * Check program completion status
     */
    public function checkProgramCompletion($programId)
    {
        $program = Program::find($programId);
        if (!$program) {
            return null;
        }

        $components = $program->programComponents;
        $requiredComponents = $components->where('is_required', true);
        $completedComponents = $requiredComponents->where('is_completed', true);

        $completionPercentage = $requiredComponents->count() > 0 
            ? round(($completedComponents->count() / $requiredComponents->count()) * 100)
            : 0;

        $isComplete = $completionPercentage === 100;

        return [
            'completionPercentage' => $completionPercentage,
            'isComplete' => $isComplete,
            'missingComponents' => $requiredComponents->where('is_completed', false)->values(),
            'completedComponents' => $completedComponents->values(),
            'totalComponents' => $requiredComponents->count(),
        ];
    }

    /**
     * Update program completion status
     */
    public function updateProgramCompletion($programId)
    {
        $program = Program::find($programId);
        if (!$program) {
            return false;
        }

        $completionData = $this->checkProgramCompletion($programId);
        
        $program->update([
            'completion_percentage' => $completionData['completionPercentage'],
            'status' => $completionData['isComplete'] ? 'completed' : $program->status,
            'completion_status' => $completionData,
        ]);

        return $completionData;
    }

    /**
     * Mark component as completed
     */
    public function markComponentCompleted($componentId, $userId = null)
    {
        $component = ProgramComponent::find($componentId);
        if (!$component) {
            return false;
        }

        $component->update([
            'status' => 'completed',
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Update program completion
        $this->updateProgramCompletion($component->program_id);

        // Log the completion
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                ContentAudit::log(
                    $component->id,
                    'program_component',
                    'complete',
                    $user,
                    ['component_type' => $component->component_type]
                );
            }
        }

        return true;
    }

    /**
     * Get program analytics
     */
    public function getProgramAnalytics($timeRange = '30d')
    {
        $startDate = match($timeRange) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30),
        };

        $programs = Program::where('created_at', '>=', $startDate)->get();

        return [
            'totalPrograms' => $programs->count(),
            'completedPrograms' => $programs->where('status', 'completed')->count(),
            'activePrograms' => $programs->where('status', 'active')->count(),
            'averageCompletion' => $programs->avg('completion_percentage'),
            'byCategory' => $programs->groupBy('category')->map->count(),
            'byStatus' => $programs->groupBy('status')->map->count(),
            'completionTrend' => $this->getCompletionTrend($programs),
        ];
    }

    /**
     * Get completion trend data
     */
    private function getCompletionTrend($programs)
    {
        $trend = [];
        $currentDate = now()->subDays(30);
        
        for ($i = 0; $i < 30; $i++) {
            $date = $currentDate->copy()->addDays($i);
            $dayPrograms = $programs->filter(function ($program) use ($date) {
                return $program->created_at->format('Y-m-d') === $date->format('Y-m-d');
            });
            
            $trend[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $dayPrograms->count(),
                'completed' => $dayPrograms->where('status', 'completed')->count(),
            ];
        }
        
        return $trend;
    }

    /**
     * Generate program report
     */
    public function generateProgramReport($programId)
    {
        $program = Program::find($programId);
        if (!$program) {
            return null;
        }

        $components = $program->programComponents;
        $completionData = $this->checkProgramCompletion($programId);

        return [
            'program' => [
                'id' => $program->id,
                'name' => $program->name,
                'description' => $program->description,
                'category' => $program->category,
                'status' => $program->status,
                'start_date' => $program->start_date,
                'end_date' => $program->end_date,
                'objectives' => $program->objectives,
                'target_audience' => $program->target_audience,
                'responsible_role' => $program->responsible_role,
                'responsible_id' => $program->responsible_id,
            ],
            'components' => [
                'agenda' => $components->where('component_type', 'agenda')->first(),
                'proposal' => $components->where('component_type', 'proposal')->first(),
                'internal_report' => $components->where('component_type', 'internal_report')->first(),
                'public_report' => $components->where('component_type', 'public_report')->first(),
            ],
            'analytics' => [
                'completion_percentage' => $completionData['completionPercentage'],
                'is_complete' => $completionData['isComplete'],
                'missing_components' => $completionData['missingComponents'],
                'completed_components' => $completionData['completedComponents'],
                'total_components' => $completionData['totalComponents'],
                'duration' => $program->duration,
                'is_active' => $program->is_active,
            ],
        ];
    }

    /**
     * Get overdue programs
     */
    public function getOverduePrograms()
    {
        return Program::where('end_date', '<', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->get();
    }

    /**
     * Get programs due soon
     */
    public function getProgramsDueSoon($days = 7)
    {
        return Program::where('end_date', '<=', now()->addDays($days))
            ->where('end_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->get();
    }

    /**
     * Auto-update program status
     */
    public function autoUpdateProgramStatus()
    {
        $now = now();
        
        // Mark overdue programs
        Program::where('end_date', '<', $now)
            ->where('status', 'active')
            ->update(['status' => 'overdue']);

        // Mark programs as active if start date reached
        Program::where('start_date', '<=', $now)
            ->where('status', 'planning')
            ->update(['status' => 'active']);

        return true;
    }
}
