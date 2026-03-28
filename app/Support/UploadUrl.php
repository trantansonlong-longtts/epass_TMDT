<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class UploadUrl
{
    public static function forPath(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback ? asset($fallback) : null;
        }

        foreach (self::candidateDisks() as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->url($path);
            }
        }

        $configuredDisk = (string) config('filesystems.upload_disk', 'public');

        return Storage::disk($configuredDisk)->url($path);
    }

    /**
     * @return array<int, string>
     */
    private static function candidateDisks(): array
    {
        $configuredDisk = (string) config('filesystems.upload_disk', 'public');

        return array_values(array_unique([
            $configuredDisk,
            'public',
            'public_uploads',
        ]));
    }
}
