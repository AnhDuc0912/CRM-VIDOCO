<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistic\Http\Controllers\StatisticController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('statistics', StatisticController::class)->names('statistic');
});
