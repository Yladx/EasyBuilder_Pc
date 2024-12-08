<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function createStorageStructure() {
    // Define paths
    $publicPath = __DIR__ . '/public';
    $storagePath = __DIR__ . '/storage/app/public';
    $publicStoragePath = $publicPath . '/storage';

    try {
        // Create directories if they don't exist
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
            echo "Created storage directory<br>";
        }

        if (!file_exists($publicStoragePath)) {
            mkdir($publicStoragePath, 0755, true);
            echo "Created public storage directory<br>";
        }

        // Get all files from storage/app/public
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storagePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $fileInfo) {
            $sourcePath = $fileInfo->getPathname();
            $relativePath = str_replace($storagePath, '', $sourcePath);
            $targetPath = $publicStoragePath . $relativePath;

            if ($fileInfo->isDir()) {
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0755, true);
                    echo "Created directory: " . $relativePath . "<br>";
                }
            } else {
                $targetDir = dirname($targetPath);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                if (!file_exists($targetPath) || filemtime($sourcePath) > filemtime($targetPath)) {
                    copy($sourcePath, $targetPath);
                    echo "Copied file: " . $relativePath . "<br>";
                }
            }
        }

        // Create a .gitignore in public/storage
        $gitignorePath = $publicStoragePath . '/.gitignore';
        if (!file_exists($gitignorePath)) {
            file_put_contents($gitignorePath, "*\n!.gitignore\n");
            echo "Created .gitignore in public/storage<br>";
        }

        echo "<br>Storage setup completed successfully!<br>";
        echo "Remember to delete this file after successful execution.<br>";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

// Execute the function
createStorageStructure();
?>
