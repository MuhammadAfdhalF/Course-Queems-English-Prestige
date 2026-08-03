<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

$mainDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Real/queens-english-prestige';
$rehearsalDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Rehearsal/queens-english-prestige-reset-rehearsal';
$phpBin = 'D:/APK/laragon/bin/php/php-8.3.16-Win32-vs16-x64/php.exe';
$mysqldumpBin = 'D:/APK/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysqldump.exe';
$mysqlBin = 'D:/APK/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysql.exe';

$backupFile = 'D:/Database Backups/Queens English/queens_english_db_before_task018_20260803_214914.sql';
if (!file_exists($backupFile)) {
    echo "Backup file $backupFile does not exist!\n";
    exit(1);
}

$expectedBackupHash = 'bdad22950830c0acac15e9fe8cf4fa7388882477466add8d7b5c27c7510aeaf4';
$actualBackupHash = hash_file('sha256', $backupFile);
if ($actualBackupHash !== $expectedBackupHash) {
    echo "Backup hash mismatch! Expected $expectedBackupHash, got $actualBackupHash\n";
    exit(1);
}
echo "Verified baseline backup dump: SHA-256 = $actualBackupHash\n";

$artifactsDir = "$rehearsalDir/rehearsal-artifacts";
if (!is_dir($artifactsDir)) {
    mkdir($artifactsDir, 0777, true);
}

echo "==================================================\n";
echo "TASK-018-R1: ISOLATED RESET REHEARSAL RERUN\n";
echo "==================================================\n";

// 1. PHYSICAL STORAGE COPY & BASELINE MANIFEST
echo "\n--- 1. PHYSICAL STORAGE COPY & BASELINE MANIFEST ---\n";
$mainStoragePublic = "$mainDir/storage/app/public";
$rehearsalStoragePublic = "$rehearsalDir/storage/app/public";

function restoreRehearsalStorage($mainStoragePublic, $rehearsalStoragePublic) {
    if (is_dir($rehearsalStoragePublic)) {
        File::deleteDirectory($rehearsalStoragePublic);
    }
    mkdir($rehearsalStoragePublic, 0777, true);

    exec("xcopy " . escapeshellarg(str_replace('/', '\\', $mainStoragePublic)) . " " . escapeshellarg(str_replace('/', '\\', $rehearsalStoragePublic)) . " /E /I /H /Y /Q", $outCp, $codeCp);

    if (!is_dir("$rehearsalStoragePublic/certificate-templates")) {
        mkdir("$rehearsalStoragePublic/certificate-templates", 0777, true);
        file_put_contents("$rehearsalStoragePublic/certificate-templates/template1.jpg", "template_content_sample");
    }
    if (!is_dir("$rehearsalStoragePublic/certificates")) {
        mkdir("$rehearsalStoragePublic/certificates", 0777, true);
    }
    if (!is_dir("$rehearsalStoragePublic/testimonials")) {
        mkdir("$rehearsalStoragePublic/testimonials", 0777, true);
    }
    if (!is_dir("$rehearsalStoragePublic/materials")) {
        mkdir("$rehearsalStoragePublic/materials", 0777, true);
        file_put_contents("$rehearsalStoragePublic/materials/material1.pdf", "material_content_sample");
    }

    // Seed dummy files for any DB-referenced certificate/testimonial path so physical files exist during rehearsal
    $certFiles = DB::table('certificates')->whereNotNull('certificate_file')->pluck('certificate_file')->toArray();
    foreach ($certFiles as $f) {
        $p = "$rehearsalStoragePublic/$f";
        if (!file_exists($p)) {
            @mkdir(dirname($p), 0777, true);
            file_put_contents($p, "dummy_pdf_content_for_$f");
        }
    }
    $testFiles = DB::table('testimonials')->whereNotNull('photo')->pluck('photo')->toArray();
    foreach ($testFiles as $f) {
        $p = "$rehearsalStoragePublic/$f";
        if (!file_exists($p)) {
            @mkdir(dirname($p), 0777, true);
            file_put_contents($p, "dummy_photo_content_for_$f");
        }
    }
}

restoreRehearsalStorage($mainStoragePublic, $rehearsalStoragePublic);

function buildStorageManifest($dir) {
    $manifest = [];
    if (!is_dir($dir)) return $manifest;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $relPath = str_replace('\\', '/', substr($file->getPathname(), strlen($dir) + 1));
            $manifest[$relPath] = [
                'relative_path' => $relPath,
                'size' => $file->getSize(),
                'sha256' => hash_file('sha256', $file->getPathname()),
            ];
        }
    }
    ksort($manifest);
    return $manifest;
}

$baselineManifest = buildStorageManifest($rehearsalStoragePublic);
file_put_contents("$artifactsDir/baseline-storage-manifest.json", json_encode($baselineManifest, JSON_PRETTY_PRINT));
echo "Saved baseline storage manifest (" . count($baselineManifest) . " files).\n";

// 2. CREATE DATABASE CLONE (queens_english_reset_test)
echo "\n--- 2. CREATE DATABASE CLONE ---\n";
$targetDb = 'queens_english_reset_test';
if ($targetDb !== 'queens_english_reset_test') {
    echo "Target DB name MUST be exactly queens_english_reset_test! Aborting.\n";
    exit(1);
}

$dbHost = env('DB_HOST', '127.0.0.1');
$dbPort = env('DB_PORT', '3306');
$dbUser = env('DB_USERNAME', 'root');
$dbPass = env('DB_PASSWORD', '');

function restoreRehearsalDatabase($targetDb, $backupFile, $mysqlBin, $dbHost, $dbPort, $dbUser, $dbPass) {
    DB::statement("DROP DATABASE IF EXISTS `{$targetDb}`");
    DB::statement("CREATE DATABASE `{$targetDb}`");

    $importCmd = escapeshellarg($mysqlBin) . " --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . ($dbPass !== '' ? " --password=" . escapeshellarg($dbPass) : "") . " " . escapeshellarg($targetDb) . " < " . escapeshellarg($backupFile);
    exec($importCmd, $outImp, $codeImp);

    if ($codeImp !== 0) {
        echo "DB Import failed with code $codeImp\n";
        exit(1);
    }
}

restoreRehearsalDatabase($targetDb, $backupFile, $mysqlBin, $dbHost, $dbPort, $dbUser, $dbPass);
echo "Imported baseline dump into `{$targetDb}`.\n";

// Verify baseline counts on clone DB
config(['database.connections.mysql.database' => $targetDb]);
DB::purge('mysql');
DB::reconnect('mysql');

$baselineTables = [
    'users' => 5,
    'course_programs' => 3,
    'course_levels' => 5,
    'modules' => 6,
    'module_materials' => 16,
    'module_practices' => 5,
    'final_exams' => 7,
    'migrations' => 62,
];

echo "Verifying baseline counts on `{$targetDb}`:\n";
foreach ($baselineTables as $tbl => $exp) {
    $act = DB::table($tbl)->count();
    $st = ($act === $exp) ? 'MATCH' : 'MISMATCH';
    echo "  - $tbl : $act (expected $exp) [$st]\n";
}

// 3. CREATE LOCAL RESET ENVIRONMENT (.env.reset-testing)
echo "\n--- 3. CREATE LOCAL RESET ENVIRONMENT ---\n";
$appKey = env('APP_KEY', '');
$envContent = <<<EOT
APP_NAME="Queens English Prestige"
APP_ENV=reset-testing
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST={$dbHost}
DB_PORT={$dbPort}
DB_DATABASE=queens_english_reset_test
DB_USERNAME={$dbUser}
DB_PASSWORD={$dbPass}

BROADCAST_DRIVER=log
CACHE_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1
MAIL_MAILER=log
EOT;

file_put_contents("$rehearsalDir/.env.reset-testing", $envContent);
file_put_contents("$rehearsalDir/.env", $envContent);

$envVars = [];
foreach ($_ENV as $k => $v) {
    if (is_string($v) || is_numeric($v)) $envVars[$k] = (string)$v;
}
foreach ($_SERVER as $k => $v) {
    if (is_string($v) || is_numeric($v)) $envVars[$k] = (string)$v;
}

$envVars = array_merge($envVars, [
    'APP_ENV' => 'reset-testing',
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => $dbHost,
    'DB_PORT' => (string)$dbPort,
    'DB_DATABASE' => 'queens_english_reset_test',
    'DB_USERNAME' => $dbUser,
    'DB_PASSWORD' => $dbPass,
    'CACHE_DRIVER' => 'database',
    'CACHE_STORE' => 'database',
    'QUEUE_CONNECTION' => 'database',
    'SESSION_DRIVER' => 'database',
    'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
    'PATH' => getenv('PATH'),
]);

function runRehearsalCmd($cmd, $workDir, $envVars, $input = null) {
    $descriptorspec = [
       0 => ["pipe", "r"],
       1 => ["pipe", "w"],
       2 => ["pipe", "w"]
    ];

    $process = proc_open($cmd, $descriptorspec, $pipes, $workDir, $envVars);
    if (!is_resource($process)) {
        return ['code' => 1, 'stdout' => '', 'stderr' => 'Failed to open process'];
    }

    if ($input !== null) {
        fwrite($pipes[0], $input);
    }
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    $code = proc_close($process);
    return ['code' => $code, 'stdout' => $stdout, 'stderr' => $stderr];
}

$resCfg = runRehearsalCmd(escapeshellarg($phpBin) . " artisan config:clear", $rehearsalDir, $envVars);

// 4. RESET 1 DRY-RUN & EXECUTE
echo "\n--- 4. RESET 1 DRY-RUN & EXECUTE ---\n";

// Dry-Run Reset 1
$dryRes1 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan app:reset-pre-production --dry-run", $rehearsalDir, $envVars);
echo "Reset 1 Dry-Run exit code: {$dryRes1['code']}\n";

// Maintenance mode down
$downRes1 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan down", $rehearsalDir, $envVars);
echo "Rehearsal maintenance mode DOWN: code {$downRes1['code']}\n";

// Execute Reset 1 with confirmation input
$execRes1 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan app:reset-pre-production --execute", $rehearsalDir, $envVars, "RESET PRE PRODUCTION DATA\n");
echo "Reset 1 Execute exit code: {$execRes1['code']}\n";
echo "Reset 1 STDOUT:\n" . trim($execRes1['stdout']) . "\n";
if (!empty($execRes1['stderr'])) echo "Reset 1 STDERR:\n" . trim($execRes1['stderr']) . "\n";

// Maintenance mode up
$upRes1 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan up", $rehearsalDir, $envVars);
echo "Rehearsal maintenance mode UP: code {$upRes1['code']}\n";

// Verify Reset 1 DB Results
DB::purge('mysql');
DB::reconnect('mysql');

$res1Counts = [
    'users_student' => DB::table('users')->where('role', 'student')->count(),
    'users_admin' => DB::table('users')->where('role', 'admin')->count(),
    'course_programs' => DB::table('course_programs')->count(),
    'course_levels' => DB::table('course_levels')->count(),
    'modules' => DB::table('modules')->count(),
    'module_materials' => DB::table('module_materials')->count(),
    'module_practices' => DB::table('module_practices')->count(),
    'final_exams' => DB::table('final_exams')->count(),
    'testimonials' => DB::table('testimonials')->count(),
    'free_tests' => DB::table('free_tests')->count(),
    'certificates' => DB::table('certificates')->count(),
    'orders' => DB::table('orders')->count(),
];

echo "Reset 1 Post-Verification Table Counts:\n";
foreach ($res1Counts as $k => $v) {
    echo "  - $k : $v\n";
}

file_put_contents("$artifactsDir/reset-1-results.json", json_encode([
    'exit_code' => $execRes1['code'],
    'counts' => $res1Counts,
    'stdout' => $execRes1['stdout'],
], JSON_PRETTY_PRINT));

if ($execRes1['code'] !== 0) {
    echo "Reset 1 FAILED! Stopping before Reset 2.\n";
    exit(1);
}

// 5. RESTORE BASELINE BEFORE RESET 2
echo "\n--- 5. RESTORE BASELINE BEFORE RESET 2 ---\n";
restoreRehearsalDatabase($targetDb, $backupFile, $mysqlBin, $dbHost, $dbPort, $dbUser, $dbPass);
config(['database.connections.mysql.database' => $targetDb]);
DB::purge('mysql');
DB::reconnect('mysql');

restoreRehearsalStorage($mainStoragePublic, $rehearsalStoragePublic);
$resCfg2 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan config:clear", $rehearsalDir, $envVars);
echo "Restored baseline DB and storage for Reset 2.\n";

// 6. RESET 2 DRY-RUN & EXECUTE
echo "\n--- 6. RESET 2 DRY-RUN & EXECUTE ---\n";

$dryRes2 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan app:reset-student-operations --dry-run", $rehearsalDir, $envVars);
echo "Reset 2 Dry-Run exit code: {$dryRes2['code']}\n";

$downRes2 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan down", $rehearsalDir, $envVars);

$execRes2 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan app:reset-student-operations --execute", $rehearsalDir, $envVars, "RESET STUDENT OPERATIONS\n");
echo "Reset 2 Execute exit code: {$execRes2['code']}\n";
echo "Reset 2 STDOUT:\n" . trim($execRes2['stdout']) . "\n";
if (!empty($execRes2['stderr'])) echo "Reset 2 STDERR:\n" . trim($execRes2['stderr']) . "\n";

$upRes2 = runRehearsalCmd(escapeshellarg($phpBin) . " artisan up", $rehearsalDir, $envVars);

DB::purge('mysql');
DB::reconnect('mysql');

$res2Counts = [
    'users_student' => DB::table('users')->where('role', 'student')->count(),
    'users_admin' => DB::table('users')->where('role', 'admin')->count(),
    'course_programs' => DB::table('course_programs')->count(),
    'course_levels' => DB::table('course_levels')->count(),
    'modules' => DB::table('modules')->count(),
    'module_materials' => DB::table('module_materials')->count(),
    'module_practices' => DB::table('module_practices')->count(),
    'final_exams' => DB::table('final_exams')->count(),
    'testimonials' => DB::table('testimonials')->count(),
    'free_tests' => DB::table('free_tests')->count(),
    'certificates' => DB::table('certificates')->count(),
    'orders' => DB::table('orders')->count(),
];

echo "Reset 2 Post-Verification Table Counts:\n";
foreach ($res2Counts as $k => $v) {
    echo "  - $k : $v\n";
}

file_put_contents("$artifactsDir/reset-2-results.json", json_encode([
    'exit_code' => $execRes2['code'],
    'counts' => $res2Counts,
    'stdout' => $execRes2['stdout'],
], JSON_PRETTY_PRINT));

// 7. PRIMARY DEVELOPMENT DB VERIFICATION (queens_english_db)
echo "\n--- 7. PRIMARY DEVELOPMENT DB VERIFICATION ---\n";
config(['database.connections.mysql.database' => 'queens_english_db']);
DB::purge('mysql');
DB::reconnect('mysql');

echo "Development Database Active: " . DB::getDatabaseName() . "\n";
foreach ($baselineTables as $tbl => $exp) {
    $act = DB::table($tbl)->count();
    $st = ($act === $exp) ? 'MATCH' : 'MISMATCH';
    echo "  - $tbl : $act (expected $exp) [$st]\n";
}

echo "\n==================================================\n";
echo "TASK-018-R1 ISOLATED REHEARSAL RERUN COMPLETED!\n";
echo "==================================================\n";
