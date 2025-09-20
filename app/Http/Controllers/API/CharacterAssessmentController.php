<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CharacterAssessment;
use App\Models\CharacterDimension;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CharacterAssessmentController extends Controller
{
    /**
     * Get all character dimensions.
     */
    public function getDimensions(): JsonResponse
    {
        try {
            $dimensions = CharacterDimension::active()
                ->ordered()
                ->with('indicators')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $dimensions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dimensions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assessment statistics.
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $stats = [
                'totalAssessments' => CharacterAssessment::count(),
                'avgCharacterScore' => CharacterAssessment::approved()->avg('total_score') ?? 0,
                'topPerformers' => CharacterAssessment::approved()
                    ->where('total_score', '>=', 3.5)
                    ->count(),
                'pendingAssessments' => CharacterAssessment::where('status', 'draft')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assessments for a specific student.
     */
    public function getStudentAssessments(Request $request, $studentId): JsonResponse
    {
        try {
            $assessments = CharacterAssessment::where('siswa_id', $studentId)
                ->with(['assessor', 'approver'])
                ->orderBy('tanggal_penilaian', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $assessments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student assessments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new assessment.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'siswa_id' => 'required|exists:siswa,id',
                'periode' => 'required|string',
                'semester' => 'required|string',
                'tanggal_penilaian' => 'required|date',
                'scores' => 'required|array',
                'comments' => 'nullable|string',
                'evidence' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Calculate total score
            $scores = $request->scores;
            $totalScore = array_sum($scores) / count($scores);

            $assessment = CharacterAssessment::create([
                'siswa_id' => $request->siswa_id,
                'assessor_id' => Auth::id(),
                'periode' => $request->periode,
                'semester' => $request->semester,
                'tanggal_penilaian' => $request->tanggal_penilaian,
                'scores' => $scores,
                'total_score' => $totalScore,
                'comments' => $request->comments,
                'evidence' => $request->evidence,
                'status' => 'draft'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment created successfully',
                'data' => $assessment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing assessment.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $assessment = CharacterAssessment::findOrFail($id);

            if (!$assessment->canBeEdited()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment cannot be edited'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'scores' => 'required|array',
                'comments' => 'nullable|string',
                'evidence' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Calculate total score
            $scores = $request->scores;
            $totalScore = array_sum($scores) / count($scores);

            $assessment->update([
                'scores' => $scores,
                'total_score' => $totalScore,
                'comments' => $request->comments,
                'evidence' => $request->evidence
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment updated successfully',
                'data' => $assessment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit an assessment for approval.
     */
    public function submit($id): JsonResponse
    {
        try {
            $assessment = CharacterAssessment::findOrFail($id);

            if (!$assessment->canBeEdited()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment cannot be submitted'
                ], 403);
            }

            $assessment->update(['status' => 'submitted']);

            return response()->json([
                'success' => true,
                'message' => 'Assessment submitted successfully',
                'data' => $assessment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve an assessment.
     */
    public function approve($id): JsonResponse
    {
        try {
            $assessment = CharacterAssessment::findOrFail($id);

            if (!$assessment->canBeApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment cannot be approved'
                ], 403);
            }

            $assessment->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment approved successfully',
                'data' => $assessment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an assessment.
     */
    public function reject(Request $request, $id): JsonResponse
    {
        try {
            $assessment = CharacterAssessment::findOrFail($id);

            if (!$assessment->canBeRejected()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment cannot be rejected'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $assessment->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment rejected successfully',
                'data' => $assessment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an assessment.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $assessment = CharacterAssessment::findOrFail($id);

            if (!$assessment->canBeEdited()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment cannot be deleted'
                ], 403);
            }

            $assessment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assessment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assessment report data.
     */
    public function getReport(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['periode', 'semester', 'kelas']);
            
            $query = CharacterAssessment::approved()
                ->with(['siswa', 'assessor']);

            if (isset($filters['periode'])) {
                $query->where('periode', $filters['periode']);
            }

            if (isset($filters['semester'])) {
                $query->where('semester', $filters['semester']);
            }

            if (isset($filters['kelas'])) {
                $query->whereHas('siswa', function ($q) use ($filters) {
                    $q->where('kelas', $filters['kelas']);
                });
            }

            $assessments = $query->get();

            // Calculate statistics
            $stats = [
                'totalStudents' => $assessments->unique('siswa_id')->count(),
                'avgScore' => $assessments->avg('total_score'),
                'topPerformers' => $assessments->where('total_score', '>=', 3.5)->count(),
                'needsAttention' => $assessments->where('total_score', '<', 2.5)->count()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $stats,
                    'assessments' => $assessments
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
