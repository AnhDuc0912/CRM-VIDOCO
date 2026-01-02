<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectCategory\Http\Controllers\ProjectCategoryController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('projectcategories', ProjectCategoryController::class)->names('projectcategory');
});
