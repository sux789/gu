<?php


namespace App\Services\Snap;


use App\Models\Snap;
use Illuminate\Support\Facades\DB;

class SnapSyncService
{

    static function run()
    {
        // step 1 跟新状态
        // step 1 抓取保存
    }

    static function runNew()
    {
        $symbolSet = self::listSymbolNeverFetch();
        foreach (array_chunk($symbolSet, 1000) as $chunk) {
            self::fetchAndSave($chunk);
        }
    }

    /**
     * 读取symbols库中在,但是snaps 表中不存在
     */
    static function listSymbolNeverFetch()
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

    private static function fetchAndSave($symbolSet)
    {
        $rs = TxApiService::get($symbolSet);
        foreach ($rs as $item) {
            Snap::create($item);
        }
    }

    static function add($symbolSet)
    {
        $rs = TxApiService::get($symbolSet);
        foreach ($rs as $item) {
            Snap::create($item);
        }
    }

    private static function handleBySetp($symbolSet)
    {
        $rt = [];
        $rs = TxApiService::get($symbolSet);
        foreach ($rs as $item) {
            $symbol = $item['symbol'];
            $rt[$symbol] = Snap::updateOrCreate(['symbol' => $symbol], $item);
        }
        return $rt;
    }

    static function headle($symbolSet){
        $rt=[];
        foreach (array_chunk((array)$symbolSet, 1000) as $chunk) {
            $rs=self::handleBySetp($chunk);
            $rt=array_merge($rs);
        }
        return $rt;
    }
}
