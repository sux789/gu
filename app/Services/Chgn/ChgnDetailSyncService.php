<?php


namespace App\Services\Chgn;

use App\Common\Webgeter;
use App\Models\ChgnSnap;

/**
 * 同步概念对应股票
 * 用于得到股票代码,得到概念
 * 执行 每周日凌晨进行一次 refreshAll
 */
class ChgnDetailSyncService
{
    const PAGE_SIZE = 100;

    static function handle()
    {
        while ($task = ChgnDetailTask::take()) {
            $chgn_id = $task['id'];
            $fetch_page = $task['fetch_page'];
            $rs = self::fetch($chgn_id, $fetch_page, self::PAGE_SIZE);
            $count = count($rs);
            if ($count) {
                $isSaved = self::save($rs);
                throw_if(!$isSaved);
            }
            $isClosed = $count < self::PAGE_SIZE;
            if ($isClosed) {
                ChgnDetailTask::close($chgn_id, $count);
            } else {
                ChgnDetailTask::add($chgn_id, $count);
            }
            sleep(5);
        }

    }

    private static function fetch($chgn, $page = 1, $num = 120)
    {
        $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/';
        $url .= "Market_Center.getHQNodeData?page={$page}&num={$num}&sort=symbol&asc=1&node=chgn_{$chgn}&symbol=&_s_r_a=init";
        $json = Webgeter::get($url);
        $rs = json_decode($json, true);

        throw_if(is_null($rs));

        return is_array($rs) ? $rs : [];
    }

    private static function save($rs)
    {
        $fields = (new ChgnSnap)->getFillable();
        $fieldsFillableMap = array_fill_keys($fields, true);
        foreach ($rs as &$item) {
            $item = array_intersect_key($item, $fieldsFillableMap);
        }
        unset($item);// 重要习惯 foreach & 之后
        return ChgnSnap::insert($rs);
    }

    static function refreshAll()
    {
        ChgnSnap::where('id', '>', 0)->delete();
        ChgnDetailTask::reset();
        self::handle();
    }
}
