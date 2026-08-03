<?php

namespace App\Services\DataReset;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ResetSafetyGuard
{
    public const ALLOWED_RESET_DB = 'queens_english_reset_test';

    public static function checkEnvironmentAndDatabase(bool $isDryRun = false): void
    {
        // 1. Production is strictly forbidden
        if (App::environment('production') || config('app.env') === 'production') {
            throw new \RuntimeException('Data reset commands are strictly forbidden in production environment.');
        }

        $activeDb = DB::getDatabaseName();

        // 2. Development DB is strictly forbidden
        if ($activeDb === 'queens_english_db') {
            throw new \RuntimeException("Data reset cannot be executed on development database '{$activeDb}'.");
        }

        // 3. Automated Unit Tests context
        if (App::runningUnitTests()) {
            if (!str_contains($activeDb, 'test')) {
                throw new \RuntimeException("Unit tests must target a testing database containing '_test', active: '{$activeDb}'.");
            }
            return;
        }

        // 4. Manual execution requires APP_ENV=reset-testing and DB=queens_english_reset_test
        $env = config('app.env');
        if ($env !== 'reset-testing') {
            throw new \RuntimeException("Manual reset execution requires APP_ENV=reset-testing, currently: '{$env}'.");
        }

        if ($activeDb !== self::ALLOWED_RESET_DB) {
            throw new \RuntimeException("Manual reset execution must target database '" . self::ALLOWED_RESET_DB . "', active: '{$activeDb}'.");
        }
    }

    public static function checkMaintenanceMode(bool $isDryRun = false): void
    {
        if ($isDryRun || App::runningUnitTests()) {
            return;
        }

        if (!App::isDownForMaintenance()) {
            throw new \RuntimeException('Application must be in maintenance mode before executing data reset (--execute). Run "php artisan down" first.');
        }
    }

    public static function checkQueuePreconditions(): array
    {
        $pendingJobs = DB::table('jobs')->count();
        if ($pendingJobs > 0) {
            throw new \RuntimeException("Pending queue jobs exist ({$pendingJobs} in 'jobs' table). Complete or clear pending jobs before executing reset.");
        }

        $pendingBatches = DB::table('job_batches')->where('pending_jobs', '>', 0)->count();
        if ($pendingBatches > 0) {
            throw new \RuntimeException("Pending job batches exist ({$pendingBatches} in 'job_batches'). Complete or clear job batches before executing reset.");
        }

        $failedJobs = DB::table('failed_jobs')->count();

        return [
            'pending_jobs' => 0,
            'pending_batches' => 0,
            'failed_jobs' => $failedJobs,
        ];
    }
}
