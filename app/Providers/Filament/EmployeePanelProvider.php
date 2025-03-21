<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Register;
use App\Filament\Employee\Pages\Auth\EditProfile;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;

use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;



use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->databaseNotifications(true)
            ->path('employee')
            ->sidebarCollapsibleOnDesktop()
//            ->login()

            ->brandLogo(asset('logo.jfif'))
            ->brandLogoHeight('60px')
            ->profile(EditProfile::class)
            ->registration(Register::class)
            ->profile(EditProfile::class,false)
//            ->userMenuItems([
//                MenuItem::make()
//                    ->label('التوقيعات')
//                    ->url('/employee/correspondence-trackings')
//                    ->icon('heroicon-o-cog-6-tooth'),
//            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')
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
                'admin:موظف'

            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}