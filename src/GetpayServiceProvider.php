<?php

namespace NeputerTech\GetpayGateway;

use Illuminate\Support\ServiceProvider;
use NeputerTech\GetpayGateway\Components\GetpayScripts;
use NeputerTech\GetpayGateway\Services\GetpayService;

class GetpayServiceProvider extends ServiceProvider
{
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

    public function register(): void
    {
        $this->app->singleton('getpay', function ($app) {
            return new GetpayService;
        });

        $this->mergeConfigFrom(__DIR__.'/../config/getpay.php', 'getpay');
    }
}
