<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Controllers\ProjectController;
use Modules\Work\Http\Controllers\WorkController;

Route::middleware(['auth', 'verified'])
    ->prefix('project')
    ->name('project.')
    ->group(function () {
        Route::get('create', [ProjectController::class, 'create'])->name('create');
        Route::post('store', [ProjectController::class, 'store'])->name('store');
        Route::get('index', [ProjectController::class, 'index'])->name('index');
        Route::get('show/{id}', [ProjectController::class, 'show'])->name('show');
        Route::get('edit/{id}', [ProjectController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [ProjectController::class, 'update'])->name('update');
        Route::post('upload-file', [ProjectController::class, 'uploadFile'])->name('upload-file');
        Route::post('/project/{id}/status', [ProjectController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/project/{id}/add-member', [ProjectController::class, 'addMember'])->name('addMember');
        Route::post('update-progress', [ProjectController::class, 'updateProgress'])->name('update-progress');
        Route::get('project/{project_id}/reports', [WorkController::class, 'projectReports'])->name('reports');
    });
