<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Include consumer routes
require __DIR__.'/consumers.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/meter-readings', 'dashboard')->name('meter-readings.index');
    // Consumer routes are now in consumers.php
    Route::view('/rates-charges', 'dashboard')->name('rates.index');
    Route::view('/billings', 'dashboard')->name('billings.index');
    // Payments route is removed as requested
    Route::view('/user-management', 'dashboard')->name('users.index');
    Route::view('/reports', 'dashboard')->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
