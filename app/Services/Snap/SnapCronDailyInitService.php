<?php


namespace App\Services\Snap;


use App\Models\Snap;
use App\Services\Misc\CronControlService;
use App\Services\Misc\CronStateService;
use http\Message\Body;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SnapCronDailyInitService
{
    const START_TIME = '09:31';

    static function old_handle()
    {
        if (!self::checkStartTime()) {
            echo "\n时间未到\n";
            return false;
        }

        if (self::has()) {
            echo "已经存在\n";
            return false;
        }

        self::handle();
    }

    static function handle($cmd='')
    {
        $lastTime = TxApiService::lastTime();

        CronStateService::setStart($cmd);
        if (!$lastTime) {
            CronStateService::setAbort($cmd);// 异常
        }
        if ($lastTime > Date::now()->toDateString()) {
            CronStateService::setFinish($cmd);//后续会抓今天数据
        } else {
            CronStateService::setEmpty($cmd); //后续不会抓今天数据
        }
    }

    static function getStartTime()
    {
        return self::today() . ' ' . self::START_TIME;
    }

    static function today()
    {
        return Date::now()->toDateString();
    }

    static function getCmdName()
    {
        return self::class;
    }

    static function has()
    {
        return CronStateService::has(self::getCmdName(), self::getStartTime());
    }


    static function checkStartTime()
    {
        return Date::now()->toDateTimeString() >= self::getStartTime();
    }
}
