<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestContactsWidget;
use App\Filament\Widgets\LatestPostsWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Hệ Thống Quản Trị - Minh Thành Group')
            ->brandLogo(fn () => \App\Models\Setting::get('theme-ripple-logo', \App\Models\Setting::get('logo', '/storage/uploads/gst-logo-01.png')))
            ->brandLogoHeight('45px')
            ->favicon(fn () => \App\Models\Setting::get('favicon', '/storage/uploads/gst-logo-01.png'))
            ->darkMode(true)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                'Quản lý nội dung',
                'Điều hướng & Khối tĩnh',
                'Tương tác khách hàng',
                'Giao diện',
                'Hệ thống',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverviewWidget::class,
                LatestPostsWidget::class,
                LatestContactsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
