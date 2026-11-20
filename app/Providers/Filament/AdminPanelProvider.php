<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\VerifyCsrfToken;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Admin dates display in US Eastern time with a short, readable format.
     */
    public const DISPLAY_TIMEZONE = 'America/New_York';

    public const DATE_TIME_FORMAT = 'M j, Y g:i A T';

    public function boot(): void
    {
        FilamentTimezone::set(self::DISPLAY_TIMEZONE);
        Table::configureUsing(fn (Table $table) => $table->defaultDateTimeDisplayFormat(self::DATE_TIME_FORMAT));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateTimeDisplayFormat(self::DATE_TIME_FORMAT));
    }

    public function panel(Panel $panel): Panel
    {
        return $panel->default()->id('admin')->path('admin')->login()
            ->authGuard('backpack')->authPasswordBroker('users')
            ->brandName('Karl Administration')
            ->maxContentWidth(Width::Full)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([Dashboard::class])
            ->navigationGroups([
                'Community',
                'Game Data',
                'User Management',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
