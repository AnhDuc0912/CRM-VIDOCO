<?php

use Illuminate\Support\Facades\Route;
use Modules\SellContract\Http\Controllers\SellContractController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sellcontracts', SellContractController::class)->names('sellcontract');
});
