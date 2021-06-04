<?php


namespace App\Services\Cron;


use App\Models\Overbuy;
use App\Models\Snap;
use Illuminate\Support\Facades\DB;

/**
 * 收盘涨停处理
 */
class CommandInitializeOverbuy implements CommandInterface
{
    static function handle(): bool
    {
        $rt = false;
        $date = Snap::lastTradeDate('date');
        if (!self::isFinished() && !empty($date)) {
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
        return $rt;
    }

    static function isFinished(): bool
    {
        $date = Snap::lastTradeDate();
        $val = Overbuy::where('date', $date)->value('id');
        return !empty($val);
    }

    static function startable(){
       return CommandInitializeClosed::isFinished() && !self::isFinished();
    }

    static function isAborted()
    {
        return !self::isFinished();
    }

    static function isEmpty()
    {
        return false;
    }

}
