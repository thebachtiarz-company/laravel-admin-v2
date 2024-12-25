<?php

namespace TheBachtiarz\Admin\Providers;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use TheBachtiarz\Admin\DTOs\Filament\Configs\DiscoverClassDTO;
use TheBachtiarz\Config\Helpers\ConfigHelper;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;

class FilamentAdminServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel->default()
            ->sidebarCollapsibleOnDesktop()
            ->brandName('System Admin')->id('system-admin')->path($this->getAdminUriPath())
            ->login(action: \TheBachtiarz\Admin\Filament\Admin\Auth\Pages\Login::class)->loginRouteSlug('authentication')
            ->colors(array_merge(Color::all(), [
                'primary' => Color::Indigo,
                'secondary' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger' => Color::Rose,
            ]))->defaultThemeMode(ThemeMode::System)
            ->maxContentWidth(MaxWidth::Full)
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
            ])->authMiddleware([
                Authenticate::class,
            ])->spa();

        $this->defineCompositions(panel: $panel);

        return $panel;
    }

    protected function defineCompositions(Panel &$panel): void
    {
        $discovers = [
            'tbadmin.filament_discover_clusters' => 'discoverClusters',
            'tbadmin.filament_discover_pages' => 'discoverPages',
            'tbadmin.filament_discover_resources' => 'discoverResources',
            'tbadmin.filament_discover_widgets' => 'discoverWidgets',
        ];

        $customs = [
            'tbadmin.filament_custom_pages' => 'pages',
            'tbadmin.filament_custom_resources' => 'resources',
            'tbadmin.filament_custom_widgets' => 'widgets',
        ];

        foreach ($discovers as $config => $method) {
            $this->discoverCompositions(panel: $panel, config: $config, method: $method);
        }

        foreach ($customs as $config => $method) {
            $this->customCompositions(panel: $panel, config: $config, method: $method);
        }
    }

    protected function discoverCompositions(Panel &$panel, string $config, string $method): void
    {
        foreach (config(key: $config, default: []) as $key => $cluster) {
            $cluster = new DiscoverClassDTO(...$cluster);
            $panel->{$method}(in: $cluster->path, for: $cluster->namespace);
        }
    }

    protected function customCompositions(Panel &$panel, string $config, string $method): void
    {
        $panel->{$method}(config(key: $config, default: []));
    }

    private function isConfigTableExist(): bool
    {
        return Schema::hasTable(ConfigInterface::TABLE_NAME);
    }

    private function getAdminUriPath(): string
    {
        return $this->isConfigTableExist()
            ? (ConfigHelper::config('tbadmin.filament_admin_uri') ?? config(key: 'tbadmin.filament_admin_uri', default: 'system-admin'))
            : config(key: 'tbadmin.filament_admin_uri', default: 'system-admin');
    }
}
