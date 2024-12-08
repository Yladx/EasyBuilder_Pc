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

// Create directory junction (Windows equivalent of symlink)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    exec('mklink /J "' . str_replace('/', '\\', $linkFolder) . '" "' . str_replace('/', '\\', $targetFolder) . '"');
} else {
    symlink($targetFolder, $linkFolder);
}

echo "Storage link created successfully!";
?>
