<?php
use App\Http\Controllers\Admin\AdminAuthenticationController;
use App\Http\Controllers\Admin\Manager\Activitylog\RecentActivity;
use App\Http\Controllers\Admin\Manager\AdminController;
use App\Http\Controllers\Admin\Manager\Ads\ManageAdsController;
use App\Http\Controllers\Admin\Manager\Build\ManageBuildController;
use App\Http\Controllers\Admin\Manager\Build\RecommendedBuildForm;
use App\Http\Controllers\Admin\Manager\Modules\ManageModuleController;
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
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::post('/logout', [AdminAuthenticationController::class, 'destroy'])->name('admin.logout');
            Route::get('/dashboard/recent-activities', [RecentActivity::class, 'getRecentActivities'])
                ->name('admin.dashboard.recent-activities');
    });

    Route::get('/dashboard/session-counts', [AdminController::class, 'getSessionCounts'])
    ->name('admin.dashboard.session-counts');

    Route::get('/users/{id}', [ManageUserController::class, 'showUserInfo']);

    Route::delete('/users/{id}/delete', [ManageUserController::class, 'deleteUser'])->name('admin.users.delete');
    
    Route::get('/users', [ManageUserController::class, 'indexUsers'])->name('users.index');
    
    
    
    Route::get('/builds/buildinfo/{id}', [LoadBuild::class, 'getBuildInfo'])
        ->name('admin.builds.info');
    
    
    
    Route::get('/modules', [ManageModuleController::class, 'indexModules'])->name('modules.index')->middleware('auth.admin');
    
  
    Route::get('/modules/create', [ManageModuleController::class, 'create'])->name('modules.create');
    
    Route::post('/modules/store', [ManageModuleController::class, 'store'])->name('modules.store');
    
    
    Route::get('/modules/{id}/edit', [ManageModuleController::class, 'edit'])->name('modules.edit');
    
    Route::put('/modules/{id}', [ManageModuleController::class, 'update'])->name('modules.update');
    
    
    
    
    route::get('/manage-components', [ManageComponentController::class, 'create'])->name('components.index')->middleware('auth.admin');
    
   
    Route::get('/modules/{tag}', [ManageModuleController::class, 'getModulesByTag'])->name('modules.byTag');
    
    
    
    route::get('/builds', [ManageBuildController::class, 'indexBuilds'])->name('builds.index')->middleware('auth.admin');

 
    Route::delete('/modules/destroy/{id}', [ManageModuleController::class, 'destroy'])->name('modules.destroy');
    

});




route::get('/recommended-build-form', [RecommendedBuildForm::class, 'createRecommendedBuild'])->name('recommended.build.form');

Route::get('/activity-logs', [RecentActivity::class, 'getActivityLogs']);



Route::prefix('ads')->group(function () {
    Route::get('/', [ManageAdsController::class, 'indexAds'])->name('ads.index')->middleware('auth.admin'); // List ads
    Route::post('/store', [ManageAdsController::class, 'storeAds'])->name('ads.store'); // Create ad
    Route::delete('/{id}/delete', [ManageAdsController::class, 'destroyAds'])->name('ads.destroy'); // Delete ad
    Route::post('/{id}/toggle', [ManageAdsController::class, 'toggleAdvertise'])->name('ads.toggle'); // Toggle ad status
    Route::get('/get-add-ads-form', [ManageAdsController::class, 'getAddForm'])->name('admin.getAddAdsForm');
});





























Route::post('/add-component', [ManageComponentController::class, 'addComponent']);

// Delete route
Route::delete('/component/{type}/{id}/delete', [ManageComponentController::class, 'delete'])->name('component.delete');

Route::post('/get-component-data/{componentType}', [ManageComponentController::class, 'getComponentData']);


Route::get('/component/{type}/{id}', [ManageComponentController::class, 'edit'])->name('component.edit');

Route::post('/component/{componentType}/add', action: [ManageComponentController::class, 'store']);

Route::put('/component/{type}/{id}/update', [ManageComponentController::class, 'update']);  // For updating existing

Route::get('/component/count', [ManageComponentController::class, 'fetchcomponentcounts'])->name('admin.components.count');





Route::post('admin/builds/store', [RecommendedBuildForm::class, 'store'])->name('recbuild.store');
