<?php


namespace Eslym\ErrorReport\Providers;

use Eslym\ErrorReport\Commands\ErrorCleanup;
use Eslym\ErrorReport\Tools\Reporter;
use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ErrorCleanup::class,
        ]);
        $this->app->singleton(Reporter::class);
        $this->loadMigrationsFrom(
            realpath(__DIR__.'/../../migrations')
        );
        $this->publishes([
            realpath(__DIR__.'/../../config/errors.php') => config_path('errors.php')
        ], 'config');
    }
}