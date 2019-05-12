<?php


namespace Eslym\ErrorReport\Facades;


use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class ErrReport
{
    public static function routes(){
        Route::namespace('\\Eslym\\ErrorReport\\Controllers')
            ->prefix('errors')
            ->name('err-reports::')
            ->group(function(Router $router){
                $router->get('/', 'ErrorController@index')
                    ->name('index');
                $router->get('/list', 'ErrorController@list')
                    ->name('list');
                $router->get('/{report}', 'ErrorController@view')
                    ->where('/report', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
                    ->middleware(SetCacheHeaders::class)
                    ->name('view');
                $router->get('/{report}/delete', 'ErrorController@delete')
                    ->where('/report', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
                    ->name('delete');
            });
    }
}