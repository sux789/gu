<?php

namespace App\Console\Commands;

use App\Services\Snap\TxApiService;
use Illuminate\Console\Command;

class Sd extends Command
{
    static $start_time;

    public function handle()
    {


        $rs=TxApiService::get(['sh600030','sh600050']);
        print_r($rs) ;
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
        self::$start_time = microtime(true);
    }

    public function __destruct()
    {
        static $printed = false;
        if ($printed) {
            return false;
        }

        $printed = true;
        $spent_time = microtime(true) - self::$start_time;
        echo "\n spent $spent_time\n";
        if($argv=$this->getArguments()){
            print_r($argv);
        }
    }
    function  act(){
        echo __FUNCTION__;
    }
}
