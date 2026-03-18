<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\CommissionReportController;
use App\Http\Controllers\CrmHomeController;
use App\Http\Controllers\PaymentPointController;
use App\Http\Controllers\Receipt\ReceiptController;
use App\Http\Controllers\Telegram\TelegramPaymentController;
use App\Http\Controllers\TochkaBank\TochkaBankAcquiringInternetPaymentsController;
use App\Http\Controllers\TochkaBank\TochkaBankBalanceController;
use App\Http\Controllers\TochkaBank\TochkaBankIncomingSbpPaymentController;
use App\Http\Controllers\UrlPaymentController;
use App\Http\Controllers\User\MyProfileController;
use App\Http\Controllers\User\UserPaymentConditionsController;
use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\User\UsersPaymentMethodsController;
use App\Http\Controllers\YooKassa\YooKassaPaymentController;
use Illuminate\Support\Facades\Route;

Route::resource('/', CrmHomeController::class)
    ->only(['index'])->middleware('auth');

Route::get('login', fn() => to_route('auth.create'))
    ->name('login');
Route::resource('auth', AuthController::class)
    ->only(['create', 'store']);

Route::resource('receipt', ReceiptController::class)->middleware(['auth', 'check-admin'])
    ->only(['store']);

Route::post('receipt/get-status', [ReceiptController::class, 'getStatus'])
    ->middleware(['auth', 'check-admin'])
    ->name('receipt.get.status');


Route::delete('logout', fn() => to_route('auth.destroy'))
    ->name('logout');

Route::delete('auth', [AuthController::class, 'destroy'])
    ->name('auth.destroy');

$settings = [
    'prefix' => 'tb',
];

Route::group($settings, function (): void {
    Route::get('incoming-sbp-payments', [TochkaBankIncomingSbpPaymentController::class, 'index'])
        ->middleware(['auth', 'check-admin'])
        ->name('tb.incoming.payments');
    Route::get('acquiring-internet-payments', [TochkaBankAcquiringInternetPaymentsController::class, 'index'])
        ->middleware(['auth', 'check-admin'])
        ->name('tb.acquiring.internet.payments');
    Route::post('balance', [TochkaBankBalanceController::class, 'getBalance'])
        ->middleware(['auth'])
        ->name('tb.account.balance');
});

Route::resource('users', UsersController::class)->except(['show', 'edit', 'destroy'])->middleware(['auth', 'check-admin']);

$settings = [
    'prefix' => 'users',
    'middleware' => ['auth', 'check-admin']
];

Route::group($settings, function (): void {
    Route::post('/get-short-info', [UsersController::class, 'getShortIno'])->name('users.get.short.info');
    Route::post('/delete', [UsersController::class, 'delete'])->name('users.delete');
    Route::post('/payment-conditions/store', [UserPaymentConditionsController::class, 'store'])
        ->name('user-payment-condition.store');
    Route::post('/payment-conditions/update', [UserPaymentConditionsController::class, 'update'])
        ->name('user-payment-condition.update');
    Route::post('/freelancer', [UsersController::class, 'setFreelancerCheckbox'])
        ->name('user.freelancer.update');
});

$settings = [
    'prefix' => 'users-payment-methods',
    'middleware' => ['auth', 'check-admin']
];

Route::group($settings, function (): void {
    Route::post('/manage', [UsersPaymentMethodsController::class, 'createOrDelete'])
        ->name('users-payment-methods.manage');
});

Route::resource('payment-points', PaymentPointController::class)->except(['show', 'edit', 'destroy'])
->middleware(['auth', 'check-admin']);

Route::post('/payment-points/delete', [PaymentPointController::class, 'delete'])
    ->name('payment-points.delete')->middleware(['auth', 'check-admin']);

$settings = [
    'prefix' => 'telegram/payments',
    'middleware' => ['auth']
];

Route::group($settings, function (): void {
    Route::get('/', [TelegramPaymentController::class, 'index'])->name('telegram-payments.list');
});


Route::resource('bots', BotController::class)->except(['show', 'edit'])
    ->middleware(['auth', 'check-admin']);

Route::post('/bots/set-wh', [BotController::class, 'setTelegramWebhook'])
    ->name('bots.set-telegram-webhook')->middleware(['auth', 'check-admin']);

$settings = [
    'prefix' => 'links',
    'middleware' => ['auth']
];

Route::group($settings, function (): void {
    Route::get('/', [UrlPaymentController::class, 'index'])->name('url-payments.list');
    Route::post('/create', [UrlPaymentController::class, 'create'])->name('url-payments.create');
});

Route::group(['prefix' => 'yookassa', 'middleware' => ['auth', 'check-admin']], function (): void {
    Route::post('/status', [YooKassaPaymentController::class, 'status'])->name('yookassa.status');
    Route::post('/refund', [YooKassaPaymentController::class, 'refund'])->name('yookassa.refund');
});

$settings = [
    'prefix' => 'reports',
    'middleware' => ['auth']
];

Route::group($settings, function (): void {
    Route::get('/commission', [CommissionReportController::class, 'index'])->name('reports-commission.index');
    Route::post('/commission/pay', [CommissionReportController::class, 'sendPaymentRequest'])->name('reports-commission.pay');
});


Route::group(['middleware' => ['auth']], function (): void {
    Route::get('/profile', [MyProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/store', [MyProfileController::class, 'storeUserAccount'])->name('profile-account.store');
    Route::post('/profile/update', [MyProfileController::class, 'updateUserAccount'])->name('profile-account.update');
});
