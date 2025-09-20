<?php

namespace App\Services;

use App\Models\Public\ContentAudit;

class ContentSanitizationService
{
    /**
     * Sanitize activity data for public display
     */
    public function sanitizeActivity($activity)
    {
        return [
            'id' => $activity->public_id ?? $activity->id,
            'title' => $activity->title,
            'description' => $activity->public_description ?? $activity->description,
            'date' => $activity->date,
            'location' => $this->sanitizeLocation($activity->location),
            'category' => $activity->category,
            'status' => $activity->status,
            'gallery' => $this->sanitizeGallery($activity->gallery ?? []),
            'achievements' => $this->sanitizeAchievements($activity->achievements ?? []),
            // NO: student_ids, personal_data, internal_notes
        ];
    }

    /**
     * Sanitize student data for public display
     */
    public function sanitizeStudentData($student)
    {
        return [
            'name' => $this->maskName($student->name),
            'class' => $student->class,
            'achievement' => $student->achievement ?? null,
            'photo' => $this->sanitizePhoto($student->photo ?? null),
            // NO: nisn, nik, address, phone, family_data
        ];
    }

    /**
     * Sanitize teacher data for public display
     */
    public function sanitizeTeacherData($teacher)
    {
        return [
            'name' => $teacher->name,
            'subject' => $teacher->subject ?? null,
            'photo' => $this->sanitizePhoto($teacher->photo ?? null),
            // NO: nik, phone, address, salary, personal_data
        ];
    }

    /**
     * Sanitize location data
     */
    private function sanitizeLocation($location)
    {
        // Remove specific addresses, keep only general location
        $generalLocations = [
            'Sekolah' => 'Sekolah',
            'Aula' => 'Aula Sekolah',
            'Lapangan' => 'Lapangan Sekolah',
            'Lab' => 'Laboratorium',
            'Perpustakaan' => 'Perpustakaan',
        ];

        foreach ($generalLocations as $key => $value) {
            if (stripos($location, $key) !== false) {
                return $value;
            }
        }

        return 'Sekolah'; // Default fallback
    }

    /**
     * Sanitize gallery images
     */
    private function sanitizeGallery($gallery)
    {
        if (!is_array($gallery)) {
            return [];
        }

        return array_map(function ($image) {
            // Remove any images that might contain sensitive information
            if (isset($image['url'])) {
                return [
                    'url' => $image['url'],
                    'alt' => $image['alt'] ?? 'Aktivitas Sekolah',
                    'caption' => $image['caption'] ?? null,
                ];
            }
            return $image;
        }, $gallery);
    }

    /**
     * Sanitize achievements data
     */
    private function sanitizeAchievements($achievements)
    {
        if (!is_array($achievements)) {
            return [];
        }

        return array_map(function ($achievement) {
            return [
                'title' => $achievement['title'] ?? $achievement,
                'description' => $achievement['description'] ?? null,
                'date' => $achievement['date'] ?? null,
                'level' => $achievement['level'] ?? 'Sekolah',
            ];
        }, $achievements);
    }

    /**
     * Mask student name for privacy
     */
    private function maskName($name)
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            // Show first name, mask last name
            return $words[0] . ' ' . substr($words[1], 0, 1) . '***';
        }
        
        // If only one name, mask part of it
        return substr($name, 0, 2) . '***';
    }

    /**
     * Sanitize photo URL
     */
    private function sanitizePhoto($photo)
    {
        if (!$photo) {
            return null;
        }

        // Ensure photo URL is from allowed domains
        $allowedDomains = [
            'localhost',
            'your-school-domain.com',
            'cdn.your-school.com',
        ];

        $parsedUrl = parse_url($photo);
        if (isset($parsedUrl['host'])) {
            foreach ($allowedDomains as $domain) {
                if (strpos($parsedUrl['host'], $domain) !== false) {
                    return $photo;
                }
            }
        }

        return null; // Block external photos
    }

    /**
     * Sanitize content for public display
     */
    public function sanitizeContent($content)
    {
        // Remove any HTML that might contain sensitive information
        $content = strip_tags($content, '<p><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>');
        
        // Remove any potential personal information patterns
        $patterns = [
            '/\b\d{10,16}\b/', // NISN/NIK patterns
            '/\b\d{3}-\d{3}-\d{4}\b/', // Phone patterns
            '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', // Email patterns
        ];

        foreach ($patterns as $pattern) {
            $content = preg_replace($pattern, '[REDACTED]', $content);
        }

        return $content;
    }

    /**
     * Log sanitization activity
     */
    public function logSanitization($contentId, $contentType, $user, $originalData, $sanitizedData)
    {
        $changes = [
            'original_fields' => array_keys($originalData),
            'sanitized_fields' => array_keys($sanitizedData),
            'removed_fields' => array_diff(array_keys($originalData), array_keys($sanitizedData)),
        ];

        ContentAudit::log($contentId, $contentType, 'sanitize', $user, $changes);
    }
}
