<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\DocumentController;
use Modules\Document\Http\Controllers\DocumentStructureController;
use Modules\Document\Models\Notification;

Route::middleware(['auth', 'verified'])
    ->prefix('document')
    ->name('document.')
    ->group(function () {

        Route::get('create', [DocumentController::class, 'create'])->name('create');
        Route::post('store', [DocumentController::class, 'store'])->name('store');
        Route::get('index', [DocumentController::class, 'index'])->name('index');
        Route::get('show/{id}', [DocumentController::class, 'show'])->name('show');
        Route::get('edit/{id}', [DocumentController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [DocumentController::class, 'update'])->name('update');

        Route::post('upload-file', [DocumentController::class, 'uploadFile'])->name('upload-file');

        Route::post('{id}/status', [DocumentController::class, 'updateStatus'])->name('updateStatus');

        Route::post('{id}/add-recipient', [DocumentController::class, 'addRecipient'])->name('addRecipient');

        Route::post('{id}/follow', [DocumentController::class, 'follow'])->name('follow');
        Route::post('{id}/unfollow', [DocumentController::class, 'unfollow'])->name('unfollow');

        Route::post('update-progress', [DocumentController::class, 'updateProgress'])->name('update-progress');

        Route::get('{document_id}/logs', [DocumentController::class, 'logs'])->name('logs');

        Route::post('{id}/approve', [DocumentController::class, 'approve'])->name('approve');

        Route::post('notifications/{id}/read', function ($id) {
            Notification::where('id', $id)
                ->where('to_user', Auth::user()->employee_id)
                ->update(['read_at' => now()]);

            return response()->json(['ok' => true]);
        })->name('notification.read');


        Route::prefix('structure')->name('structure.')->group(function () {
            Route::get('index', [DocumentStructureController::class, 'index'])->name('index');
            Route::get('create', [DocumentStructureController::class, 'create'])->name('create');
            Route::post('store', [DocumentStructureController::class, 'store'])->name('store');
            Route::get('edit/{id}', [DocumentStructureController::class, 'edit'])->name('edit');
            Route::put('update/{id}', [DocumentStructureController::class, 'update'])->name('update');
            Route::delete('delete/{id}', [DocumentStructureController::class, 'delete'])->name('delete');
            Route::get('children', [DocumentStructureController::class, 'children'])
                ->name('children');
        });
    });
