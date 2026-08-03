<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== CERTIFICATE SETTINGS & TEMPLATES COLS ===\n\n";

echo "certificate_settings columns:\n";
print_r(Schema::getColumnListing('certificate_settings'));

echo "\ncertificate_templates columns:\n";
print_r(Schema::getColumnListing('certificate_templates'));

echo "\ncertificate_settings row:\n";
print_r(DB::table('certificate_settings')->get()->toArray());

echo "\ncertificate_templates rows:\n";
print_r(DB::table('certificate_templates')->get()->toArray());

echo "\nUser Roles:\n";
print_r(DB::table('users')->select('id', 'name', 'email', 'role')->get()->toArray());
