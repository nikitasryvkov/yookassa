<?php

use App\Console\Processors\CommissionPaymentStatusProcessor;
use App\Console\Processors\UrlPaymentsProcessor;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    app(UrlPaymentsProcessor::class)->run();
})->everyThreeMinutes()->name('url_payment_processing_task')
->withoutOverlapping(10);

Schedule::call(function () {
    app(CommissionPaymentStatusProcessor::class)->run();
})->everyFiveMinutes()->name('payment_status_processing_task')
->withoutOverlapping(10);
