<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by bootstrap/app.php and prefixed with /api.
| The 'web' middleware is applied to auth-required routes so that
| session-based authentication (Breeze) works for Blade-served pages.
*/

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');
});

// M-Pesa callback — no auth (called by Safaricom servers)
Route::post('/mpesa/callback', [PaymentController::class, 'callback'])->name('mpesa.callback');
