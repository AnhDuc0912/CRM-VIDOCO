<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectCategory\Http\Controllers\ProjectCategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projectcategories', ProjectCategoryController::class)->names('projectcategory');
});
