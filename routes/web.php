<?php

use App\Http\Controllers\BillingController;
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
require __DIR__ . '/consumers.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/meter-readings', 'dashboard')->name('meter-readings.index');
    // Consumer routes are now in consumers.php
    Route::view('/rates-charges', 'dashboard')->name('rates.index');

    Route::get('billings/generate', [BillingController::class, 'generate'])->name('billings.generate');
    Route::post('billings/generate/active', [BillingController::class, 'generateActive'])->name('billings.generate.active');
    Route::post('billings/generate/custom', [BillingController::class, 'generateSelected'])->name('billings.generate.selected');
    Route::resource('billings', BillingController::class);
    Route::get('billings/{billing}/payments/create', [BillingController::class, 'createPayment'])
        ->name('billings.payment.create');
    Route::post('billings/{billing}/payments', [BillingController::class, 'processPayment'])
        ->name('billings.payment.store');
    Route::get('billings/{billing}/print', [BillingController::class, 'print'])->name('billings.print');
    Route::get('billings/payments/{payment}/receipt', [BillingController::class, 'receipt'])->name('billings.receipt');
    Route::patch('billings/{billing}/cancel', [BillingController::class, 'cancel'])->name('billings.cancel');

    // Payment Management Routes
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');

    // Audit Logs Routes
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');

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
