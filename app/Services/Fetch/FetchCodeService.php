<?php


namespace App\Services\Fetch;

use App\Models\Chgn;
use App\Services\Fetch;
use Illuminate\Support\Facades\DB;


/**
 * 提供所有code
 * 代码内部的task同步到持久,内部算法,隔离了
 * @package App\Services\Fetch
 */
class FetchCodeService extends Fetch
{
    const PAGE_SIZE = 100;

    private $chgnModel;
    private static $taskList = [];

    protected static $refreshTime = 3 * 86400;

    static $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodes';

    public function __construct(Chgn $chgnModel)
    {
        $this->chgnModel = $chgnModel;
    }

    static function initTask()
    {
        static $init = false;
        if (!$init) {
            $init = true;
            $chgnList = Chgn::all();
            foreach ($chgnList as $key => $item) {
                self::addTask($item['id']);
            }
        }
    }

    static function getByChgn($chgn, $page = 1, $num = 80)
    {
        $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/';
        $url .= "Market_Center.getHQNodeData?page={$page}&num={$num}&sort=symbol&asc=1&node=chgn_{$chgn}&symbol=&_s_r_a=init";
        $rt = self::httpGet($url);
        return is_array($rt) ? $rt : false;
    }

    static function handle()
    {
        $loops = 0;
        $task=FetchCateTaskService::getTask();
        while ($task=FetchCateTaskService::getTask()) {
            $loops++;
            if ($loops > 4000) {
                break;
            }
            $chgn_id=$task->id;
            $page=$task->cur_fetch_page;
            $num = self::PAGE_SIZE;
            $rs = self::getByChgn($chgn_id, $page, $num);
            if (!is_array($rs)) {
                die;
            }
            $count = count($rs);
            if($count){
                $rs=self::save($rs,$chgn_id, $page);
                if(!$rs){
                    die("save error");
                }
            }

            sleep(1);
            echo "\nchgn_id $chgn_id, page $page, num $num ,count $count";
            // print_r($rs);
            echo "\n";
            if ($count < $num) {
                FetchCateTaskService::endTask($chgn_id,$count);

            } else {
                FetchCateTaskService::addTask($chgn_id);
            }
        }
    }

    static function _handle()
    {
        self::initTask();

        $count = 0;
        while (self::$taskList) {
            $count++;
            if ($count > 999) {
                break;
            }

            $chgn = key(self::$taskList);
            $page = self::$taskList[$chgn];
            $num = self::PAGE_SIZE;
            $rs = self::getByChgn($chgn, $page, $num);
            if (!is_array($rs)) {
                die;
            }
            $count = count($rs);


            sleep(1);
            echo "\n$chgn, $page, $num ,$count " . count(self::$taskList);
            print_r($rs);
            echo "\n";
            if ($count < $num) {
                self::removeTask($chgn);
            } else {
                self::addTask($chgn);
            }
        }

    }

    static function save($rows, $chgn_id, $page)
    {
        $fields = ['symbol', 'name', 'changepercent', 'open',
            'high', 'low', 'mktcap', 'turnoverratio', 'trade', 'nmc',
        ];
        $saveList = [];
        foreach ($rows as $row) {
            $item=[];
            //$item = array_intersect($row,array_flip($fields));
            $item['chgn_id'] = $chgn_id;
            $item['page'] = $page;
            foreach ($fields as $field){
                $item[$field]=$row->$field;
            }
            $saveList[] = $item;
        }
        return DB::table('chgn_symbol')->insert($saveList);
    }

    static function addTask($chgn)
    {
        if (isset(self::$taskList[$chgn])) {
            self::$taskList[$chgn] += 1;
        } else {
            self::$taskList[$chgn] = 1;
        }
    }

    static function removeTask($chgn)
    {
        unset(self::$taskList[$chgn]);
    }
}
