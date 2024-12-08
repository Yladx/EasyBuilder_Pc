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

// Storage setup route - Only accessible in local environment
Route::get('/storage-setup', [App\Http\Controllers\StorageController::class, 'setupStorage'])
    ->middleware(['auth', 'admin'])
    ->name('storage.setup');

require __DIR__.'/auth.php';
require __DIR__.'/build.php';

require __DIR__.'/admin.php';
