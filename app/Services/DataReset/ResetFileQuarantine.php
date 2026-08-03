<?php

namespace App\Services\DataReset;

use Illuminate\Support\Facades\Storage;

class ResetFileQuarantine
{
    public static function quarantineFiles(
        array $certificateRelativePaths,
        array $testimonialRelativePaths,
        string $resetType,
        string $timestamp
    ): array {
        $quarantineBase = "data-reset-quarantine/{$timestamp}/{$resetType}";
        $manifest = [
            'reset_type' => $resetType,
            'timestamp' => $timestamp,
            'certificates' => [],
            'testimonials' => [],
            'warnings' => [],
            'quarantined_count' => 0,
        ];

        // Process generated Student certificates
        foreach ($certificateRelativePaths as $relPath) {
            if (!$relPath) continue;

            $normalized = ltrim(str_replace('\\', '/', $relPath), '/');

            // Safe path check: must start with certificates/ and contain no path traversal
            if (!str_starts_with($normalized, 'certificates/') || str_contains($normalized, '..')) {
                $manifest['warnings'][] = "Rejected unsafe certificate path: {$relPath}";
                continue;
            }

            $res = self::moveFileToQuarantine($normalized, "{$quarantineBase}/{$normalized}");
            $manifest['certificates'][] = $res;
            if ($res['status'] === 'quarantined') {
                $manifest['quarantined_count']++;
            } elseif (isset($res['warning'])) {
                $manifest['warnings'][] = $res['warning'];
            }
        }

        // Process testimonial photo files
        foreach ($testimonialRelativePaths as $relPath) {
            if (!$relPath) continue;

            $normalized = ltrim(str_replace('\\', '/', $relPath), '/');

            // Safe path check: must start with testimonials/ and contain no path traversal
            if (!str_starts_with($normalized, 'testimonials/') || str_contains($normalized, '..')) {
                $manifest['warnings'][] = "Rejected unsafe testimonial path: {$relPath}";
                continue;
            }

            $res = self::moveFileToQuarantine($normalized, "{$quarantineBase}/{$normalized}");
            $manifest['testimonials'][] = $res;
            if ($res['status'] === 'quarantined') {
                $manifest['quarantined_count']++;
            } elseif (isset($res['warning'])) {
                $manifest['warnings'][] = $res['warning'];
            }
        }

        // Write manifest to local disk
        $manifestPath = "{$quarantineBase}/manifest.json";
        Storage::disk('local')->put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
        $manifest['manifest_file'] = Storage::disk('local')->path($manifestPath);

        return $manifest;
    }

    protected static function moveFileToQuarantine(string $publicRelPath, string $localQuarantineRelPath): array
    {
        $publicDisk = Storage::disk('public');
        $localDisk = Storage::disk('local');

        if (!$publicDisk->exists($publicRelPath)) {
            return [
                'source_path' => $publicRelPath,
                'status' => 'missing',
                'warning' => "Source file missing on public disk: {$publicRelPath}",
            ];
        }

        try {
            $stream = $publicDisk->readStream($publicRelPath);
            if (!$stream) {
                return [
                    'source_path' => $publicRelPath,
                    'status' => 'read_failed',
                    'warning' => "Failed to open read stream: {$publicRelPath}",
                ];
            }

            $localDisk->writeStream($localQuarantineRelPath, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            if ($localDisk->exists($localQuarantineRelPath)) {
                $sourceHash = hash('sha256', $publicDisk->get($publicRelPath));
                $targetHash = hash('sha256', $localDisk->get($localQuarantineRelPath));

                if ($sourceHash === $targetHash) {
                    $publicDisk->delete($publicRelPath);
                    return [
                        'source_path' => $publicRelPath,
                        'quarantine_path' => $localQuarantineRelPath,
                        'size' => $localDisk->size($localQuarantineRelPath),
                        'sha256' => $targetHash,
                        'status' => 'quarantined',
                    ];
                }
            }

            return [
                'source_path' => $publicRelPath,
                'status' => 'hash_mismatch',
                'warning' => "Hash mismatch during quarantine for {$publicRelPath}. Source retained.",
            ];
        } catch (\Throwable $e) {
            return [
                'source_path' => $publicRelPath,
                'status' => 'error',
                'warning' => "File quarantine error for {$publicRelPath}: " . $e->getMessage(),
            ];
        }
    }
}
