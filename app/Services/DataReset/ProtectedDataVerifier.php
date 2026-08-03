<?php

namespace App\Services\DataReset;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProtectedDataVerifier
{
    public const CMS_TABLES = [
        'about_us',
        'hero_sections',
        'contacts',
        'contact_social_links',
        'faqs',
        'information_posts',
        'information_post_images',
        'mentors',
        'profile_videos',
        'visions_missions',
        'vision_mission_items',
        'why_choose_us',
        'certificate_settings',
        'migrations',
    ];

    public const COURSE_MASTER_TABLES = [
        'course_programs',
        'course_levels',
        'modules',
        'module_materials',
        'module_practices',
        'module_practice_questions',
        'module_practice_question_options',
        'final_exams',
        'final_exam_questions',
        'final_exam_question_options',
    ];

    public const FREE_TEST_MASTER_TABLES = [
        'free_test_categories',
        'free_tests',
        'free_test_questions',
    ];

    public static function getProtectedTables(string $resetType): array
    {
        $tables = array_merge(self::CMS_TABLES);

        if ($resetType === 'student_operations') { // Reset 2
            $tables = array_merge(
                $tables,
                self::COURSE_MASTER_TABLES,
                self::FREE_TEST_MASTER_TABLES,
                ['certificate_templates']
            );
        }

        return array_values(array_unique($tables));
    }

    public static function calculateChecksum(
        string $resetType,
        array $studentIds = [],
        array $studentEmails = []
    ): array {
        $tables = self::getProtectedTables($resetType);
        $summary = [];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $count = DB::table($table)->count();
            $hash = self::hashTableContent($table);
            $summary[$table] = [
                'count' => $count,
                'hash' => $hash,
            ];
        }

        // Add admin users checksum
        $adminUsersCount = DB::table('users')->where('role', '!=', 'student')->count();
        $adminUsersHash = hash('sha256', json_encode(
            DB::table('users')->where('role', '!=', 'student')->orderBy('id')->get(['id', 'email', 'name', 'role'])->toArray()
        ));
        $summary['users_non_student'] = [
            'count' => $adminUsersCount,
            'hash' => $adminUsersHash,
        ];

        // Add non-student sessions checksum
        $adminSessionsQuery = DB::table('sessions');
        if (!empty($studentIds)) {
            $adminSessionsQuery->whereNotIn('user_id', $studentIds);
        }
        $summary['sessions_non_student'] = [
            'count' => (clone $adminSessionsQuery)->count(),
            'hash' => hash('sha256', json_encode((clone $adminSessionsQuery)->orderBy('id')->get(['id', 'user_id'])->toArray())),
        ];

        // Add non-student reset tokens checksum
        $adminTokensQuery = DB::table('password_reset_tokens');
        if (!empty($studentEmails)) {
            $adminTokensQuery->whereNotIn('email', $studentEmails);
        }
        $summary['tokens_non_student'] = [
            'count' => (clone $adminTokensQuery)->count(),
            'hash' => hash('sha256', json_encode((clone $adminTokensQuery)->orderBy('email')->get(['email', 'created_at'])->toArray())),
        ];

        $overallHash = hash('sha256', json_encode($summary));

        return [
            'overall_hash' => $overallHash,
            'details' => $summary,
        ];
    }

    protected static function hashTableContent(string $table): string
    {
        $primaryKey = 'id';
        if ($table === 'migrations') {
            $primaryKey = 'id';
        } elseif ($table === 'password_reset_tokens') {
            $primaryKey = 'email';
        }

        $query = DB::table($table);

        if (Schema::hasColumn($table, $primaryKey)) {
            $query->orderBy($primaryKey);
        }

        $rows = $query->get()->map(function ($row) {
            // Strip timestamp fluctuations if any
            $data = (array) $row;
            return $data;
        })->toArray();

        return hash('sha256', json_encode($rows));
    }

    public static function verifyChecksums(array $baseline, array $current): void
    {
        if ($baseline['overall_hash'] !== $current['overall_hash']) {
            $mismatched = [];
            foreach ($baseline['details'] as $table => $info) {
                $currInfo = $current['details'][$table] ?? null;
                if (!$currInfo || $info['hash'] !== $currInfo['hash'] || $info['count'] !== $currInfo['count']) {
                    $mismatched[] = $table;
                }
            }

            $mismatchedList = implode(', ', $mismatched);
            throw new \RuntimeException("Protected data verification failed! The following datasets were modified during reset: [{$mismatchedList}]. Transaction rolled back.");
        }
    }
}
