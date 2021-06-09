<?php


namespace App\Services\Cron;

use App\Models\Crontab as CrontabModel;
use App\Services\Snap\SnapAddService;
use Illuminate\Support\Facades\DB;


/**
 * 任务计划执行状态管理
 * @note 比crontab -e 多了简单管理
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
        $rt = false;

        if (!self::hasRunning($cmd)) {
            // step 1 backup && clear
            $sql_bak = "insert into crontabs_logs select * from crontabs where cmd='$cmd' ";
            DB::insert($sql_bak);
            CrontabModel::where('cmd', $cmd)->delete();

            // step 2 state new
            $state = self::STATE_START;
            $data = compact('cmd', 'remark', 'state');
            $rt = CrontabModel::create($data);
        }

        return $rt;
    }

    static function hasRunning($cmd, $expire = null): int
    {
        $id = CrontabModel::where('cmd', $cmd)
            ->where('state', self::STATE_START)
            ->value('id');
        return boolval($id);
    }

    static function setAborted($cmd, $remark = '')
    {
        $state = self::STATE_ABORT;
        $data = compact('cmd', 'remark', 'state');
        CrontabModel::updateOrCreate(['cmd' => $cmd], $data);
    }

    static function setFinished($cmd, $remark = '')
    {
        $state = self::STATE_FISHED;
        $data = compact('cmd', 'remark', 'state');
        CrontabModel::updateOrCreate(['cmd' => $cmd], $data);
    }

    static function setEmpty($cmd, $remark = '')
    {
        $state = self::STATE_EMPTY;
        $data = compact('cmd', 'state', 'remark');
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
