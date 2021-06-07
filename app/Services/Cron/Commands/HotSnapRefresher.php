<?php


namespace App\Services\Cron\Commands;


use App\Models\Snap;
use App\Services\Cron\CommandBase;
use App\Services\Snap\SnapSyncService;
use Illuminate\Support\Facades\Date;

/**
 * 同步收盘价
 */
class HotSnapRefresher extends CommandBase
{
    static $period = [
        ['09:40', '11:29'],
        ['13:01', '14:58'],
    ];

    function handle()
    {
        self::refresh();
        $this->setStateFinished();
    }

    function startable()
    {
        return self::isTimeUp() && Snap::hasTodayTrade() && !self::isFinished();
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

    private static function pluckUnRefreshed($limit = 0)
    {
        $date = Snap::lastTradeDate();
        $lastUnixTime = time() - 60;
        $lastCreateTime = date('Y-m-d H:i:s', $lastUnixTime);


        $obj = Snap::where('updated_at', '<', $lastCreateTime);
        if ($limit) {
            $obj->limit($limit);
        }
        $rs = $obj->pluck('symbol')->toArray();

        return $rs;
    }

    static function isTimeUp($now = null): bool
    {
        $now = $now ?: Date::now()->toDateTimeString();
        $today = Date::now()->toDateString();
        $rt = false;
        foreach (self::$period as $item) {
            list($startHour, $endHour) = $item;
            if ($now > "$today $startHour" && $now < "$today $endHour") {
                $rt = true;
                break;
            }
        }
        return $rt;
    }
}
