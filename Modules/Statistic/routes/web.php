<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistic\Http\Controllers\StatisticController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [StatisticController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-business', [StatisticController::class, 'dashboard_business'])->name('dashboard.business');
});
