<?php


namespace App\Services\Misc;

use App\Services\Snap\SnapCronDailyInitService;
use App\Services\Snap\SnapSnapCronFinishDateService;
use App\Services\Snap\TxApiService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

// 运行控制
class CronControlService
{
    const DAY_TYPE_ALL = 100;
    const DAY_TYPE_WORK = 300;
    const FIELD_DESC = 'desc';
    const FIELD_DAY_TYPE = 'day_type';
    const FIELD_TIME_ON = 'time_on'; //格式 ['start' ,'endtime]
    const FIELD_ONCE = 'once';

    static $configs = [
        [
            'desc' => '每日初始化,检查当天是否有快照',
            'class' => SnapCronDailyInitService::class,
            'method' => 'handle',// default
            'ons' => [
                [
                    'begin' => '09:30',
                    'end' => '09:41',
                    'interval_minute' => 10, //  间隔分钟 0  是当天执行一次
                    'days' => [1, 2, 3, 4, 5], // 星期
                ],
            ],
            // and more ...
        ],
    ];

    static function handle()
    {
        foreach (self::$configs as $config) {
            $cmd = $config['class'] . "::" . $config['method'];
            $ons = $config['ons'];
            $class = $config['class'];
            $method = $config['method'];
            foreach ($ons as $timeOn) {
                if (self::checkTimeOn($timeOn, $cmd)) {
                    $ins = App::make($class);
                    $ins->$method($cmd);
                }
            }
        }
    }


    static function checkTimeOn($timeOn, $cmd)
    {
        // step $week 检查星期
        $checkDay = true;

        if ($days = $timeOn['days']) {
            $day = date('w');
            $checkDay = in_array($day, $days);
        }
        if (!$checkDay) {
            echo "检查星期错误";
            return false;
        }


        // step  $checkTime 时间在内
        $startTime = self::todayTime($timeOn['begin']);
        $endTime = self::todayTime($timeOn['end']);
        $now = Date::now()->toDateTimeString();
        $checkTime = ($now >= $startTime && $now < $endTime);
        if (!$checkTime) {
            echo "#检查时间错误";
            return false;
        }

        // step $checkInterval 检查时间
        $checkInterval = false;
        $maxCeatedTime = '';// 最后执行时间,不能超过
        if ($interval_minute = $timeOn['interval_minute']) {
            $maxCeatedTime = date('Y-m-d H:i:s', time() - $interval_minute * 60); // n分钟内没有执行
        } else {
            $maxCeatedTime = self::today();// 当天不存在
        }
        $exists = CronStateService::has($cmd, $maxCeatedTime);
        var_dump($exists);
        $checkInterval = !$exists; //
        if (!$checkInterval) {
            echo "$maxCeatedTime 内已经执行了\n ";
            return false;
        }

        return true;
    }


    static function todayTime($time)
    {
        return Date::now()->toDateString() . " $time";
    }

    static function today()
    {
        return Date::now()->toDateString();
    }


}
