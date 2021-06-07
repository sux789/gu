<?php


namespace App\Common;


use Illuminate\Support\Facades\Cache;

class Lock
{
    const KEY_PREFIX = 's_lock';

    function get($key, $seconds = 60)
    {
        $key = self::formatKey($key);
        return Cache::add($key, 1, $seconds);
    }

    private function formatKey($key)
    {
        return self::KEY_PREFIX . trim($key);
    }
}
