<?php

use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\ServiceStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Consumer Resource Routes
    Route::resource('consumers', ConsumerController::class);

    // Service Status Routes
    Route::post('/consumers/{consumer}/disconnect', [ServiceStatusController::class, 'disconnect'])->name('consumers.disconnect');
    Route::post('/consumers/{consumer}/reconnect', [ServiceStatusController::class, 'reconnect'])->name('consumers.reconnect');
});
