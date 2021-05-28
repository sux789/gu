<?php


namespace App\Common;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

class Webgeter
{
    static function get($urls, $callback = null)
    {
        $rt = [];
        $rs = self::getContent($urls);
        if (is_array($rs)) {
            $rt = is_array($urls) ? $rs : current($urls);
        }
        return $rt;
    }

    static function getContent($urls)
    {
        $urls = (array)$urls;
        $client = new Client();
        $promises = [];
        foreach ($urls as $key => $url) {
            $promises[$key] = $client->getAsync($url);
        }
        $rs = \GuzzleHttp\Promise\Utils::unwrap($promises);
        $rt = [];
        foreach ($rs as $key => $item) {
            $rt[$key] = $item->getBody()->getContents();
        }
        return $rt;
    }


}
