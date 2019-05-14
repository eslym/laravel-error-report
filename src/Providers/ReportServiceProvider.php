<?php


namespace Eslym\ErrorReport\Providers;

use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(
            realpath(__DIR__.'/../../res/lang'),
            'err-reports'
        );
        $this->loadViewsFrom(
            realpath(__DIR__.'/../../res/views'),
            'err-reports'
        );
        $this->loadMigrationsFrom(
            realpath(__DIR__.'/../../migrations')
        );
        $this->publishes([
            realpath(__DIR__.'/../../config/errors.php') => config_path('errors.php')
        ], 'config');
    }
}