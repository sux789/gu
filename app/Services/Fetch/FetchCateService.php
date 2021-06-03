<?php


namespace App\Services\Fetch;

use App\Services\Fetch;
use App\Models\Chgn;
use Illuminate\Support\Facades\DB;

/**
 * 提供所有分类
 * -- 总结 sync 同步到数据库 以后定时清理同步,每周执行一次就可以了
 * @package App\Services\Fetch
 */
class FetchCateService extends Fetch
{
    private $chgnModel;

    protected static $refreshTime = 3 * 86400;

    static $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodes';

    public function __construct(Chgn $chgnModel)
    {
        $this->chgnModel=$chgnModel;
    }

    static function getData()
    {
        $rs = self::httpGet(self::$url);
        $data = $rs[1][0][1][5][1];
        $rt = [];
        foreach ($data as $item) {
            $key = trim(substr($item[2], 5, 16));
            $rt[$key] = trim($item[0]);
        }
        return $rt;
    }

    static function get(){
        $data=self::getData();
        if(!empty(self::$refreshTime)){

        }
    }

    static function sync(){
        $data=self::getData();
        $rows=[];
        $create_at=time();
        $update_at=$create_at;
        foreach ($data as $id=>$title){
            $rows[]=compact('id','title','create_at','update_at');
        }
        if($rows){
            DB::table('chgn')->insert($rows);
        }
    }
}
