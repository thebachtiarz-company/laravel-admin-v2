<?php

namespace TheBachtiarz\Admin\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->bind(abstract: OauthAuthUser::class, concrete: AuthUser::class);

        (new ConfigProvider())();

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            \TheBachtiarz\Admin\Console\Commands\AdminRegisterGeneratorCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $configName  = 'tbadmin';
        $publishName = 'thebachtiarz-admin';

        $this->publishes([__DIR__ . "/../../configs/$configName.php" => config_path("$configName.php")], "$publishName-config");
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations')], "$publishName-migrations");
    }
}
