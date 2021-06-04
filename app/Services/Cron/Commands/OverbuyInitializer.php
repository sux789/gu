<?php


namespace App\Services\Cron\Commands;


use App\Models\Overbuy;
use App\Models\Snap;
use App\Services\Cron\CommandBase;
use Illuminate\Support\Facades\DB;

/**
 * 收盘涨停处理
 */
class OverbuyInitializer extends CommandBase
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
        return ClosedInitializer::isFinished() && !self::isFinished();
    }

    static function initOverBy()
    {
        $date = Snap::lastTradeDate();
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
        $date = Snap::lastTradeDate();
        $val = Overbuy::where('date', $date)->value('id');
        return !empty($val);
    }
}
