<?php

use Illuminate\Support\Facades\Route;
use Modules\Work\Http\Controllers\WorkController;
use Modules\Core\Enums\PermissionEnum;

Route::middleware(['auth', 'verified'])
    ->prefix('work')
    ->name('work.')
    ->group(function () {

        Route::get('/', [WorkController::class, 'index'])->name('index');
        Route::get('create', [WorkController::class, 'create'])->name('create');
        Route::post('store', [WorkController::class, 'store'])->name('store');
        Route::get('show/{id}', [WorkController::class, 'show'])->name('show');
        Route::get('edit/{id}', [WorkController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [WorkController::class, 'update'])->name('update');

        Route::get('report/{id}', [WorkController::class, 'indexReport'])->name('report.index');
        Route::get('{id}/create-report', [WorkController::class, 'createReport'])->name('report.create');
        Route::post('{id}/report', [WorkController::class, 'storeReport'])->name('report.store');
        Route::get('{work_id}/report/{report_id}/show', [WorkController::class, 'showReport'])->name('report.show');
        Route::get('{work_id}/report/{report_id}/edit', [WorkController::class, 'editReport'])->name('report.edit');
        Route::put('/report/{report_id}', [WorkController::class, 'updateReport'])->name('report.update');
        Route::get('follow-report', [WorkController::class, 'followReport'])->name('report.follow');


        Route::post('update-progress', [WorkController::class, 'updateProgress'])->name('update-progress');

        Route::get('get-groups/{project_id}', [WorkController::class, 'getGroups'])->name('getGroups');
    });
