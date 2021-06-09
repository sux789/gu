<?php


namespace App\Services\Cron\Commands;


use App\Models\Snap;
use App\Services\Cron\CommandBase;
use App\Services\Snap\SnapSyncService;
use Illuminate\Support\Facades\Date;

/**
 * 日快照初始化
 * -- 用不可能同时停牌数据更新快照
 */
class SnapInitCommand extends CommandBase
{

    const BEGIN_HOUR = '09:31';

    static $initSymbolSet = ['sh601398', 'sh600519', 'sh601318'];

    function handle()
    {
        self::initToday();

        if (self::isFinished()) {
            $this->setStateFinished();
        } else {
            $this->setStateAborted();
        }
    }

    function startable()
    {
        return !self::isFinished();
    }

    public static function initToday()
    {
        return SnapSyncService::headle(self::$initSymbolSet);
    }

    static function isFinished(): bool
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
}
