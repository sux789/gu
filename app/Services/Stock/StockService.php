<?php


namespace App\Services\Stock;

use App\Services\Snap\TradeDayService;
use Illuminate\Support\Facades\DB;

/**
 * 股票信息维护
 * 拼音,代码,名称,权重,多头向上等维护
 */
class StockService
{

    /**
     * 读取推荐数据
     * @return array
     */
    static function listRecommend()
    {
        $date = TradeDayService::lastDate();
        $sql = "select snaps.* from snaps join recommends
            on snaps.symbol=recommends.symbol
            and snaps.date='$date'
            and recommends.date='$date'
                   ";
        $rs = DB::select($sql);
        return $rs;
    }

    /**
     * 同步新股代码
     * @return bool
     */
    static function syncNew()
    {
        $lastTradeDate = TradeDayService::lastDate();

        $sql_insert = "	INSERT INTO stocks ( symbol, `name`, `code` ) SELECT
            snaps.symbol,
            snaps.`name`,
            SUBSTR( snaps.symbol, 3 )
            FROM
                snaps
                LEFT JOIN stocks ON snaps.symbol = stocks.symbol
            WHERE
                snaps.date = '$lastTradeDate'
                AND stocks.symbol IS NULL
                LIMIT 1000";
        return Db::insert($sql_insert);
    }

}
