<?php


namespace App\Services\Cron\Commands;


use App\Models\Snap;
use App\Services\Cron\CommandBase;
use App\Services\Snap\SnapSyncService;
use App\Services\Snap\TradeDayService;

/**
 * 同步热点股票
 */
class SnapSyncHotCommand extends CommandBase
{


    function handle()
    {
        self::refresh();
        $this->setStateFinished();
    }

    function startable()
    {
        return TradeDayService::nowIsTrading() && !self::isFinished();
    }

    static function refresh()
    {
        $symbolSet = self::pluckUnRefreshed();
        SnapSyncService::headle($symbolSet);
    }


    static function isFinished(): bool
    {
        return !self::pluckUnRefreshed(1);
    }

    static function pluckUnRefreshed($limit = 0)
    {
        $date = TradeDayService::lastDate();
        $lastUnixTime = time() - 60;
        $lastCreateTime = date('Y-m-d H:i:s', $lastUnixTime);


        $obj = Snap::where('updated_at', '<', $lastCreateTime);
        if ($limit) {
            $obj->limit($limit);
        }
        $rs = $obj->pluck('symbol')->toArray();

        return $rs;
    }

}
