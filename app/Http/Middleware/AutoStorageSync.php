<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class AutoStorageSync
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->syncStorage();
        return $next($request);
    }

    protected function syncStorage()
    {
        $publicStoragePath = public_path('storage');
        $appStoragePath = storage_path('app/public');

        // Ensure public storage directory exists
        if (!File::exists($publicStoragePath)) {
            File::makeDirectory($publicStoragePath, 0755, true);
        }

        // Create symlink if it doesn't exist
        if (!File::exists($publicStoragePath)) {
            try {
                symlink($appStoragePath, $publicStoragePath);
            } catch (\Exception $e) {
                // Fallback to copy if symlink fails
                $this->recursiveCopy($appStoragePath, $publicStoragePath);
            }
        }
    }

    protected function recursiveCopy($source, $destination)
    {
        if (!File::exists($source)) {
            return;
        }

        // Ensure destination exists
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        // Copy all files and subdirectories
        $items = File::allFiles($source, true);
        
        foreach ($items as $item) {
            $relativePath = str_replace($source, '', $item->getPathname());
            $targetPath = $destination . $relativePath;

            // Ensure target directory exists
            $targetDir = dirname($targetPath);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // Copy file if it doesn't exist or is newer
            if (!File::exists($targetPath) || File::lastModified($item) > File::lastModified($targetPath)) {
                File::copy($item->getPathname(), $targetPath);
            }
        }
    }
}
