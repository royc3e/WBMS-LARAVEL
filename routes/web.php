<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Include consumer routes
require __DIR__ . '/consumers.php';

Route::middleware(['auth', 'verified'])->group(function () {
    // Meter Readings Routes
    Route::resource('meter-readings', \App\Http\Controllers\MeterReadingController::class);
    Route::get('meter-readings/consumer/{consumer}', [\App\Http\Controllers\MeterReadingController::class, 'getConsumerDetails'])
        ->name('meter-readings.consumer-details');

    // Consumer routes are now in consumers.php
    Route::view('/rates-charges', 'dashboard')->name('rates.index');

    Route::get('billings/generate', [BillingController::class, 'generate'])->name('billings.generate');

    // New dual generation system routes
    Route::post('billings/generate-all', [BillingController::class, 'generateAll'])->name('billings.generate-all');
    Route::post('billings/generate-individual', [BillingController::class, 'generateIndividual'])->name('billings.generate-individual');

    // Legacy routes (deprecated but kept for backward compatibility)
    Route::post('billings/generate/active', [BillingController::class, 'generateActive'])->name('billings.generate.active');
    Route::post('billings/generate/custom', [BillingController::class, 'generateSelected'])->name('billings.generate.selected');

    Route::resource('billings', BillingController::class);
    Route::get('billings/{billing}/payments/create', [BillingController::class, 'createPayment'])
        ->name('billings.payments.create');
    Route::post('billings/{billing}/payments', [BillingController::class, 'processPayment'])
        ->name('billings.payment.store');
    Route::get('billings/{billing}/print', [BillingController::class, 'print'])->name('billings.print');
    Route::get('billings/payments/{payment}/receipt', [BillingController::class, 'receipt'])->name('billings.receipt');
    Route::patch('billings/{billing}/cancel', [BillingController::class, 'cancel'])->name('billings.cancel');

    // Payment Management Routes
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');

    // Audit Logs Routes
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [\App\Http\Controllers\AuditLogController::class, 'export'])->name('audit-logs.export');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'index'])->name('index');

        // User Management (Admin only)
        Route::get('/users', [\App\Http\Controllers\SettingsController::class, 'users'])->name('users');
        Route::post('/users', [\App\Http\Controllers\SettingsController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [\App\Http\Controllers\SettingsController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\SettingsController::class, 'destroyUser'])->name('users.destroy');

        // Profile Settings (All users)
        Route::get('/profile', [\App\Http\Controllers\SettingsController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('profile.update');

        // Water Rate Settings (Admin only)
        Route::get('/rates', [\App\Http\Controllers\SettingsController::class, 'rates'])->name('rates');
        Route::post('/rates', [\App\Http\Controllers\SettingsController::class, 'updateRates'])->name('rates.update');
    });

    // User Management Routes
    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
    Route::view('/reports', 'dashboard')->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
