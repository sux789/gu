<?php


namespace App\Services\Stock;


use App\Models\Snap;
use Illuminate\Support\Facades\DB;

class StockService
{
    static function listRecommend()
    {
        $date = Snap::lastTradeDate();
        $sql = "select snaps.* from snaps join recommends
            on snaps.symbol=recommends.symbol
            and snaps.date='$date'
            and recommends.date='$date'
                   ";
        $rs=DB::select($sql);
        return $rs;
    }
}
