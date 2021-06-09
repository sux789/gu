<?php


namespace App\Services\Cron\Commands;


use App\Models\Overbuy;
use App\Services\Cron\CommandBase;
use App\Services\Snap\TradeDayService;
use Illuminate\Support\Facades\DB;

/**
 * 收盘涨停处理
 */
class OverbuyInitCommand extends CommandBase
{
    function handle()
    {
        self::initOverBy();

        if (self::isFinished()) {
            $this->setStateFinished();
        } else {
            $this->setStateAborted();
        }
    }

    function startable()
    {
        return !self::isFinished() && SnapSyncClosedCommand::isFinished() ;
    }

    static function initOverBy()
    {
        $date = TradeDayService::lastDate();
        $sql = "INSERT INTO overbuys ( symbol, date, over_buy, changepercent ) SELECT
            symbol,
            date,
            over_buy,
            changepercent
            FROM
                snaps
            WHERE
                date = '$date'
                AND changepercent > 9
                AND ( changepercent > 11 OR over_buy > 0 );";
        $rt = DB::insert($sql);
    }

    static function isFinished(): bool
    {
        $date = TradeDayService::lastDate();
        $val = Overbuy::where('date', $date)->value('id');
        return !empty($val);
    }
}
