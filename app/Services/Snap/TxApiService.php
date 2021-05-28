<?php


namespace App\Services\Snap;


class TxApiService
{
    static $defaultSymbolSet = ['sh601398', 'sh600519'];

    static function lastTime()
    {
        $rs = TxApiService::get(self::$defaultSymbolSet);
        $lastTime='';
        foreach ($rs as $item){
            $lastTime=$item['fetch_time']??'';
            if($lastTime){
                break;
            }
        }
        return $lastTime;
    }

    static function get($symbolSet)
    {
        $rt = [];

        $symbolSet = array_unique((array)$symbolSet);
        if ($symbolSet) {
            $contents = TxWebFetchService::multiGet($symbolSet);

            foreach ($contents as $item) {
                $rs = TxWebParserService::handle($item);
                $rt = array_merge($rt, $rs);
            }
        }
        return $rt;
    }

}
