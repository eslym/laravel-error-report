<?php


namespace Eslym\ErrorReport\Facades;


use Eslym\ErrorReport\Tools\Reporter;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Facade;
use Throwable;

/**
 * Class ErrReport
 * @package Eslym\ErrorReport\Facades
 * @method static void routes(Router $router = null);
 * @method static string report(Throwable $exception);
 */
class ErrReport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Reporter::class;
    }
}