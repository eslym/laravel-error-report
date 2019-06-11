<?php

namespace Eslym\ErrorReport\Commands;

use Carbon\Carbon;
use Eslym\ErrorReport\Model\ErrorRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ErrorCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'errors:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean-up errors';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $remove = Carbon::now()->subDays(Config::get('errors.days-keep', env('ERRORS_DAYS_KEEP', 30)));
        ErrorRecord::where('updated_at', '<', $remove->toDateString())
            ->delete();
        return 0;
    }
}
