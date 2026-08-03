<?php

$mainDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Real/queens-english-prestige';
$rehearsalDir = 'D:/Kerja File/Freelance/E-Course Queens/Coding/Rehearsal/queens-english-prestige-reset-rehearsal';

echo "Updating rehearsal workspace repository to commit 38e1440...\n";

// Fetch and reset rehearsal git repo
$cmd = "cd " . escapeshellarg($rehearsalDir) . " && git fetch origin && git reset --hard 38e1440";
exec($cmd, $out, $code);

if ($code !== 0) {
    // Re-clone if fetch fails
    echo "Git reset failed ($code), re-cloning...\n";
    exec("rmdir /s /q " . escapeshellarg(str_replace('/', '\\', $rehearsalDir)), $outRm, $codeRm);
    exec("git clone " . escapeshellarg($mainDir) . " " . escapeshellarg($rehearsalDir), $outClone, $codeClone);
}

exec("cd " . escapeshellarg($rehearsalDir) . " && git log -1 --oneline", $outLog, $codeLog);
echo "Rehearsal repo updated: " . implode("\n", $outLog) . "\n";
