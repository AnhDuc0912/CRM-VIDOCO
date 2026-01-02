<?php

use Illuminate\Support\Facades\Route;
use Modules\SellOrder\Http\Controllers\SellOrderController;

Route::middleware(['auth', 'verified'])->prefix('sell-orders')->name('sell-orders.')->group(function () {
    Route::get('/', [SellOrderController::class, 'index'])->name('index');
    Route::get('/{id}', [SellOrderController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::get('/create', [SellOrderController::class, 'create'])->name('create');
    Route::post('/', [SellOrderController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [SellOrderController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SellOrderController::class, 'update'])->name('update');
    Route::delete('/{id}/files/{fileId}', [SellOrderController::class, 'removeFile'])->name('remove-file');
    Route::get('/{id}/download-files', [SellOrderController::class, 'downloadFiles'])->name('download-files');
});
