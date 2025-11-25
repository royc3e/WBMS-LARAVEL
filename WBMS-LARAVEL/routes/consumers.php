<?php

use App\Http\Controllers\ConsumerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Consumer Resource Routes
    Route::resource('consumers', ConsumerController::class);
    
    // Additional routes can be added here
    // Example: Route::get('/consumers/export', [ConsumerController::class, 'export'])->name('consumers.export');
});
