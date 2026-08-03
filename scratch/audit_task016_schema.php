<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== DATABASE TABLES & FOREIGN KEYS AUDIT ===\n\n";

$tables = DB::select('SHOW TABLES');
$dbName = DB::getDatabaseName();
$tableKey = "Tables_in_" . $dbName;

$allTables = [];
foreach ($tables as $t) {
    $allTables[] = $t->$tableKey;
}

sort($allTables);

echo "Total Tables: " . count($allTables) . "\n";
echo "Tables List: " . implode(", ", $allTables) . "\n\n";

// Get Foreign Keys
$foreignKeys = DB::select("
    SELECT 
        TABLE_NAME, 
        COLUMN_NAME, 
        CONSTRAINT_NAME, 
        REFERENCED_TABLE_NAME, 
        REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = ? AND REFERENCED_TABLE_NAME IS NOT NULL
", [$dbName]);

$fkByTable = [];
foreach ($foreignKeys as $fk) {
    $fkByTable[$fk->TABLE_NAME][] = [
        'column' => $fk->COLUMN_NAME,
        'constraint' => $fk->CONSTRAINT_NAME,
        'ref_table' => $fk->REFERENCED_TABLE_NAME,
        'ref_column' => $fk->REFERENCED_COLUMN_NAME,
    ];
}

// Get FK Delete rules
$fkRules = DB::select("
    SELECT 
        TABLE_NAME, 
        CONSTRAINT_NAME, 
        REFERENCED_TABLE_NAME, 
        DELETE_RULE,
        UPDATE_RULE
    FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = ?
", [$dbName]);

$rulesByConstraint = [];
foreach ($fkRules as $r) {
    $rulesByConstraint[$r->TABLE_NAME][$r->CONSTRAINT_NAME] = [
        'on_delete' => $r->DELETE_RULE,
        'on_update' => $r->UPDATE_RULE,
    ];
}

foreach ($allTables as $table) {
    $count = DB::table($table)->count();
    echo "TABLE: {$table} (Records: {$count})\n";
    if (isset($fkByTable[$table])) {
        foreach ($fkByTable[$table] as $fk) {
            $rule = $rulesByConstraint[$table][$fk['constraint']]['on_delete'] ?? 'UNKNOWN';
            echo "  FK: {$fk['column']} -> {$fk['ref_table']}({$fk['ref_column']}) [ON DELETE: {$rule}]\n";
        }
    } else {
        echo "  No Foreign Keys pointing out.\n";
    }
    echo "\n";
}
