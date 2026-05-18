<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class KepalaDesaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('kepala-desa')
            ->path('kepala-desa')
            ->login()
            ->colors(['primary' => Color::Emerald])
            ->brandName('Kepala Desa - Pengelompokan Warga')
            ->discoverResources(in: app_path('Filament/KepalaDesa/Resources'), for: 'App\\Filament\\KepalaDesa\\Resources')
            ->discoverPages(in: app_path('Filament/KepalaDesa/Pages'), for: 'App\\Filament\\KepalaDesa\\Pages')
            ->pages([Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/KepalaDesa/Widgets'), for: 'App\\Filament\\KepalaDesa\\Widgets')
            ->widgets([AccountWidget::class])
            ->sidebarCollapsibleOnDesktop()
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
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
