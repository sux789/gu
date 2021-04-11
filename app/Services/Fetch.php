<?php


namespace App\Services;

use GuzzleHttp\Client;

abstract class Fetch
{
    protected $httpClient;
    protected static $cacheLifeTime = 0;

    public function __construct()
    {
    }

    // abstract static function getData();

    static function httpGet($url, $data = [])
    {
        $client = new Client();
        $res = $client->request('GET', $url, $data);
        $statusCode = $res->getStatusCode();
        $body = $res->getBody();
        // call body handle
        $arr = json_decode($body);

        return null === $arr ? $body : $arr;

    }


}
