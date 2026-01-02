<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;
use Modules\Category\Http\Controllers\CategoryServiceController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/{categoryId}', [CategoryController::class, 'show'])->name('show')->where('categoryId', '[0-9]+');
        Route::get('/{categoryId}/edit', [CategoryController::class, 'edit'])->name('edit')->where('categoryId', '[0-9]+');
        Route::put('/{categoryId}/update', [CategoryController::class, 'update'])->name('update')->where('categoryId', '[0-9]+');
        Route::get('/{categoryId}/download-files', [CategoryController::class, 'downloadFiles'])->name('download-files')->where('categoryId', '[0-9]+');
        Route::delete('/delete-file/{fileId}', [CategoryController::class, 'deleteFile'])->name('delete-file')->where('fileId', '[0-9]+');
        Route::delete('/{categoryId}', [CategoryController::class, 'destroy'])
            ->name('destroy')->where('categoryId', '[0-9]+');
    });

    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [CategoryServiceController::class, 'index'])->name('index');
        Route::get('/create', [CategoryServiceController::class, 'create'])->name('create');
        Route::post('/store', [CategoryServiceController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryServiceController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [CategoryServiceController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', [CategoryServiceController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [CategoryServiceController::class, 'destroy'])
            ->name('destroy')->where('id', '[0-9]+');
    });
});
