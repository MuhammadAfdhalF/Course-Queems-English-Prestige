<?php

$rehearsalDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Rehearsal/queens-english-prestige-reset-rehearsal';
$phpBin = 'D:/APK/laragon/bin/php/php-8.3.16-Win32-vs16-x64/php.exe';

$cmd = "cd " . escapeshellarg($rehearsalDir) . " && " . escapeshellarg($phpBin) . " artisan app:reset-pre-production --dry-run";

$envVars = array_merge($_SERVER, $_ENV, [
    'APP_ENV' => 'reset-testing',
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'queens_english_reset_test',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
    'CACHE_DRIVER' => 'database',
    'CACHE_STORE' => 'database',
    'QUEUE_CONNECTION' => 'database',
    'SESSION_DRIVER' => 'database',
    'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
    'PATH' => getenv('PATH'),
]);

$descriptorspec = [
   0 => ["pipe", "r"],
   1 => ["pipe", "w"],
   2 => ["pipe", "w"]
];

$process = proc_open($cmd, $descriptorspec, $pipes, $rehearsalDir, $envVars);

if (is_resource($process)) {
    fclose($pipes[0]);
    $out = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $code = proc_close($process);
    echo "Code: $code\n";
    echo "STDOUT:\n$out\n";
    if (!empty($err)) echo "STDERR:\n$err\n";
}
