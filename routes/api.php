<?php

use App\Http\Controllers\Receipt\ReceiptWebhookController;
use App\Http\Controllers\Telegram\TelegramWebhookController;
use App\Http\Controllers\TochkaBank\TochkaBankWebHookController;
use App\Http\Controllers\YooKassa\YooKassaWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('tb-wh', [ TochkaBankWebHookController::class, 'getPayment'])->name('tochka.bank.webhook');

Route::post('receipt/{id}', [ ReceiptWebhookController::class, 'index'])->name('receipt.webhook');

Route::post('t-wh', [ TelegramWebhookController::class, 'index'])->name('telegram.webhook');

Route::post('yk-wh', YooKassaWebhookController::class)
    ->middleware(['yookassa-ip'])
    ->name('yookassa.webhook');
