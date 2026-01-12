<?php

use Illuminate\Support\Facades\Route;
use Modules\Proposal\Http\Controllers\ProposalController;

Route::middleware(['auth', 'verified'])->prefix('proposals')->name('proposals.')->group(function () {
    Route::get('/', [ProposalController::class, 'index'])->name('index');
    Route::get('/create', [ProposalController::class, 'create'])->name('create');
    Route::post('/store', [ProposalController::class, 'store'])->name('store');
    Route::get('/{id}/download-files', [ProposalController::class, 'downloadFiles'])->name('download-files');
    Route::put('/{id}/convert-to-order', [ProposalController::class, 'convertToOrder'])->name('convert-to-order');
    Route::put('/{id}/convert-to-contract', [ProposalController::class, 'convertToContract'])->name('convert-to-contract');
    Route::put('/{id}/reject-redo', [ProposalController::class, 'rejectRedo'])->name('reject-redo');
    Route::get('/{id}', [ProposalController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ProposalController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProposalController::class, 'update'])->name('update');
    Route::delete('/{id}/remove-file/{fileId}', [ProposalController::class, 'removeFile'])->name('remove-file');
    Route::get('/{id}/ajax/show', [ProposalController::class, 'ajaxShow'])->name('ajax.show');
});
