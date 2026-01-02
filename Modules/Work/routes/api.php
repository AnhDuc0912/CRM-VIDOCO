<?php

use Illuminate\Support\Facades\Route;
use Modules\Work\Http\Controllers\WorkController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('works', WorkController::class)->names('work');
});
