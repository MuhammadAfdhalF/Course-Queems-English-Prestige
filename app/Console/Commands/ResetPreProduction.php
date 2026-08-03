<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataReset\DataResetService;
use App\Services\DataReset\ResetPreProductionPlan;

class ResetPreProduction extends Command
{
    protected $signature = 'app:reset-pre-production {--dry-run : Run simulation without deleting data} {--execute : Execute full pre-production data reset}';

    protected $description = 'Full Pre-Production Data Reset (Purges all test students, operational data, courses, free tests, transactions, and issued certificates)';

    public function handle(DataResetService $service): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $execute = (bool) $this->option('execute');

        if (!$dryRun && !$execute) {
            $this->error('Error: You must specify either --dry-run or --execute.');
            return 1;
        }

        if ($dryRun && $execute) {
            $this->error('Error: You cannot specify both --dry-run and --execute at the same time.');
            return 1;
        }

        $plan = new ResetPreProductionPlan();

        $this->info("==================================================");
        $this->info(" FULL PRE-PRODUCTION DATA RESET ");
        $this->info("==================================================");
        $this->line("APP_ENV          : " . config('app.env'));
        $this->line("Active DB        : " . \Illuminate\Support\Facades\DB::getDatabaseName());
        $this->line("Reset Mode       : " . ($dryRun ? 'DRY-RUN (Simulation Only)' : 'EXECUTE (REAL MUTATION)'));
        $this->info("--------------------------------------------------");

        if ($execute) {
            if ($this->option('no-interaction')) {
                $this->error('Error: Execution in non-interactive mode is strictly forbidden.');
                return 1;
            }

            $phrase = $plan->getConfirmationPhrase();
            $answer = $this->ask("WARNING: This will purge ALL operational and course data!\nTo proceed, type exact phrase: [{$phrase}]");

            if (trim($answer) !== $phrase) {
                $this->error("Confirmation failed. Exact phrase matching required. Reset aborted.");
                return 1;
            }
        }

        try {
            $result = $service->execute($plan, $dryRun);

            $this->info("--------------------------------------------------");
            $this->info("Target Tables & Record Summary:");
            $headers = ['Table', 'Filter Type', 'Before Count', 'Deleted', 'After Count'];
            $rows = [];
            foreach ($result['tables'] as $t) {
                $rows[] = [$t['table'], $t['type'], $t['count_before'], $t['deleted_count'], $t['count_after']];
            }
            $this->table($headers, $rows);

            if ($dryRun) {
                $this->info("--------------------------------------------------");
                $this->info("DRY-RUN COMPLETED SUCCESSFULLY.");
                $this->line("No database records deleted.");
                $this->line("No files quarantined.");
                $this->line("Protected Checksum: " . $result['protected_checksum']);
                return 0;
            }

            $this->info("--------------------------------------------------");
            $this->info("PRE-PRODUCTION DATA RESET COMPLETED SUCCESSFULLY.");
            $this->line("Total Deleted Records : " . $result['total_deleted']);
            $this->line("Quarantined Files     : " . $result['quarantined_count']);
            $this->line("Protected Checksum    : " . $result['protected_checksum']);

            if (!empty($result['warnings'])) {
                $this->warn("Warnings Encountered:");
                foreach ($result['warnings'] as $w) {
                    $this->warn(" - {$w}");
                }
            }

            return $result['exit_code'];
        } catch (\Throwable $e) {
            $this->error("RESET ERROR: " . $e->getMessage());
            return 1;
        }
    }
}
