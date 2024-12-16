<?php
use App\Http\Controllers\Admin\AdminAuthenticationController;
use App\Http\Controllers\Admin\Manager\Activitylog\RecentActivity;
use App\Http\Controllers\Admin\Manager\AdminController;
use App\Http\Controllers\Admin\Manager\Ads\ManageAdsController;
use App\Http\Controllers\Admin\Manager\Build\ManageBuildController;
use App\Http\Controllers\Admin\Manager\Build\RecommendedBuildForm;
use App\Http\Controllers\Admin\Manager\Modules\ManageModuleController;
use App\Http\Controllers\Admin\Manager\Activitylog\ManageActivityLogController;
use App\Http\Controllers\Admin\Manager\PartsComponent\ManageComponentController;
use App\Http\Controllers\Admin\Manager\User\ManageUserController;
use App\Http\Controllers\Build\BuildController;
use App\Http\Controllers\Build\LoadBuild;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Build\BuildCompatability;
use Illuminate\Http\Request;

Route::prefix('admin')->group(function () {

    Route::post('/verify-admin-key', function (Request $request) {
        try {
            $key = $request->input('key');

            if (empty($key) || !is_string($key)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid key format'
                ], 400);
            }

            $valid = $key === env('ADMIN_KEY');

            if ($valid) {
                $request->session()->put('admin_key_verified', true);
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid admin key'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    })->middleware('web')->name('admin.verify-key');

    Route::get('/login', [AdminAuthenticationController::class, 'create'])
        ->middleware('check.user','admin.key')
        ->name('admin.login');

    Route::post('/login', [AdminAuthenticationController::class, 'store'])->name('admin.login.submit');

    Route::middleware('auth.admin')->group(function () {

            // Dashboard Routes
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('/dashboard/recent-activities', [RecentActivity::class, 'getRecentActivities'])
                ->name('admin.dashboard.recent-activities');
            Route::get('/dashboard/session-counts', [AdminController::class, 'getSessionCounts'])
                ->name('admin.dashboard.session-counts');

            Route::post('/logout', [AdminAuthenticationController::class, 'destroy'])->name('admin.logout');

            // Activity Logs Routes
            Route::get('/activity-logs', [ManageActivityLogController::class, 'getActivityLogs'])->name('admin.activity-logs');
            Route::get('/activity-logs/data', [ManageActivityLogController::class, 'getActivityLogsData'])->name('admin.activity-logs.data');
            Route::get('/activity-logs', [RecentActivity::class, 'getActivityLogs']);
            Route::get('/manage-activity-logs', [ManageActivityLogController::class, 'getActivityLogs'])->name('activity-logs.index')->middleware(['auth.admin']);
            
   
            // Manage Modules Routes
            Route::get('/manage-modules', [ManageModuleController::class, 'indexModules'])->name('modules.index')->middleware('auth.admin');
            Route::get('/modules/create', [ManageModuleController::class, 'create'])->name('modules.create'); 
            Route::post('/modules/store', [ManageModuleController::class, 'store'])->name('modules.store');  
            Route::get('/modules/{id}/edit', [ManageModuleController::class, 'edit'])->name('modules.edit'); 
            Route::put('/modules/{id}', [ManageModuleController::class, 'update'])->name('modules.update'); 
            Route::delete('/modules/destroy/{id}', [ManageModuleController::class, 'destroy'])->name('modules.destroy');
            Route::get('/modules/{tag}', [ManageModuleController::class, 'getModulesByTag'])->name('modules.byTag');
    
    
             // Manage Users Routes
            Route::get('/users/{id}', [ManageUserController::class, 'showUserInfo']);
            Route::delete('/users/{id}/delete', [ManageUserController::class, 'deleteUser'])->name('admin.users.delete');      
            Route::get('/users', [ManageUserController::class, 'indexUsers'])->name('users.index');
            
            
    
  
        Route::get('/manage-components', [ManageComponentController::class, 'create'])->name('components.index');
    
   
        // Build Routes   
       Route::get('/manage-builds', [ManageBuildController::class, 'indexBuilds'])->name('builds.index')->middleware('auth.admin');

 
   
        // Ads Routes
        Route::get('/manage-ads', [ManageAdsController::class, 'indexAds'])->name('ads.index')->middleware('auth.admin'); // List ads
        
        Route::prefix('ads')->group(function () {
            Route::post('/store', [ManageAdsController::class, 'storeAds'])->name('ads.store'); // Create ad
            Route::delete('/{id}/delete', [ManageAdsController::class, 'destroyAds'])->name('ads.destroy'); // Delete ad
            Route::post('/{id}/toggle', [ManageAdsController::class, 'toggleAdvertise'])->name('ads.toggle'); // Toggle ad status
            Route::get('/get-add-ads-form', [ManageAdsController::class, 'getAddForm'])->name('admin.getAddAdsForm');
        });

  
   

        Route::prefix('components')->group(function () {
            Route::post('/get-data/{componentType}', [ManageComponentController::class, 'getComponentData']);
        
            Route::post('/add', [ManageComponentController::class, 'addComponent']);
            
            Route::delete('/{type}/{id}/delete', [ManageComponentController::class, 'delete'])->name('component.delete');
            
            Route::get('/{type}/{id}', [ManageComponentController::class, 'edit'])->name('component.edit');
            
            Route::post('/{componentType}/add', action: [ManageComponentController::class, 'store']);
            
            Route::put('/{type}/{id}/update', [ManageComponentController::class, 'update']);  // For updating existing
            
            Route::get('/counts', [ManageComponentController::class, 'fetchcomponentcounts'])->name('admin.components.count');
            Route::get('/get-columns/{table}', [ManageComponentController::class, 'getColumns']);

            
        });
        

        });


    
});
             
Route::get('/recommended-build-form', [RecommendedBuildForm::class, 'createRecommendedBuild'])->name('recommended.build.form');

Route::post('admin/builds/store', [RecommendedBuildForm::class, 'store'])->name('recbuild.store');
