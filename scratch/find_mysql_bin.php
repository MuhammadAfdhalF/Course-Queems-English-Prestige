<?php

$laragonPath = 'D:/APK/laragon/bin/mysql';
$files = glob("$laragonPath/*/bin/mysqldump.exe");
if (empty($files)) {
    $files = glob("C:/laragon/bin/mysql/*/bin/mysqldump.exe");
}

foreach ($files as $f) {
    echo "Found mysqldump: $f\n";
    $mysqlBin = dirname($f) . '/mysql.exe';
    echo "Found mysql: $mysqlBin\n";
}
