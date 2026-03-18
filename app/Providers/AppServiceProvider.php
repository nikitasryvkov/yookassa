<?php

namespace App\Providers;

use App\Facades\Telegram;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserPaymentConditionsRepository;
use App\Repositories\UserRepository;
use App\Services\Telegram\Bot\Buttons;
use App\Services\Telegram\Bot\Factory;
use App\Services\Telegram\Bot\File;
use App\Services\Telegram\Bot\InlineQuery;
use App\Services\Telegram\Bot\Message;
use App\Services\Telegram\Webhook\Actions\Error;
use App\Services\Telegram\Webhook\TelegramWebhook;
use App\Services\TochkaBank\TbApiService;
use App\Services\User\UserRepositoryInterface;
use App\Services\UserAccount\UserAccountRepositoryInterface;
use App\Services\UserPaymentConditions\UserPaymentConditionsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserAccountRepositoryInterface::class, UserAccountRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserPaymentConditionsRepositoryInterface::class, UserPaymentConditionsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        $this->app->bind(Telegram::class, function() {
            return new Factory(
                new Message(),
                new File(),
                new InlineQuery(),
                new Buttons(),
            );
        });

        $this->app->bind(TelegramWebhook::class, function() use ($request) {
            return new TelegramWebhook(
                $request,
                new TbApiService(),
                new Error(),
            );
        });
    }
}
