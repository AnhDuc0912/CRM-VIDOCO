<?php

use Illuminate\Support\Facades\Route;
use Modules\Proposal\Http\Controllers\ProposalController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('proposals', ProposalController::class)->names('proposal');
});
