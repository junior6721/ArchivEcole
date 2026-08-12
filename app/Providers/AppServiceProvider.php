<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OtpService;
use App\Services\SmsService;
use App\Services\EmailService;
use App\Services\VerificationService;
use App\Services\QrCodeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind services
        $this->app->singleton(SmsService::class, function () {
            return new SmsService();
        });

        $this->app->singleton(EmailService::class, function () {
            return new EmailService();
        });

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService(
                $app->make(SmsService::class),
                $app->make(EmailService::class)
            );
        });

        $this->app->singleton(VerificationService::class, function () {
            return new VerificationService();
        });

        $this->app->singleton(QrCodeService::class, function () {
            return new QrCodeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load configuration
        $this->loadConfigsFrom(config_path());
    }
}
