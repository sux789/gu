<?php


namespace App\Services\Snap;


use App\Models\Snap;
use Illuminate\Support\Facades\DB;

/**
 * 同步数据,根据代码抓取然后
 */
class SnapSyncService
{

    static function headle($symbolSet)
    {
        $rt = [];
        // 大数据分页, 内存和网络 削峰
        foreach (array_chunk((array)$symbolSet, 1000) as $chunk) {
            $rs = self::handleBySetp($chunk);
            $rt = array_merge($rs);
        }
        return $rt;
    }

    private static function handleBySetp($symbolSet)
    {
        $rt = [];
        $rs = \App\Services\WebClient\TxApiService::get($symbolSet);
        foreach ($rs as $item) {
            $symbol = $item['symbol'];
            $rt[$symbol] = Snap::updateOrCreate(['symbol' => $symbol], $item);
        }
        return $rt;
    }

}
