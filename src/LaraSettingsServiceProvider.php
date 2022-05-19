<?php

namespace Camc\LaraSettings;

use Illuminate\Support\ServiceProvider;

class LaraSettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('lara_settings.php'),
            ], 'lara-settings-config');
        }
    }

    public function register()
    {
        $this->app->bind('lara-settings', function ($app) {
            return new SettingRepository(config('lara_settings.cache_key', 'lara-settings'));
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'lara_settings');
    }
}
