<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\FacultyTypeStatsWidget;
use App\Filament\Widgets\FacultyWorkloadDistribution;
use App\Filament\Widgets\LoadSubmissionProgressWidget;
use App\Filament\Widgets\OverdueUnsubmittedLoadsWidget;
use App\Filament\Widgets\SubmissionRateWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class RegistrarPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('registrar')
            ->path('registrar')
            ->viteTheme('resources/css/admin/app.css')
            ->login()
            ->passwordReset()
            ->colors([
                'purple' => '#7c3aed',
                'yellow' => '#eab308',
                'red' => '#ef4444',
                'blue' => '#3b82f6',
            ])
            ->font('Poppins')
            ->maxContentWidth(Width::Full)
            ->brandName('SPUP Grading System')
            ->brandLogo(asset('sys-logo.png'))
            ->brandLogoHeight('3rem')
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('System Settings'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->breadcrumbs()
            ->darkmode(false)
            ->navigationGroups([
                'Grading Sheet Management',
                'User Management',
                'Academic Management',
                'System Settings',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(false)
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                SubmissionRateWidget::class,
                FacultyWorkloadDistribution::class,
                LoadSubmissionProgressWidget::class,
                FacultyTypeStatsWidget::class,
                OverdueUnsubmittedLoadsWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
