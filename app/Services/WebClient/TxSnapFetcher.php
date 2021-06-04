<?php


namespace App\Services\WebClient;


use App\Common\Webgeter;


class TxSnapFetcher
{
    const URL_MAIN = 'http://qt.gtimg.cn/q=';


    static function handle($symbolSet)
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
