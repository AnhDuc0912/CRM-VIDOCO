<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\CustomerController;
use Modules\Customer\Http\Controllers\CustomerSourceController;

Route::middleware(['auth', 'verified'])->prefix('customers')->name('customers.')->group(function () {
    Route::get('/notification', [CustomerController::class, 'notification'])->name('notification');
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/', [CustomerController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    Route::delete('/{id}/remove-file/{fileId}', [CustomerController::class, 'removeFile'])->name('remove-file');
    Route::get('/{id}/download-files', [CustomerController::class, 'downloadFiles'])->name('download-files');

    //ajax
    Route::get('/{id}/ajax', [CustomerController::class, 'showAjax'])->name('ajax.show');
});

Route::middleware(['auth', 'verified'])->name('customer-sources.')->prefix('customer-sources')->group(function () {
    Route::get('/', [CustomerSourceController::class, 'index'])->name('index');
    Route::get('/create', [CustomerSourceController::class, 'create'])->name('create');
    Route::post('/', [CustomerSourceController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CustomerSourceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerSourceController::class, 'update'])->name('update');
    Route::delete('/{id}', [CustomerSourceController::class, 'destroy'])->name('destroy');
});
