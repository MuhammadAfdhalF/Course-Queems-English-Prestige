<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== TESTIMONIALS SCHEMA & FK AUDIT ===\n";
print_r(DB::select("SHOW CREATE TABLE testimonials"));

echo "\n=== SESSIONS SCHEMA & USER_ID AUDIT ===\n";
print_r(DB::select("SHOW CREATE TABLE sessions"));
echo "Session record count: " . DB::table('sessions')->count() . "\n";
print_r(DB::table('sessions')->select('id', 'user_id', 'last_activity')->get()->toArray());

echo "\n=== PASSWORD_RESET_TOKENS SCHEMA & EMAIL AUDIT ===\n";
print_r(DB::select("SHOW CREATE TABLE password_reset_tokens"));
echo "Password reset token count: " . DB::table('password_reset_tokens')->count() . "\n";

echo "\n=== CERTIFICATES FILE FIELD AUDIT ===\n";
print_r(DB::select("SHOW CREATE TABLE certificates"));
echo "Certificates sample rows:\n";
print_r(DB::table('certificates')->select('id', 'student_id', 'certificate_number', 'verification_token', 'status')->get()->toArray());

echo "\n=== COURSE / FREE TEST FILE FIELDS AUDIT ===\n";
echo "course_levels file columns:\n";
print_r(Schema::getColumnListing('course_levels'));
echo "module_materials file columns:\n";
print_r(Schema::getColumnListing('module_materials'));
echo "information_posts file columns:\n";
print_r(Schema::getColumnListing('information_posts'));
