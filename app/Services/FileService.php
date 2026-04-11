<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class FileService
{
    /**
     * Store a file and synchronize it to the public directory.
     * Useful for shared hosting where symbolic links are not supported.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string|null
     */
    public static function storeAndSync(UploadedFile $file, string $directory, string $disk = 'public'): ?string
    {
        // 1. Store the file in the storage directory
        $path = $file->store($directory, $disk);

        if ($path) {
            // 2. Synchronize to the public directory
            self::syncToPublic($path, $disk);
        }

        return $path;
    }

    /**
     * Synchronize a file from storage to the public directory.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public static function syncToPublic(string $path, string $disk = 'public'): bool
    {
        // Use storage_path to avoid linter errors with Storage::disk()->path()
        $storagePath = $disk === 'public' 
            ? storage_path('app/public/' . $path) 
            : storage_path('app/' . $path);

        // Copy to all candidate public paths used across shared-hosting setups.
        $targetPaths = array_unique([
            public_path('storage/' . $path),
            base_path('storage/' . $path),
        ]);

        // Copy the file if it exists in storage
        if (File::exists($storagePath)) {
            $copied = false;

            foreach ($targetPaths as $targetPath) {
                $targetDirectory = dirname($targetPath);

                if (!File::exists($targetDirectory)) {
                    File::makeDirectory($targetDirectory, 0755, true);
                }

                $copied = File::copy($storagePath, $targetPath) || $copied;
            }

            return $copied;
        }

        return false;
    }

    /**
     * Delete a file from both storage and public directories.
     *
     * @param string|null $path
     * @param string $disk
     * @return void
     */
    public static function deleteAndSync(?string $path, string $disk = 'public'): void
    {
        if (!$path) {
            return;
        }

        // 1. Delete from storage
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }

        // 2. Delete from all synced public paths
        $targetPaths = array_unique([
            public_path('storage/' . $path),
            base_path('storage/' . $path),
        ]);

        foreach ($targetPaths as $targetPath) {
            if (File::exists($targetPath)) {
                File::delete($targetPath);
            }
        }
    }
}
