<?php


namespace App\Services\Snap;


use App\Models\Snap;
use Illuminate\Support\Facades\DB;

/**
 * 股票快照
 * Class SnapService
 * @package App\Services\Snap
 */
class SnapService
{


    static function getUnUpdatedSymbolSet($updated_at)
    {
        return Snap::where('updated_at', '<', $updated_at)->pluck('symbol')->toArray();
    }


    /**
     * 从同步新股
     * @return array
     */
    static function listUnSyncSymbol()
    {
        // 读取
        $sql = "SELECT
            symbols.symbol
        FROM
            symbols
            LEFT JOIN snaps ON symbols.symbol = snaps.symbol

        where
				snaps.symbol IS NULL
            LIMIT 1000";
        $rs = DB::select($sql);

        $rt = array_column($rs, 'symbol');
        return $rt;
    }

    /**
     * 读取热点代码,便于实时更新
     */
    static function getHotSymbol()
    {

    }

    /**
     * 今日是否存在交易
     */
    static function hasTodayTrade()
    {

    }
}
