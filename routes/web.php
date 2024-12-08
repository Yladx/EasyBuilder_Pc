<?php

//JA MORANT 

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningModulesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; // Added this line
use Illuminate\Support\Facades\Route;

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

// Automatic Storage Sync Route
Route::get('/storage-sync', function () {
    $publicStoragePath = public_path('storage');
    $appStoragePath = storage_path('app/public');

    // Remove existing symlink or directory
    if (is_link($publicStoragePath) || file_exists($publicStoragePath)) {
        if (is_link($publicStoragePath)) {
            unlink($publicStoragePath);
        } else {
            // Recursively delete directory
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
            rmdir($publicStoragePath);
        }
    }

    // Create symlink
    try {
        symlink($appStoragePath, $publicStoragePath);
        return response()->json(['status' => 'Symlink created successfully']);
    } catch (\Exception $e) {
        // Fallback to manual copy if symlink fails
        if (!file_exists($publicStoragePath)) {
            mkdir($publicStoragePath, 0755, true);
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($appStoragePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $fileInfo) {
            $sourcePath = $fileInfo->getPathname();
            $relativePath = str_replace($appStoragePath, '', $sourcePath);
            $targetPath = $publicStoragePath . $relativePath;

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

        return response()->json(['status' => 'Files copied successfully']);
    }
})->name('storage.sync');

// Trigger storage sync on every request
Route::middleware(['web'])->group(function () {
    Route::get('*', function () {
        try {
            $publicStoragePath = public_path('storage');
            $appStoragePath = storage_path('app/public');

            if (!is_link($publicStoragePath) && !file_exists($publicStoragePath)) {
                // Create symlink or copy files
                if (function_exists('symlink')) {
                    symlink($appStoragePath, $publicStoragePath);
                } else {
                    // Fallback copy method
                    if (!file_exists($publicStoragePath)) {
                        mkdir($publicStoragePath, 0755, true);
                    }

                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($appStoragePath, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                    );

                    foreach ($files as $fileInfo) {
                        $sourcePath = $fileInfo->getPathname();
                        $relativePath = str_replace($appStoragePath, '', $sourcePath);
                        $targetPath = $publicStoragePath . $relativePath;

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
                }
            }
        } catch (\Exception $e) {
            // Silently handle errors
            \Log::error('Storage sync error: ' . $e->getMessage());
        }
    });
});

require __DIR__.'/admin.php';
