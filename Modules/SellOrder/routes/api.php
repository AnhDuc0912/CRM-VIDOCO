<?php

use Illuminate\Support\Facades\Route;
use Modules\SellOrder\Http\Controllers\SellOrderController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sellorders', SellOrderController::class)->names('sellorder');
});
