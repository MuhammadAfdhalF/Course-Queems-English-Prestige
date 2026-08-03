<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$pdo = DB::connection()->getPdo();
echo "Active DB Name: " . DB::getDatabaseName() . "\n";
echo "PDO Connection Attributes:\n";
$config = config('database.connections.mysql');
print_r($config);
