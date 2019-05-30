<?php


namespace Eslym\ErrorReport\Facades;


use Carbon\Carbon;
use Eslym\ErrorReport\Model\ErrorRecord;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

class ErrReport
{
    public static function routes(Router $router = null){
        $router = $router ?? app('router');
        $router->namespace('\\Eslym\\ErrorReport\\Controllers')
            ->prefix('errors')
            ->name('err-reports::')
            ->group(function(Router $router){
                $router->get('/', 'ErrorController@list')
                    ->name('list');
            });
    }

    public static function report(Throwable $exception){
        $trace = json_encode(collect($exception->getTrace())->map(function ($trace){
            return Arr::except($trace, ['args']);
        }));

        $record = ErrorRecord::firstOrCreate([
            'class' => get_class($exception),
            'hash' => hash('sha512', $trace),
            'site' => url('/'),
            'is_console' => app()->runningInConsole(),
        ]);

        if (!app()->runningInConsole()) {
            session()->put('report_id', $record->id);
        }

        $max_sample = config('errors.max-sample', env('ERRORS_MAX_SAMPLE', 10));
        if ( $record->reports()->count() >= $max_sample) {
            return;
        }

        $sample_interval = config('errors.sample-delay', env('ERRORS_SAMPLE_DELAY', 10));
        $last_report = $record->reports()->orderByDesc('created_at')->first();
        if ($last_report && $last_report->created_at->gt(Carbon::now()->subMinutes($sample_interval))) {
            return;
        }

        $handler = new PrettyPageHandler();
        $handler->setPageTitle("Error report $record->id");
        $handler->handleUnconditionally(true);

        $whoops = new Whoops();
        $whoops->pushHandler($handler);
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);
        $content = $whoops->handleException($exception);

        $record->reports()->make([
            'content' => $content,
        ])->save();
    }
}