<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth', 'verified'])
    ->prefix('orders')
    ->name('orders.')
    ->group(function () {
        Route::get('create', [OrderController::class, 'create'])->name('create');
        Route::post('store', [OrderController::class, 'store'])->name('store');
        Route::get('active', [OrderController::class, 'activeOrders'])->name('active');
        Route::get('expiring', [OrderController::class, 'expiringOrders'])->name('expiring');
        Route::get('expired', [OrderController::class, 'expiredOrders'])->name('expired');
        Route::get('/{id}/orderService/{orderServiceId}', [OrderController::class, 'show'])->name('show');
        Route::get('/{id}/orderService/{orderServiceId}/renew', [OrderController::class, 'renew'])->name('renew');
        Route::post('/{id}/orderService/{orderServiceId}/renew', [OrderController::class, 'renewUpdate'])->name('renew.update');
    });
