<?php


namespace App\Services\Chgn;


use App\Models\Chgn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 爬虫概念对应股票快照
 * 1 请求分页,每次请求数量会有限制 2 支持任务继续,新浪有反爬虫机制
 */
class ChgnDetailTask
{
    const FETCH_STATE_CLOSED = 2;
    const FETCH_STATE_PROCESSING = 0;
    static $fetchStateDes = [
        self::FETCH_STATE_PROCESSING => '待处理',
        self::FETCH_STATE_CLOSED => '已经完成关闭',
    ];

    /**
     * 初始化,重新全部爬一遍
     */
    static function reset()
    {
        $taskInitInfo = [
            'fetch_page' => 1,
            'fetch_count' => 0,
            'fetch_state' => 0
        ];
        $table = (new Chgn())->getTable();
        DB::table($table)->update($taskInitInfo);
    }

    /**
     * 领取任务
     * @return mixed
     */
    static function take()
    {
        $rt = Chgn::where('fetch_state', self::FETCH_STATE_PROCESSING)
            ->whereNotIn('id', [700014, 700015, 700016, 700095, 700002, 730362])
            ->select(['id', 'fetch_page'])
            ->first()
            ->toArray();
        return $rt;
    }

    /**
     * 未完成,添加一页新任务
     * @param $chgn_id
     * @param int $lastCount
     * @return int
     */
    static function add($chgn_id, $lastCount = 0)
    {
        $obj = Chgn::find($chgn_id);
        if ($obj) {
            $obj->fetch_state = self::FETCH_STATE_PROCESSING;
            $obj->fetch_page = $obj->fetch_page + 1;
            $obj->fetch_count = $obj->fetch_count + $lastCount;
            $obj->save();
        }
        return $obj->fetch_page;
    }

    /**
     * 完成,关闭任务
     * @param $chgn_id
     * @param int $lastCount
     */
    static function close($chgn_id, $lastCount = 0)
    {
        $obj = Chgn::find($chgn_id);
        if ($obj) {
            $obj->fetch_state = self::FETCH_STATE_CLOSED;
            $obj->fetch_count = $obj->fetch_count + $lastCount;
            $obj->save();
        }
    }
}
