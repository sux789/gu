<?php


namespace App\Services\WebClient;


/**
 * 腾讯股票快照
 *
 * 分为读取类TxSnapFetcher 和解析类 TxSnapParser 的组合
 * TxSnapFetcher异步抓取和分块削峰
 */
class TxSnapService
{

    static function get($symbolSet)
    {
        $rt = [];

        $symbolSet = array_unique((array)$symbolSet);
        if ($symbolSet) {
            $contents = TxSnapFetcher::handle($symbolSet);

            foreach ($contents as $item) {
                $rs = TxSnapParser::handle($item);
                $rt = array_merge($rt, $rs);
            }
        }
        return $rt;
    }

}
