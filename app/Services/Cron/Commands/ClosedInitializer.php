<?php


namespace App\Services\Cron\Commands;


use App\Models\Snap;
use App\Services\Cron\CommandBase;
use App\Services\Snap\SnapSyncService;
use Illuminate\Support\Facades\Date;

/**
 * 同步收盘价
 */
class ClosedInitializer extends CommandBase
{
    const START_HOUR = '16:01';

    function handle()
    {
        self::initClosed();

        if (self::isFinished()) {
            $this->setStateFinished();
        } else {
            $this->setStateAborted();
        }
    }

    function startable()
    {
        return self::isTimeUp() && Snap::hasTodayTrade() && !self::isFinished();
    }

    static function initClosed()
    {
        $symbolSet = self::pluckUnRefreshed();
        SnapSyncService::headle($symbolSet);
    }


    static function isFinished(): bool
    {
        return !self::pluckUnRefreshed(1);
    }

    private static function pluckUnRefreshed($limit = 0)
    {
        $date = Snap::lastTradeDate();
        $hour = self::START_HOUR;
        $lastCreateTime = "$date $hour";

        $obj = Snap::where('updated_at', '<', $lastCreateTime);
        if ($limit) {
            $obj->limit($limit);
        }
        $rs = $obj->pluck('symbol')->toArray();

        return $rs;
    }


    private static function isTimeUp(): bool
    {
        $now = Date::now()->toDateTimeString();
        $date = Date::now()->toDateString();
        $starTime = "$date " . self::START_HOUR;
        return $now > $starTime;
    }


}
