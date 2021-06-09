<?php


namespace App\Services\Cron;


/**
 * 定时任务命令:便于外部统一命令调用,异常处理和状态记录
 */
abstract class CommandBase
{

    /**
     * 建议 主要逻辑和本函数分开,本函数执行主要逻辑和设置处理状态
     * @return mixed
     */
    protected abstract function handle();


    function startable()
    {
        return true;
    }

    final function run()
    {
        if ($this->startable()) {
            $commandName = $this->getCommandName();
            echo "\n### run $commandName ###\n";
            $started=CronStateService::setStarted($commandName);
            if($started){
                try {
                    $this->handle();
                } catch (\Throwable $e) {
                    $this->setStateAborted();
                    echo $e->getMessage();
                }
            }
        }
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
