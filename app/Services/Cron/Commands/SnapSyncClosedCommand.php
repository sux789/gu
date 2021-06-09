<?php


namespace App\Services\Cron\Commands;


use App\Models\Snap;
use App\Services\Cron\CommandBase;
use App\Services\Snap\SnapSyncService;
use App\Services\Snap\TradeDayService;
use Illuminate\Support\Facades\Date;

/**
 * 同步收盘价
 */
class SnapSyncClosedCommand extends CommandBase
{
    const START_HOUR = '16:30';

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
        return self::isTimeUp() && TradeDayService::todayIsTradingDay() && !self::isFinished();
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
        $lastDate = TradeDayService::lastDate();
        $hour = self::START_HOUR;
        $lastCreateTime = "$lastDate $hour";

        $obj = Snap::whereBetween('updated_at', [$lastDate, $lastCreateTime]);
        if ($limit) {
            $obj->limit($limit);
        }
        $rs = $obj->pluck('symbol')->toArray();

        return $rs;
    }


    private static function isTimeUp(): bool
    {
        $now = Date::now()->toDateTimeString();
        return $now > self::getStartTime();
    }

    static function getStartTime(): string
    {
        $date = Date::now()->toDateString();
        return "$date " . self::START_HOUR;
    }
}
