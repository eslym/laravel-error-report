<?php


namespace Eslym\ErrorReport\Facades;


use Eslym\ErrorReport\Tools\Reporter as ErrorReporter;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Facade;
use Throwable;

/**
 * Class Reporter
 * @package Eslym\ErrorReport\Facades
 * @method static void routes(Router $router = null)
 * @method static string report(Throwable $exception)
 */
class Reporter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ErrorReporter::class;
    }
}