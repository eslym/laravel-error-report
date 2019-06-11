<?php


namespace Eslym\ErrorReport\Tools;


use Carbon\Carbon;
use Eslym\ErrorReport\Model\ErrorRecord;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

class Reporter
{
    /**
     * @var Router
     */
    protected $router;

    protected $app;

    public function routes(Router $router = null){
        $router = $router ?? $this->router;
        $router->namespace('\\Eslym\\ErrorReport\\Controllers')
            ->prefix('errors')
            ->name('err-reports::')
            ->group(function(Router $router){
                $router->get('/', 'ErrorController@list')
                    ->name('list');
            });
    }

    public function report(Throwable $exception){
        $trace = json_encode(collect($exception->getTrace())->map(function ($trace){
            return Arr::except($trace, ['args']);
        }));

        $record = ErrorRecord::firstOrCreate([
            'class' => get_class($exception),
            'hash' => hash('sha512', $trace),
            'site' => URL::to('/'),
            'is_console' => $this->runningInConsole(),
        ]);

        $record->increment('counter');

        if (!$record->is_console) {
            Session::put('report_id', $record->id);
        }

        $max_sample = Config::get('errors.max-sample', env('ERRORS_MAX_SAMPLE', 10));
        if ( $record->reports()->count() >= $max_sample) {
            return;
        }

        $sample_interval = Config::get('errors.sample-delay', env('ERRORS_SAMPLE_DELAY', 10));
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

    public function runningInConsole()
    {
        if (isset($_ENV['APP_RUNNING_IN_CONSOLE'])) {
            return $_ENV['APP_RUNNING_IN_CONSOLE'] === 'true';
        }
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }
}