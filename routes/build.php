<?php

use App\Http\Controllers\Build\BuildCompatability;
use App\Http\Controllers\Build\BuildController;
use App\Http\Controllers\Build\LoadBuild;
use App\Http\Controllers\Build\RateBuild;
use App\Http\Controllers\Admin\Manager\Build\RecommendedBuildForm;
use App\Models\Build;
use Illuminate\Support\Facades\Route;

Route::prefix('builds')->group(function () {

    Route::get('/{tag?}', [LoadBuild::class, 'fetchBuilds'])
        ->name('builds.display');

    Route::get('/buildinfo/{id}', [LoadBuild::class, 'getbuildinfo'])
        ->name('builds.info');

    Route::put('/update/{id}', [BuildController::class, 'update'])->name('builds.update');

    Route::delete('/delete/{id}', [BuildController::class, 'deleteBuild'])->name('builds.delete');

    Route::post('/rate', [RateBuild::class, 'store'])->name('rate.build');

});


Route::get('/manage-builds', [LoadBuild::class, 'loaduserbuilds'])->middleware('auth')->name('manage.build');

Route::get('/build/build-pc', [BuildController::class, 'buildPc'])->name('build.build-pc')->middleware('auth');

Route::post('/build/store', [RecommendedBuildForm::class, 'store'])->name('build.store')->middleware('auth');


Route::prefix('build-compatibility')->group(function () {
    // Fetch compatible CPUs for a motherboard
    Route::get('/compatible-cpus/{motherboardId}', [BuildCompatability::class, 'getCompatibleCpus']);

    // Fetch compatible GPUs for a motherboard
    Route::get('/compatible-gpus/{motherboardId}', [BuildCompatability::class, 'getCompatibleGpus']);

    // Fetch compatible RAMs for a motherboard
    Route::get('/compatible-rams/{motherboardId}', [BuildCompatability::class, 'getCompatibleRams']);

    // Fetch compatible storages for a motherboard
    Route::get('/compatible-storages/{motherboardId}', [BuildCompatability::class, 'getCompatibleStorages']);

    // Fetch compatible power supplies
    Route::post('/compatible-power-supplies', [BuildCompatability::class, 'getCompatiblePowerSupplies']);

    // Fetch compatible cases based on motherboard and GPU
    Route::get('/compatible-cases/{motherboardId}/{gpuId}', [BuildCompatability::class, 'getCompatibleCases']);
});


