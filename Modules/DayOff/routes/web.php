<?php

use Illuminate\Support\Facades\Route;
use Modules\DayOff\Http\Controllers\DayOffController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('dayoff', DayOffController::class)->names('dayoff');
    Route::post('dayoff/{id}/approve', [DayOffController::class, 'approve'])->name('dayoff.approve');
    Route::post('dayoff/{id}/reject', [DayOffController::class, 'reject'])->name('dayoff.reject');
});
