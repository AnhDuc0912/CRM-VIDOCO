<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AuthorizationController;

Route::middleware('auth')->group(function () {
    Route::get('/authorization', [AuthorizationController::class, 'index'])->name('authorization');
    Route::put('/authorization/employee', [AuthorizationController::class, 'updatePermissionsEmployee'])->name('updatePermissionsEmployee');
    Route::put('/authorization/department', [AuthorizationController::class, 'updatePermissionsDepartment'])->name('updatePermissionsDepartment');
});
