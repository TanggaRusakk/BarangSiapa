<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes - Payment & Midtrans Webhooks
|--------------------------------------------------------------------------
|
| WHY: Webhook dari Midtrans server harus di API routes karena:
| - Tidak butuh session/cookie
| - Tidak butuh CSRF token (sudah di-exclude di bootstrap/app.php)
| - Tidak butuh autentikasi user (signature verification saja)
| - RESTful dan stateless
| - URL: https://yourdomain.com/api/midtrans/webhook
|
*/

// Midtrans webhook - TIDAK PAKAI MIDDLEWARE AUTH
// Midtrans akan POST ke endpoint ini setiap ada perubahan status payment
Route::post('/midtrans/webhook', [PaymentController::class, 'webhook'])
    ->name('api.midtrans.webhook');
