<?php


namespace App\Services\Cron;


use App\Models\Snap;
use App\Services\Snap\SnapSyncService;
use Illuminate\Support\Facades\Date;

/**
 * 同步收盘价
 */
class CommandInitializeClosed implements CommandInterface
{
    const START_HOUR = '16:01';

    static function handle()
    {
        if (self::startable()) {
            $symbolSet = self::pluckUnRefreshed();
            SnapSyncService::headle($symbolSet);
        }
    }

    static function startable()
    {
        return self::isTimeUp() && Snap::hasTodayTrade() && !self::isFinished();
    }

    static function isAborted()
    {
        return !self::isFinished();;
    }

    static function isEmpty()
    {
        return false;
    }


    static function isFinished(): bool
    {
        return !self::pluckUnRefreshed(1);
    }

    private static function pluckUnRefreshed($limit = 0)
    {
        $date = Snap::lastTradeDate('date');
        $hour = self::START_HOUR;
        $lastCreateTime = "$date $hour";

        $obj = Snap::where('updated_at', '<', $lastCreateTime);
        if ($limit) {
            $obj->limit($limit);
        }
        $rs = $obj->pluck('symbol')->toArray();
        dump($rs);
        return $rs;
    }


    static function isTimeUp(): bool
    {
        $now = Date::now()->toDateTimeString();
        $date = Date::now()->toDateString();
        $starTime = "$date " . self::START_HOUR;
        return $now > $starTime;
    }


}
