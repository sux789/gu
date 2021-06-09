<?php


namespace App\Services\Snap;


use App\Models\Snap;
use App\Services\WebClient\TxSnapFetcher;
use App\Services\WebClient\TxSnapService;

/**
 * 快照同步服务
 */
class SnapSyncService
{

    // 一次处理多少个股票,应该TxSnapFetcher::PAGE_SIZE即800倍数
    const CHUNK_SIZE = 800;

    static function headle($symbolSet)
    {
        $rt = [];
        // 大数据分页, 内存和网络 削峰
        foreach (array_chunk((array)$symbolSet, self::CHUNK_SIZE) as $chunk) {
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
