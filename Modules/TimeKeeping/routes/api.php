<?php

use Illuminate\Support\Facades\Route;
use Modules\TimeKeeping\Http\Controllers\TimeKeepingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('timekeepings', TimeKeepingController::class)->names('timekeeping');
});
