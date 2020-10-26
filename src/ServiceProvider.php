<?php

namespace Eval4VictoryCTO\LaravelImageUploader;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'image-uploader');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
        Route::group($this->routeConfigurationApi(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/image-uploader.php', 'image-uploader');

        // Register the service the package provides.
        $this->app->singleton('image-uploader', function ($app) {
            return new ImageUploader();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['image-uploader'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/image-uploader.php' => config_path('image-uploader.php'),
        ], 'config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/image-uploader'),
        ], 'views');
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('image-uploader.route-prefix'),
            'middleware' => config('image-uploader.middleware'),
        ];
    }
    protected function routeConfigurationApi()
    {
        return [
            'prefix' => 'api/' . config('image-uploader.route-prefix'),
            'middleware' => config('image-uploader.middleware-api'),
        ];
    }
}
