<?php

namespace NeputerTech\GetpayGateway;

use Illuminate\Support\ServiceProvider;
use NeputerTech\GetpayGateway\Components\GetpayScripts;
use NeputerTech\GetpayGateway\Services\GetpayService;

/**
 * Getpay Gateway Service Provider
 * 
 * Laravel service provider that bootstraps the Getpay payment gateway package.
 * Handles registration of services, publishing of config files and views,
 * loading of routes, and registration of Blade components.
 * 
 * @package NeputerTech\GetpayGateway
 * @author Nitish Raj Uprety <nitishuprety@neputer.com>
 */
class GetpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Getpay services
     * 
     * Publishes configuration files and views, loads view components,
     * and registers routes for the Getpay payment gateway.
     * 
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/getpay.php' => config_path('getpay.php'),
        ], ['getpay-config']);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'getpay-views');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/getpay'),
        ], ['getpay-views']);

        $this->loadViewComponentsAs('getpay', [
            GetpayScripts::class,
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register the Getpay services
     * 
     * Registers the GetpayService as a singleton in the Laravel service container
     * and merges the package configuration with the application configuration.
     * 
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('getpay', function ($app) {
            return new GetpayService;
        });

        $this->mergeConfigFrom(__DIR__.'/../config/getpay.php', 'getpay');
    }
}
