<?php


namespace Eslym\ErrorReport\Tools;


use Carbon\Carbon;
use Eslym\ErrorReport\Model\ErrorRecord;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
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

    public function __construct(Router $router)
    {
        $this->router = $router;
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

        $max_sample = Config::get('errors.max-sample', env('ERRORS_MAX_SAMPLE', 10));
        if ( $record->reports()->count() >= $max_sample) {
            return $record->id;
        }

        $sample_interval = Config::get('errors.sample-delay', env('ERRORS_SAMPLE_DELAY', 10));
        $last_report = $record->reports()->orderByDesc('created_at')->first();
        if ($last_report && $last_report->created_at->gt(Carbon::now()->subMinutes($sample_interval))) {
            return $record->id;
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
        return $record->id;
    }

    public function runningInConsole()
    {
        if (isset($_ENV['APP_RUNNING_IN_CONSOLE'])) {
            return $_ENV['APP_RUNNING_IN_CONSOLE'] === 'true';
        }
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }
}