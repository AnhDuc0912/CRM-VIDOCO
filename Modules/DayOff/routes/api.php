<?php

use Illuminate\Support\Facades\Route;
use Modules\DayOff\Http\Controllers\DayOffController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('dayoffs', DayOffController::class)->names('dayoff');
});
