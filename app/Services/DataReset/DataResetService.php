<?php

namespace App\Services\DataReset;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DataResetService
{
    public function execute(ResetPlan $plan, bool $isDryRun = false): array
    {
        $timestamp = date('Y-m-d_His');
        $resetType = $plan->getResetType();

        // 1. Safety Guards
        ResetSafetyGuard::checkEnvironmentAndDatabase($isDryRun);
        ResetSafetyGuard::checkMaintenanceMode($isDryRun);
        $queueStatus = ResetSafetyGuard::checkQueuePreconditions();

        // 2. Pre-extract Student metadata and file paths before deletion
        $studentIds = DB::table('users')->where('role', 'student')->pluck('id')->toArray();
        $studentEmails = DB::table('users')->where('role', 'student')->pluck('email')->toArray();
        $protectedUserIds = DB::table('users')->where('role', '!=', 'student')->pluck('id')->toArray();
        $protectedUserEmails = DB::table('users')->where('role', '!=', 'student')->pluck('email')->toArray();

        $certificateFiles = Schema::hasTable('certificates')
            ? DB::table('certificates')->whereNotNull('certificate_file')->where('certificate_file', '!=', '')->pluck('certificate_file')->toArray()
            : [];

        $testimonialPhotos = Schema::hasTable('testimonials')
            ? DB::table('testimonials')->whereNotNull('photo')->where('photo', '!=', '')->pluck('photo')->toArray()
            : [];

        // 3. Baseline Protected Data Checksum
        $baselineChecksum = ProtectedDataVerifier::calculateChecksum($resetType, $protectedUserIds, $protectedUserEmails);

        $deletionSteps = $plan->getDeletionSteps();
        $tableSummaries = [];

        if ($isDryRun) {
            foreach ($deletionSteps as $step) {
                $table = $step['table'];
                $type = $step['type'];
                $count = $this->countTargetRecords($table, $type, $studentIds, $studentEmails);

                $tableSummaries[] = [
                    'table' => $table,
                    'type' => $type,
                    'count_before' => $count,
                    'deleted_count' => 0,
                    'count_after' => $count,
                ];
            }

            return [
                'status' => 'success',
                'mode' => 'dry-run',
                'reset_type' => $resetType,
                'environment' => config('app.env'),
                'database' => DB::getDatabaseName(),
                'timestamp' => $timestamp,
                'tables' => $tableSummaries,
                'certificate_files_found' => count($certificateFiles),
                'testimonial_photos_found' => count($testimonialPhotos),
                'queue_status' => $queueStatus,
                'protected_checksum' => $baselineChecksum['overall_hash'],
                'exit_code' => 0,
            ];
        }

        // --- EXECUTE MODE WITHIN DB TRANSACTION ---
        $executedSummaries = [];
        $totalDeletedRecords = 0;

        DB::beginTransaction();

        try {
            foreach ($deletionSteps as $step) {
                $table = $step['table'];
                $type = $step['type'];

                if (!Schema::hasTable($table)) {
                    continue;
                }

                $beforeCount = $this->countTargetRecords($table, $type, $studentIds, $studentEmails);
                $deletedCount = $this->deleteTargetRecords($table, $type, $studentIds, $studentEmails);
                $afterCount = $this->countTargetRecords($table, $type, $studentIds, $studentEmails);

                $totalDeletedRecords += $deletedCount;
                $executedSummaries[] = [
                    'table' => $table,
                    'type' => $type,
                    'count_before' => $beforeCount,
                    'deleted_count' => $deletedCount,
                    'count_after' => $afterCount,
                ];
            }

            // Post-deletion protected data checksum verification BEFORE commit
            $postChecksum = ProtectedDataVerifier::calculateChecksum($resetType, $protectedUserIds, $protectedUserEmails);
            ProtectedDataVerifier::verifyChecksums($baselineChecksum, $postChecksum);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        // --- POST-COMMIT TASKS ---
        $warnings = [];
        $exitCode = 0;

        // 1. File Quarantine
        $quarantineResult = ResetFileQuarantine::quarantineFiles(
            $certificateFiles,
            $testimonialPhotos,
            $resetType,
            $timestamp
        );

        if (!empty($quarantineResult['warnings'])) {
            $warnings = array_merge($warnings, $quarantineResult['warnings']);
            $exitCode = 2; // Warning status
        }

        // 2. Cache Post-Commit Clear
        $cacheStatus = 'success';
        try {
            Artisan::call('cache:clear');
        } catch (\Throwable $e) {
            $cacheStatus = 'warning';
            $warnings[] = "Cache clear post-commit error: " . $e->getMessage();
            $exitCode = 2;
        }

        // 3. Write Audit Log
        $auditLogData = [
            'reset_type' => $resetType,
            'mode' => 'execute',
            'timestamp' => $timestamp,
            'environment' => config('app.env'),
            'database' => DB::getDatabaseName(),
            'total_deleted_records' => $totalDeletedRecords,
            'quarantined_files_count' => $quarantineResult['quarantined_count'],
            'protected_checksum' => $postChecksum['overall_hash'],
            'cache_status' => $cacheStatus,
            'warnings' => $warnings,
            'exit_code' => $exitCode,
            'table_details' => $executedSummaries,
        ];

        $this->writeAuditLog($timestamp, $auditLogData);

        return [
            'status' => 'success',
            'mode' => 'execute',
            'reset_type' => $resetType,
            'environment' => config('app.env'),
            'database' => DB::getDatabaseName(),
            'timestamp' => $timestamp,
            'tables' => $executedSummaries,
            'total_deleted' => $totalDeletedRecords,
            'quarantine_manifest' => $quarantineResult['manifest_file'] ?? null,
            'quarantined_count' => $quarantineResult['quarantined_count'],
            'cache_status' => $cacheStatus,
            'warnings' => $warnings,
            'protected_checksum' => $postChecksum['overall_hash'],
            'exit_code' => $exitCode,
        ];
    }

    protected function countTargetRecords(string $table, string $type, array $studentIds, array $studentEmails): int
    {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);

        if ($type === 'filtered_role_student') {
            $query->where('role', 'student');
        } elseif ($type === 'filtered_user_id') {
            if (empty($studentIds)) return 0;
            $query->whereIn('user_id', $studentIds);
        } elseif ($type === 'filtered_email') {
            if (empty($studentEmails)) return 0;
            $query->whereIn('email', $studentEmails);
        }

        return $query->count();
    }

    protected function deleteTargetRecords(string $table, string $type, array $studentIds, array $studentEmails): int
    {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);

        if ($type === 'filtered_role_student') {
            $query->where('role', 'student');
        } elseif ($type === 'filtered_user_id') {
            if (empty($studentIds)) return 0;
            $query->whereIn('user_id', $studentIds);
        } elseif ($type === 'filtered_email') {
            if (empty($studentEmails)) return 0;
            $query->whereIn('email', $studentEmails);
        }

        return $query->delete();
    }

    protected function writeAuditLog(string $timestamp, array $data): void
    {
        $logPath = storage_path("logs/data-reset-{$timestamp}.log");
        $content = json_encode($data, JSON_PRETTY_PRINT);
        File::put($logPath, $content);
    }
}
