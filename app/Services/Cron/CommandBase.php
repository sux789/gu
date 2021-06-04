<?php


namespace App\Services\Cron;


use App\Services\Cron\CronStateService;
use Illuminate\Support\Facades\App;


/**
 * 定时任务命令:便于外部统一命令调用,异常处理和状态记录
 */
abstract class CommandBase
{
    protected $runState = 0;

    protected static $ins = null;

    /**
     * 建议 主要逻辑和本函数分开,本函数执行主要逻辑和设置处理状态
     * @return mixed
     */
    abstract function handle();


    function startable()
    {
        return true;
    }

    final static function run()
    {
        if (null === self::$ins) { // 定时任务,不同时进行
            $ins = App::make(static::class);
            if ($ins->startable()) {
                CronStateService::setStarted($ins->getCommandName());
                $ins->handle();
            }
        }
        self::$ins = null;
    }

    protected function setStateFinished($remark = '')
    {
        return CronStateService::setFinished($this->getCommandName(), $remark);
    }

    protected function setStateAborted($remark = '')
    {
        return CronStateService::setAborted($this->getCommandName(), $remark);
    }

    static function getCommandName()
    {
        return static::class;
    }
}
