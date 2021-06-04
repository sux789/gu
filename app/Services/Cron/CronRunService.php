<?php


namespace App\Services\Cron;

use App\Services\Snap\SnapCronDailyInitService;
use App\Services\Snap\SnapSnapCronFinishDateService;
use GuzzleHttp\Promise\Promise;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;

/**
 * 任务计划运行控制,便于管理
 * Class CronControlService
 * @package App\Services\Cron
 */
class CronRunService
{
    static function handle()
    {
        foreach (config('cron') as $config) {
            $class = $config['cmd']['class'] ?? '';
            $method = $config['cmd']['method'] ?? 'handle';
            $cmd = "$class::$method";
            $ons = $config['ons'];
            foreach ($ons as $timeOn) {
                if (self::checkTimeOn($timeOn, $class)) {
                    $ins = App::make($class);
                    $ins->run();
                }
            }
        }
    }

    private static function checkTimeOn($timeOn, $cmd)
    {
        // step 1 $week 检查星期
        $checkDay = true;

        if ($days = $timeOn['days']) {
            $day = date('w');
            $checkDay = in_array($day, $days);
        }
        if (!$checkDay) {
            echo "检查星期错误";
            return false;
        }


        // step 2 $checkTime 时间在内
        $startTime = self::todayTime($timeOn['begin']);
        $endTime = self::todayTime($timeOn['end']);
        $now = Date::now()->toDateTimeString();
        $checkTime = ($now >= $startTime && $now < $endTime);
        if (!$checkTime) {
            echo "\n{$cmd}检查时间错误";
            return false;
        }

        // step 3 $checkInterval 检查时间
        $checkInterval = false;
        $intervalTimeBegin = '';// 最后执行时间,不能超过
        if ($interval_minute = $timeOn['interval_minute']) {
            $intervalTimeBegin = date('Y-m-d H:i:s', time() - $interval_minute * 60); // n分钟内没有执行
        } else {
            $intervalTimeBegin = self::today();// 当天不存在
        }
        $exists = CronStateService::has($cmd, $intervalTimeBegin);

        $checkInterval = !$exists; //
        if (!$checkInterval) {
            echo "$intervalTimeBegin 内已经执行了\n ";
            return false;
        }

        return true;
    }


    static function todayTime($time): string
    {
        return Date::now()->toDateString() . " $time";
    }

    static function today(): string
    {
        return Date::now()->toDateString();
    }


}
