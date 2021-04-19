<?php

namespace Lumaintenance\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use Lumaintenance\Console\LocalStorageCommand;
use Lumaintenance\Console\MaintenanceStatusCommand;
use Lumaintenance\Middleware\MaintenanceMiddleware;
use Lumaintenance\Service\LumainConfig;
use Lumaintenance\Service\LumainConfigInterface;
use Lumaintenance\Service\LumainEnvService;
use Lumaintenance\Service\LumainEnvServiceInterface;
use Lumaintenance\Service\LumainLocalService;
use Lumaintenance\Service\LumainLocalServiceInterface;

/**
 * Class LumaintenanceServiceProvider
 * @package Lumaintenance\Providers
 */
class LumaintenanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'lumaintenance');

        if ($this->app->runningInConsole()) {
            $this->commands([
                LocalStorageCommand::class,
                MaintenanceStatusCommand::class,
            ]);
        }
    }

    public function register()
    {
        parent::register();
        $this->mergeConfigFrom(__DIR__ . '/../../config/lumaintenance.php', 'lumaintenance');

        $this->app->bind(LumainEnvServiceInterface::class, LumainEnvService::class);
        $this->app->bind(LumainLocalServiceInterface::class, LumainLocalService::class);
        $this->app->bind(LumainConfigInterface::class, LumainConfig::class);

        /**
         * required config/lumaintenance.php
         */
        $this->app->singleton(LumainConfigInterface::class, function () {
            return new LumainConfig(config('lumaintenance'));
        });

        app()->middleware([
            MaintenanceMiddleware::class
        ]);

        if ($this->app instanceof Application) {
            $this->app->configure('lumaintenance');
        }
    }
}
