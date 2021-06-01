<?php


namespace App\Services\Snap;


use App\Models\Snap;
use Illuminate\Support\Facades\DB;

class SnapCronAddService extends SnapCronBaseService
{

    static function getSymbolSet()
    {
        // 读取
        $sql = "SELECT
            symbols.symbol
        FROM
            symbols
            LEFT JOIN snaps ON symbols.symbol = snaps.symbol

        where
				snaps.symbol IS NULL
            LIMIT 1000";
        $rs = DB::select($sql);

        $rt = array_column($rs, 'symbol');
        return $rt;
    }

}
