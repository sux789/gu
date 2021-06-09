<?php


namespace App\Services\WebClient;

use App\Common\Webgeter;
use Illuminate\Support\Facades\DB;

/**
 * 第三方均线接口
 */
class SinaMa
{
    private static function getTaskSymbol()
    {
        $sql = "SELECT
            stocks.symbol
        FROM
            stocks
            LEFT JOIN stock_mas ON stocks.symbol = stock_mas.symbol
        WHERE
            1
            AND stock_mas.symbol IS NULL
            LIMIT 1";
        $rs = DB::select($sql);
        $rt = '';
        if ($rs) {
            $rt = $rs[0]->symbol;
        }

        return $rt;
    }

    static function fetch($symbol)
    {
        $url = 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php';
        $url .= '/CN_MarketData.getKLineData?symbol={$symbol}&scale=60&datalen=1';
        $json = Webgeter::get($url);
        $rs = json_decode($json, true);
        $rawData = $rs[0] ?? [];
        $rt = [];
        if ($rawData) {
            $rt = ['symbol' => $symbol,
                'trade_time' => $rs['day'],
                'ma_price5' => $rs['ma_price5'],
                'ma_volume5' => $rs['ma_volume5'],
                'ma_price10' => $rs['ma_price10'],
                'ma_volume10' => $rs['ma_volume10'],
                'ma_price30' => $rs['ma_price30'],
                'ma_volume30' => $rs['ma_volume30'],
            ];
        }

        return $rt;
    }
}
