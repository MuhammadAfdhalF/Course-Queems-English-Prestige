<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = [
    'users' => 5,
    'course_programs' => 3,
    'course_levels' => 5,
    'modules' => 6,
    'module_materials' => 16,
    'module_practices' => 5,
    'final_exams' => 7,
    'migrations' => 62,
];

echo "Development Database Active: " . DB::getDatabaseName() . "\n";
echo "----------------------------------------\n";

foreach ($tables as $table => $expected) {
    $actual = DB::table($table)->count();
    $status = ($actual === $expected) ? 'MATCH' : 'MISMATCH';
    echo sprintf("%-25s : %d (expected %d) [%s]\n", $table, $actual, $expected, $status);
}
