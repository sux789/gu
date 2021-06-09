<?php


namespace App\Services\Snap;


use App\Models\Snap;
use App\Services\WebClient\TxSnapService;

/**
 * 快照同步服务
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
        $rs = TxSnapService::get($symbolSet);
        foreach ($rs as $item) {
            $symbol = $item['symbol'];
            $rt[$symbol] = Snap::updateOrCreate(['symbol' => $symbol, 'date' => $item['date']], $item);
        }
        return $rt;
    }

}
