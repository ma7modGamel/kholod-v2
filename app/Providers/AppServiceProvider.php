<?php

namespace App\Providers;

use App\Filament\MainPanal\Pages\GlobalLogout;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\MailManager;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;

class AppServiceProvider extends ServiceProvider
{
    /*
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, GlobalLogout::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        Gate::define('use-translation-manager', function (?User $user) {
            return true;
            // return $user !== null && ($user->hasRole('admin') || $user->hasRole('Super Admin'));
        });


                    
        // تعيين تكوين SMTP أساسي من .env
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'mail.kholood.com'),
            'port' => env('MAIL_PORT', 465),
                    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
                    'username' => env('MAIL_USERNAME'),
                    'password' => env('MAIL_PASSWORD'),
                    'timeout' => 60,
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ]);
        Config::set('mail.default', 'smtp');
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', 'noreply@kholood.com'));
        Config::set('mail.from.name', env('MAIL_FROM_NAME', 'Kholood'));
        app('mail.manager')->forgetMailers();

        // تحديث ديناميكي من General Settings (البلاجن)
        $settings = GeneralSetting::first();
        if ($settings && $settings->email_settings) {
            $emailSettings = $settings->email_settings;

            if ($emailSettings && ($emailSettings['default_email_provider'] ?? 'smtp') === 'smtp') {
                Config::set('mail.mailers.smtp', [
                    'transport' => 'smtp',
                    'host' => $emailSettings['smtp_host'] ?? env('MAIL_HOST', 'mail.kholood.com'),
                    'port' => $emailSettings['smtp_port'] ?? env('MAIL_PORT', 465),
                    'username' => $emailSettings['smtp_username'] ?? env('MAIL_USERNAME', 'noreply@kholood.com'),
                    'password' => $emailSettings['smtp_password'] ?? env('MAIL_PASSWORD', 'Q+y7ZECKT7%F'),
                    'encryption' => $emailSettings['smtp_encryption'] ?? env('MAIL_ENCRYPTION', null),
                    'timeout' => $emailSettings['smtp_timeout'] ?? 60,
                ]);
                Config::set('mail.from.address', $settings->from_address ?? env('MAIL_FROM_ADDRESS', 'noreply@kholood.com'));
                Config::set('mail.from.name', $settings->from_name ?? env('MAIL_FROM_NAME', 'Kholood'));
                Config::set('mail.default', 'smtp');
                app('mail.manager')->forgetMailers();
            }
        }
    }
}