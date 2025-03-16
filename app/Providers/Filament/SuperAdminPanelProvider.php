<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Employee\Pages\Auth\EditProfile;
use Vormkracht10\FilamentMails\FilamentMailsPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Kenepa\TranslationManager\TranslationManagerPlugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentGeneralSettings\FilamentGeneralSettingsPlugin;

class SuperAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('super-admin')
            ->path('super-admin')
            ->sidebarCollapsibleOnDesktop()
            ->plugins([FilamentMailsPlugin::make(),
            FilamentGeneralSettingsPlugin::make()
                ->setIcon('heroicon-o-cog') // اختياري: لتغيير الأيقونة
                ->setNavigationGroup('Settings') // اختياري: لتجميعها تحت قسم "Settings"
                ->setTitle('General Settings') // اختياري: لتغيير العنوان
                ->setNavigationLabel('General Settings') // اختياري: لتغيير التسمية في القائمة

            ])
            // ->navigationItems([
            //     FilamentMailsPlugin::getNavigationItem()
            // ])
//            ->login()
            ->brandLogo(asset('logo.jfif'))
            ->brandLogoHeight('60px')
            ->profile(EditProfile::class,false)
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,            ])
            ->discoverResources(in: app_path('Filament/SuperAdmin/Resources'), for: 'App\\Filament\\SuperAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/SuperAdmin/Pages'), for: 'App\\Filament\\SuperAdmin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/SuperAdmin/Widgets'), for: 'App\\Filament\\SuperAdmin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                'superadmin:سوبر أدمن'
            ])
            ->plugins([
                // FilamentSpatieRolesPermissionsPlugin::make(),
               // TranslationManagerPlugin::make()
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}