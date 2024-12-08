<?php

//JA MORANT 

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningModulesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; // Added this line
use Illuminate\Support\Facades\Route;

Route::get('/storage-link', function(){
    $targetFolder = storage_path('app/public');
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';

    // Check if symlink function exists
    if (function_exists('symlink')) {
        try {
            // Remove existing symlink or directory if it exists
            if (is_link($linkFolder)) {
                unlink($linkFolder);
            } elseif (file_exists($linkFolder)) {
                // Recursively delete directory
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($linkFolder, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($files as $fileinfo) {
                    $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                    $todo($fileinfo->getRealPath());
                }
                rmdir($linkFolder);
            }

            // Create symlink
            symlink($targetFolder, $linkFolder);
            return "Symlink created successfully";
        } catch (\Exception $e) {
            // Fallback to manual copy
            return $e->getMessage();
        }
    } else {
        // Fallback method: Manually copy files
        if (!file_exists($linkFolder)) {
            mkdir($linkFolder, 0755, true);
        }

        // Recursively copy files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($targetFolder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $fileInfo) {
            $sourcePath = $fileInfo->getPathname();
            $relativePath = str_replace($targetFolder, '', $sourcePath);
            $targetPath = $linkFolder . $relativePath;

            if ($fileInfo->isDir()) {
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                $targetDir = dirname($targetPath);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                copy($sourcePath, $targetPath);
            }
        }

        return "Files copied successfully (symlink not supported)";
    }
});
    
//  Route for the guest home
Route::get('/', [HomeController::class, 'adshow'])
    ->middleware('admin.session')
    ->name('home');

// Route for the guest home

Route::get('/home', [HomeController::class, 'adshow'])
    ->middleware('admin.session')
    ->name('mainhome');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'sendEmail'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/learning-modules', [LearningModulesController::class, 'index'])->name('learning.modules');
    Route::get('/learning-modules/{id?}', [LearningModulesController::class, 'index'])->name('learning.modules.show');
    Route::get('/learningmodules/fetch/{id}', [LearningModulesController::class, 'fetchmodule']);
});


require __DIR__.'/auth.php';
require __DIR__.'/build.php';

require __DIR__.'/admin.php';
