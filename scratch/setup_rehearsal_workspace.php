<?php

$mainDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Real/queens-english-prestige';
$rehearsalDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Rehearsal/queens-english-prestige-reset-rehearsal';

echo "Setting up isolated rehearsal workspace...\n";

if (!is_dir(dirname($rehearsalDir))) {
    mkdir(dirname($rehearsalDir), 0777, true);
}

// Clone repo locally to rehearsalDir using git clone
$cmd = "git clone " . escapeshellarg($mainDir) . " " . escapeshellarg($rehearsalDir);
echo "Executing: $cmd\n";
exec($cmd, $output, $exitCode);

if ($exitCode !== 0) {
    echo "Git clone failed with exit code $exitCode\n";
    exit(1);
}

echo "Git clone successful.\n";

// Copy vendor directory if not present
if (!is_dir("$rehearsalDir/vendor") && is_dir("$mainDir/vendor")) {
    echo "Copying vendor directory...\n";
    exec("xcopy " . escapeshellarg(str_replace('/', '\\', "$mainDir/vendor")) . " " . escapeshellarg(str_replace('/', '\\', "$rehearsalDir/vendor")) . " /E /I /H /Y /Q", $outputVendor, $exitVendor);
    echo "Vendor copy completed with exit code: $exitVendor\n";
} else {
    echo "Vendor directory already present or copied.\n";
}

echo "Rehearsal workspace prepared at: $rehearsalDir\n";
