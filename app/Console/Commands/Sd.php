<?php

namespace App\Console\Commands;

use App\Models\Crontab;
use App\Models\Snap;
use App\Models\Symbol;
use App\Services\Cron\CronSnapService;
use App\Services\Misc\CronControlService;
use App\Services\Misc\CronStateService;
use App\Services\Snap\SnapAddService;
use App\Services\Snap\SnapCronDailyFinishService;
use App\Services\Snap\SnapCronDailyInitService;
use App\Services\Snap\SnapSyncService;
use App\Services\Snap\TxApiService;
use App\Services\Symbol\ChgnSymbolService;
use App\Services\Symbol\SymbolService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class Sd extends Command
{
    static $start_time;

    public function handle()
    {

       /* $rs = TxApiService::get(['sh600030', 'sh600050']);

        foreach ($rs as $item) {
            Snap::updateOrCreate(['symbol' => $item['symbol']], $item);
        }*/
        /*$s=new Symbol();
        echo "getTable:",  $s->getTable(),"\n"; return 0;

        $rs=SymbolService::listForFetch();*/
        // $rs=SnapSyncService::runNew();
        // $rs=SnapAddService::handle();
        /*$rs=SnapCronDailyFinishService::handle();
        $rs=SnapCronDailyFinishService::getSymbolSet();
        */
        //$rs=CronStateService::exitsToday('xx');
        // $rs=SnapCronDailyInitService::handle();
        // print_r($rs);
        // CronControlService::handle();
       $rs= CronSnapService::hasInitToday();
        var_dump($rs);
        echo __CLASS__;
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

    public function x__destruct()
    {
        static $printed = false;
        if ($printed) {
            return false;
        }

        $printed = true;
        $spent_time = microtime(true) - self::$start_time;
        // echo "\n spent $spent_time\n";
        if ($argv = $this->getArguments()) {
            print_r($argv);
        }
    }

    function act()
    {
        echo __FUNCTION__;
    }
}
