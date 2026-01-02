<?php

use Illuminate\Support\Facades\Route;
use Modules\Position\Http\Controllers\PositionController;
use Modules\Position\Models\Position;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('positions', PositionController::class)->names('position');
    Route::get('/positions-by-level/{level}', [PositionController::class, 'getByLevel']);
});
