<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AuthorizationController;
use Modules\Core\Http\Controllers\TransferCustomerController;

Route::middleware('auth')->group(function () {
    Route::get('/authorization', [AuthorizationController::class, 'index'])->name('authorization');
    Route::put('/authorization/employee', [AuthorizationController::class, 'updatePermissionsEmployee'])->name('updatePermissionsEmployee');
    Route::put('/authorization/department', [AuthorizationController::class, 'updatePermissionsDepartment'])->name('updatePermissionsDepartment');

    // Transfer Customer
    Route::get('/transfer-customers', [TransferCustomerController::class, 'showForm'])->name('transfer-customers.form');
    Route::post('/transfer-customers', [TransferCustomerController::class, 'process'])->name('transfer-customers.process');
});
