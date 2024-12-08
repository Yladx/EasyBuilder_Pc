<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;

class StorageController extends Controller
{
    public function setupStorage()
    {
        try {
            // Define paths
            $publicPath = base_path('public');
            $storagePath = storage_path('app/public');
            $publicStoragePath = $publicPath . '/storage';

            // Create directories if they don't exist
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            if (!file_exists($publicStoragePath)) {
                mkdir($publicStoragePath, 0755, true);
            }

            // Get all files from storage/app/public
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $copied = 0;
            $created = 0;

            foreach ($files as $fileInfo) {
                $sourcePath = $fileInfo->getPathname();
                $relativePath = str_replace($storagePath, '', $sourcePath);
                $targetPath = $publicStoragePath . $relativePath;

                if ($fileInfo->isDir()) {
                    if (!file_exists($targetPath)) {
                        mkdir($targetPath, 0755, true);
                        $created++;
                    }
                } else {
                    $targetDir = dirname($targetPath);
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0755, true);
                        $created++;
                    }
                    
                    if (!file_exists($targetPath) || filemtime($sourcePath) > filemtime($targetPath)) {
                        copy($sourcePath, $targetPath);
                        $copied++;
                    }
                }
            }

            // Create a .gitignore in public/storage
            $gitignorePath = $publicStoragePath . '/.gitignore';
            if (!file_exists($gitignorePath)) {
                file_put_contents($gitignorePath, "*\n!.gitignore\n");
            }

            return response()->json([
                'success' => true,
                'message' => "Storage setup completed successfully!",
                'details' => [
                    'directories_created' => $created,
                    'files_copied' => $copied
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error setting up storage: " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
