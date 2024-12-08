<?php
// Create the symbolic link for storage
$targetFolder = __DIR__.'/storage/app/public';
$linkFolder = __DIR__.'/public/storage';

if (file_exists($linkFolder)) {
    unlink($linkFolder);
}

symlink($targetFolder, $linkFolder);
echo "Symbolic link created successfully!";
?>
