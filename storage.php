<?php
// Create the symbolic link for storage
$targetFolder = __DIR__.'/storage/app/public';
$linkFolder = __DIR__.'/public/storage';

// Remove existing symlink if it exists
if (file_exists($linkFolder)) {
    if (is_dir($linkFolder)) {
        rmdir($linkFolder);
    } else {
        unlink($linkFolder);
    }
}

function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    if (!file_exists($dst)) {
        mkdir($dst, 0777, true);
    }
    
    while (($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recursiveCopy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Remove existing directory if it exists
if (file_exists($linkFolder)) {
    if (is_dir($linkFolder)) {
        // Remove directory and its contents
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($linkFolder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $fileinfo) {
            if ($fileinfo->isDir()) {
                rmdir($fileinfo->getRealPath());
            } else {
                unlink($fileinfo->getRealPath());
            }
        }
        rmdir($linkFolder);
    } else {
        unlink($linkFolder);
    }
}

// Create directory and copy contents
recursiveCopy($targetFolder, $linkFolder);

echo "Storage files copied successfully! Your public storage is now accessible.";
?>
