<?php


namespace Eslym\ErrorReport;

use Eslym\ErrorReport\Model\ErrorReport;
use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Whoops\Run as Whoops;

class ErrorHandler extends Handler
{
    protected $report_id = null;

    public function report(Exception $exception)
    {
        if(!Schema::hasTable((new ErrorReport())->getTable())){
            parent::report($exception);
            return;
        }

        if ($this->shouldntReport($exception)) {
            return;
        }
        $content = class_exists(Whoops::class) ?
            $this->renderExceptionWithWhoops($exception) :
            $this->renderExceptionWithSymfony($exception, true);
        $report = ErrorReport::create([
            'class' => get_class($exception),
            'content' => $content,
            'is_console' => app()->runningInConsole(),
        ]);
        $this->report_id = $report->id;
    }

    protected function renderHttpException(HttpException $e)
    {
        $this->registerErrorViewPaths();

        if (view()->exists($view = "errors::{$e->getStatusCode()}")) {
            return response()->view($view, [
                'errors' => new ViewErrorBag,
                'report_id' => $this->report_id,
                'exception' => $e,
            ], $e->getStatusCode(), $e->getHeaders());
        }

        return $this->convertExceptionToResponse($e);
    }
}
