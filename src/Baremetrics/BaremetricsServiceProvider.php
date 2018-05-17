<?php

namespace Oseintow\Baremetrics;

use Illuminate\Support\ServiceProvider;

class BaremetricsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->alias('Baremetrics', 'Oseintow\Baremetrics\Facades\Baremetrics');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('baremetrics',function($app)
        {
            return new Baremetrics();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
