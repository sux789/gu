<?php


namespace App\Services\Snap;


use App\Common\Webgeter;
use phpDocumentor\Reflection\Types\Self_;

class TxWebFetchService
{
    const URL_MAIN = 'http://qt.gtimg.cn/q=';


    static function multiGet($symbolSet)
    {
        $urls = self::getUrls($symbolSet);
        return Webgeter::get($urls);
    }


    static function getUrls($symbolList)
    {
        $urlList = [];
        $symbolList = (array)$symbolList;
        $codeChunks = array_chunk($symbolList, 200);
        $r = mt_rand(1, 999999);
        foreach ($codeChunks as $chunk) {
            $urlList[] = self::URL_MAIN . join(',', $chunk) . "&_r=$r";
        }
        return $urlList;
    }

}
