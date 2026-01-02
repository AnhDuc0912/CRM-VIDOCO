<?php

use Illuminate\Support\Facades\Route;
use Modules\TimeKeeping\Http\Controllers\TimeKeepingController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/checkin-status', [TimeKeepingController::class, 'checkinStatus'])
        ->name('checkin.status');

    Route::post('/checkin', [TimeKeepingController::class, 'checkin'])->name('checkin');
    Route::post('/checkout', [TimeKeepingController::class, 'checkout'])->name('checkout');

    Route::prefix('/admin/timekeeping')->group(function () {
        Route::get('/', [TimeKeepingController::class, 'index'])->name('timekeeping.index');
        Route::get('/{id}/edit', [TimeKeepingController::class, 'edit'])->name('timekeeping.edit');
        Route::put('/{id}', [TimeKeepingController::class, 'update'])->name('timekeeping.update');
        Route::delete('/{id}', [TimeKeepingController::class, 'destroy'])->name('timekeeping.destroy');
        Route::get('/monthly', [TimeKeepingController::class, 'monthly'])->name('timekeeping.monthly');
    });
});
