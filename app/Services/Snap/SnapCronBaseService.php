<?php


namespace App\Services\Snap;


use App\Models\Crontab;
use App\Models\Snap;

use Illuminate\Support\Facades\DB;

abstract class SnapCronBaseService
{

    abstract static function getSymbolSet();

    static function handle()
    {
        $startTime = microtime(true);
        $symbolSet = static::getSymbolSet();
        $rs = SnapSyncService::headle($symbolSet);
        $endTime = microtime(true);
        $spentTime = $endTime - $startTime;

        self::log($spentTime, $rs);
    }

    static function log($spentTime, $result)
    {
        $cmd = static::class;
        if (is_array($result)) {
            // $result=json_encode($result, JSON_UNESCAPED_UNICODE);
            $symbolSet = array_filter($result);
            $result = join(',', array_keys($result));
        }
        $data = ['cmd' => $cmd, 'time_spent' => $spentTime, 'result' => $result];
        Crontab::updateOrCreate(['cmd' => $cmd], $data);
    }

}
