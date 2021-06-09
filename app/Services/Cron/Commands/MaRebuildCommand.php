<?php


namespace App\Services\Cron\Commands;

use App\Models\StockMa;
use App\Services\Cron\CommandBase;
use App\Services\Snap\TradeDayService;
use Illuminate\Support\Facades\DB;

/**
 * 计算均线,目的得到多头向上股票
 * @todo 除权后股票筛选不出来,应该重置价格 pre.price =cur.price - change_percent*pre.price
 */
class MaRebuildCommand extends CommandBase
{
    function handle()
    {
        self::syncSymbol();
        $rs = self::setMa(5)
            && self::setMa(10)
            && self::setMa(30, true);
        if ($rs) {
            $this->setStateFinished();
        } else {
            $this->setStateAborted();
        }
    }

    function startable()
    {
        return SnapSyncClosedCommand::isFinished() && self::isFinished();
    }

    private static function syncSymbol()
    {
        $lastTradeDate = TradeDayService::lastDate();

        $sql_insert = "	INSERT INTO stock_mas ( symbol) SELECT
            snaps.symbol
            FROM
                snaps
                LEFT JOIN stock_mas ON snaps.symbol = stock_mas.symbol
            WHERE
                snaps.date = '$lastTradeDate'
                AND stock_mas.symbol IS NULL
                ";
        return Db::insert($sql_insert);
    }


    private static function setMa($offset = 5, $updated_at = null)
    {
        $dateList = TradeDayService::lastDateSet($offset);

        $minDate = min($dateList);

        $sql = "
            UPDATE stock_mas main
            JOIN (
                SELECT `symbol`, AVG( trade ) AS ma_price{$offset}, avg( turnoverratio ) AS ma_volume{$offset}
            FROM snaps
            WHERE `date` >= '$minDate'
            GROUP BY `symbol`
                 ) AS ma
                ON main.`symbol` = ma.`symbol`
            SET main.ma_price{$offset} = ma.ma_price{$offset},
            main.ma_volume{$offset} = ma.ma_volume{$offset}
            ";
        if ($updated_at) {
            $sql .= ',updated_at=now()';
        }

        return DB::update($sql);
    }

    static function lastUpdatedAt()
    {
        return StockMa::max('updated_at');
    }

    static function isFinished()
    {
        return self::lastUpdatedAt() > SnapSyncClosedCommand::getStartTime();
    }
}
