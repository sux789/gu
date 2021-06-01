<?php


namespace App\Services\Symbol;


use App\Models\Chgn;
use Illuminate\Support\Facades\DB;

class ChgnSymbolService
{
    const MIN_SYMBOL_NUM = 4000; // 抓取股票数至少 4 千

    /**
     * 统计代码数量
     * @return int
     */
    static function countSymbol()
    {
        return DB::table('chgn_symbol')->distinct()->count('symbol');
    }

    /**
     * 说明抓取成功
     * @return bool
     */
    static function validSymbol()
    {
        return self::countSymbol() > self::MIN_SYMBOL_NUM;
    }

    /**
     * 刷新
     */
    static function refresh()
    {
        if (self::validSymbol()) {
            // step 1 执行 delete
            $sql_delete = "delete from symbols ";
            $delete = Db::delete($sql_delete);

            // setp 2  执行 insert
            $created_at = time();
            $sql_data = "select distinct symbol,$created_at from chgn_symbol";
            $sql_insert = "insert into symbols (symbol,created_at) $sql_data";
            $insert = Db::insert($sql_insert);
            dump($delete);
            dump($insert);
        }
    }

    static function fix(){

    }
}
