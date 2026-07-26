<?php

namespace App\Services;

use App\Models\CourseLevel;
use App\Models\CourseProgram;

class CertificatePresentationService
{
    /**
     * Build dynamic course display name for certificate.
     *
     * Rules:
     * A. Both Program and Level present & different -> "{Program Name} — {Level Name}"
     * B. Program and Level have same name (case-insensitive after trim) -> single name
     * C. Level empty -> Program Name
     * D. Program empty -> Level Name
     * E. Both empty -> "Course Completion"
     */
    public static function courseDisplayName(?CourseProgram $program, ?CourseLevel $level): string
    {
        $programName = $program?->name ? trim($program->name) : '';
        $levelName = $level?->name ? trim($level->name) : '';

        if ($programName !== '' && $levelName !== '') {
            if (mb_strtolower($programName, 'UTF-8') === mb_strtolower($levelName, 'UTF-8')) {
                return $programName;
            }
            return "{$programName} — {$levelName}";
        }

        if ($programName !== '') {
            return $programName;
        }

        if ($levelName !== '') {
            return $levelName;
        }

        return 'Course Completion';
    }

    /**
     * Resolve score label heading for certificate without trailing colon.
     */
    public static function scoreLabel(?CourseLevel $level): string
    {
        $rawLabel = $level?->certificate_score_label;
        $normalized = self::normalizeScoreLabel($rawLabel);

        return $normalized !== null ? $normalized : 'Final Score';
    }

    /**
     * Server-side normalization for certificate_score_label input.
     * Strips HTML, trims whitespace, removes trailing colon.
     */
    public static function normalizeScoreLabel(?string $label): ?string
    {
        if ($label === null) {
            return null;
        }

        // 1. Strip HTML tags
        $clean = strip_tags($label);

        // 2. Trim whitespace
        $clean = trim($clean);

        if ($clean === '') {
            return null;
        }

        // 3. Remove trailing colon(s) and whitespace at end
        $clean = preg_replace('/:\s*$/', '', $clean);
        $clean = trim($clean);

        if ($clean === '') {
            return null;
        }

        return $clean;
    }
}
