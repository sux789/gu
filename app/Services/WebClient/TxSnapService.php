<?php


namespace App\Services\WebClient;


use App\Services\Snap\TxSnapFetcher;

class TxApiService
{
    static $defaultSymbolSet = ['sh601398', 'sh600519'];

    static function lastTime()
    {
        $rs = TxApiService::get(self::$defaultSymbolSet);
        $lastTime = '';
        foreach ($rs as $item) {

            $lastTime = $item['trade_time'] ?? '';

            if ($lastTime) {
                break;
            }
        }
        return $lastTime;
    }

    static function lastDate()
    {
        $time = self::lastTime();
        $info = explode(' ', $time);
        return $info[0];

    }

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
