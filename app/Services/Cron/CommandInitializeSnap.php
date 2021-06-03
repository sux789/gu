<?php


namespace App\Services\Cron;


use App\Models\Snap;
use App\Services\Snap\SnapSyncService;
use Illuminate\Support\Facades\Date;

/**
 * 日快照初始化
 * -- 用不可能同时停牌数据更新快照
 */
class CommandInitializeSnap implements CommandInterface
{

    const BEGIN_HOUR = '09:31';

    static $initSymbolSet = ['sh601398', 'sh600519', 'sh601318'];

    static function handle()
    {
        if (!self::isDone()) {
            self::initToday();
        }else{
            echo "dine";
        }
        return true;
    }

    private static function initToday()
    {
        return SnapSyncService::headle(self::$initSymbolSet);
    }

    static function isDone(): bool
    {
        $lastUpdateAt = self::getTimeByHour(self::BEGIN_HOUR);
        return (bool)Snap::where('updated_at', '>', $lastUpdateAt)
            ->whereIn('symbol', self::$initSymbolSet)
            ->value('id');
    }

    static function getTimeByHour($hour = '00:00', $date = null)
    {
        $hour = trim($hour);
        $date = $date ?: Date::now()->toDateString();
        return "$date $hour";
    }

    static function startable()
    {
        return !self::isDone();
    }

    static function isAborted()
    {
        return !self::isDone();;
    }

    static function isEmpty()
    {
        return false;
    }

    static function isFinished()
    {
        return Snap::hasTodayTrade();
    }
}
