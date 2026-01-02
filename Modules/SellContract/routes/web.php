<?php

use Illuminate\Support\Facades\Route;
use Modules\SellContract\Http\Controllers\SellContractController;

Route::middleware(['auth', 'verified'])->prefix('sell-contracts')->name('sell-contracts.')->group(function () {
    Route::get('/', [SellContractController::class, 'index'])->name('index');
    Route::get('/{id}', [SellContractController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::get('/create', [SellContractController::class, 'create'])->name('create');
    Route::post('/', [SellContractController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [SellContractController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SellContractController::class, 'update'])->name('update');
    Route::delete('/{id}/files/{fileId}', [SellContractController::class, 'removeFile'])->name('remove-file');
    Route::get('/{id}/download-files', [SellContractController::class, 'downloadFiles'])->name('download-files');
    Route::put('/{id}/convert-to-order', [SellContractController::class, 'convertToOrder'])->name('convert-to-order');
});
