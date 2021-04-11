<?php


namespace App\Services\Fetch;

use App\Services\Fetch;
use App\Models\Chgn;
use Illuminate\Support\Facades\DB;

/**
 * 分类任务表
 * @package App\Services\Fetch
 */
class FetchCateTaskService
{
    const STATE_UNFETFHED = -1;

    static function getTask()
    {
        $rt = DB::table('chgns')
            ->where('cur_fetch_count', self::STATE_UNFETFHED)
            ->where('id', '!=', 700014)
            ->where('id', '!=', 700015)
            ->where('id', '!=', 700016)
            ->select(['id', 'cur_fetch_page', 'cur_fetch_count'])
            ->first();
        return $rt;
    }

    static function addTask($chgn_id)
    {
        $obj = Chgn::find($chgn_id);
        if ($obj) {
            $obj->cur_fetch_page = $obj->cur_fetch_page + 1;
            $obj->cur_fetch_count = -1;
            $obj->save();
        }
        return $obj->cur_fetch_page;
    }

    static function endTask($chgn_id, $count)
    {
        $obj = Chgn::find($chgn_id);
        if ($obj) {
            $obj->cur_fetch_count = $count;
            $obj->save();
        }
    }
}
