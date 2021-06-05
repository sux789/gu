<?php

namespace App\Console\Commands;

use App\Models\ChgnSnap;
use App\Models\Crontab;
use App\Models\Snap;
use App\Models\Symbol;
use App\Services\Chgn\ChgnDetailSyncService;
use App\Services\Chgn\ChgnSyncService;
use App\Services\Cron\CronSnapService;
use App\Services\Misc\CronControlService;
use App\Services\Misc\CronStateService;
use App\Services\Recommend\RecommendService;
use App\Services\Snap\SnapAddService;
use App\Services\Snap\SnapCronDailyFinishService;
use App\Services\Snap\SnapCronDailyInitService;
use App\Services\Snap\SnapSyncService;
use App\Services\Snap\TxApiService;
use App\Services\Symbol\ChgnSymbolService;
use App\Services\Symbol\SymbolService;
use App\Services\WebClient\SignChgnFetcher;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class Sd extends Command
{
    static $start_time;

    public function handle()
    {
        $rs=RecommendService::run();

        return 0;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:act {act}';

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
        system('clear');
        self::$start_time = microtime(true);
    }

    function act()
    {
        echo __FUNCTION__;
    }
}
