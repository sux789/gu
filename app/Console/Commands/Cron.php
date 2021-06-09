<?php

namespace App\Console\Commands;

use App\Services\Chgn\ChgnDetailSyncService;
use App\Services\Cron\CronRunService;
use App\Services\Cron\SnapDayInitializer;
use App\Services\Misc\CronControlService;
use Illuminate\Console\Command;

class Cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        CronRunService::handle();

        return 0;
    }

    public function chgn(){
        return ChgnDetailSyncService::handle();
    }
}
