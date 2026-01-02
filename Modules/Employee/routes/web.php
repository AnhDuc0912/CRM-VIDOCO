<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Controllers\EmployeeController;

// Protected routes
Route::middleware('auth')->group(function () {
    // Employees routes
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/info/{id}', [EmployeeController::class, 'info'])->name('info');
        Route::put('/update-password/{userId}', [EmployeeController::class, 'updatePassword'])
            ->name('update-password');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('update');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [EmployeeController::class, 'delete'])->name('delete');

        Route::get('/{employeeId}/permissions', [EmployeeController::class, 'getEmployeePermissions'])
            ->name('getEmployeePermissions');
        Route::post('/update-status', [EmployeeController::class, 'updateStatus'])
            ->name('update-status');

        // Password setup routes
        Route::post('/{employeeId}/send-password-setup', [EmployeeController::class, 'sendPasswordSetupEmail'])
            ->name('send-password-setup');

        Route::delete('/remove-file/{employeeId}/{fileId}', [EmployeeController::class, 'removeFile'])
            ->name('remove-file');

        Route::get('/{employeeId}/download-other-files', [EmployeeController::class, 'downloadOtherFiles'])
            ->name('download-other-files');

        Route::get('/search', [EmployeeController::class, 'search']);
    });
});

// Public routes for password setup
Route::get('/setup-password/{token}', [EmployeeController::class, 'showPasswordSetupForm'])
    ->name('employees.setup-password-form');
Route::post('/setup-password', [EmployeeController::class, 'setupPassword'])
    ->name('employees.setup-password');
