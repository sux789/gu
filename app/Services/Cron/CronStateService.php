<?php


namespace App\Services\Cron;

use App\Models\Crontab as CrontabModel;
use App\Services\Snap\SnapAddService;
use Illuminate\Support\Facades\DB;


/**
 * 任务计划执行状态管理
 * Class CronStateService
 * @package App\Services\Cron
 */
class CronStateService
{
    const STATE_START = 100;
    const STATE_ABORT = 300;
    const STATE_EMPTY = 500;
    const STATE_FISHED = 900;
    static $stateDesc = [
        self::STATE_START => '运行中',
        self::STATE_ABORT => '异常退出',
        self::STATE_EMPTY => '运行无数据',
        self::STATE_FINISHED => '运行完成,不影响后续其他任务',
    ];


    static function setStarted($cmd, $remark = '')
    {
        $cron = CrontabModel::where('cmd', $cmd)->first();
        if ($cron) {
            $sql_bak = "insert into crontabs_logs select * from crontabs where id={$cron->id}";
            DB::insert($sql_bak);
            $cron->delete();
        }

        $state = self::STATE_START;
        $data = compact('cmd', 'remark', 'state');
        return CrontabModel::create($data);
    }

    static function setAborted($cmd, $remark = '')
    {
        $state = self::STATE_ABORT;
        $data = compact('cmd', 'remark', 'state');
        CrontabModel::updateOrCreate(['cmd' => $cmd], $data);
    }

    static function setFinished($cmd, $result = '')
    {
        $state = self::STATE_FISHED;
        $data = compact('cmd', 'result', 'state');
        CrontabModel::updateOrCreate(['cmd' => $cmd], $data);
    }

    static function setEmpty($cmd)
    {
        $state = self::STATE_EMPTY;
        $data = compact('cmd', 'state');
        CrontabModel::updateOrCreate(['cmd' => $cmd], $data);
    }

    static function isFinished($cmd, $time = null)
    {
        $cron = CrontabModel::where('cmd', $cmd);
        $cron->where('state', self::STATE_FISHED);
        if ($time) {
            $cron->where('created_at', '>', $time);
        }
        return $cron->value('id');
    }

    static function has($cmd, $time = null)
    {
        $cron = CrontabModel::where('cmd', $cmd);
        if ($time) {
            $cron->where('created_at', '>', $time);
        }
        return $cron->value('id');
    }

}
