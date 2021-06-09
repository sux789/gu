<?php


namespace App\Services\Chgn;

use App\Common\Webgeter;
use App\Models\Chgn;

/**
 * 同步中国概念
 */
class ChgnSyncService
{

    static $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodes';


    static function handle()
    {
        $map = self::fetch();
        foreach ($map as $id => $title) {
            $data = compact('id', 'title');
            Chgn::updateOrCreate(['id' => $id, 'title' => $title], $data);
        }
    }

    private static function fetch()
    {
        $content = Webgeter::get(self::$url);
        $rt = [];
        if ($rs = json_decode($content)) {
            $data = $rs[1][0][1][5][1];
            $rt = [];
            foreach ($data as $item) {
                $key = trim(substr($item[2], 5, 16));
                $rt[$key] = trim($item[0]);
            }
        }
        return $rt;
    }

}
