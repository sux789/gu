<?php


namespace App\Services\Recommend;


use App\Models\Overbuy;
use App\Models\Snap;

class OverbuyAlgorithm
{
    static function lists($days = 5)
    {
        $dateList = Snap::listTradeDate();
        $today = $dateList[0];

        $overbuySymbols = Overbuy::listLastSymbol(10);

        $rt = [];
        if ($overbuySymbols) {
            $rs = Snap::where('date', $today)
                ->whereBetween('changepercent', [-2, 2])
                ->where('turnoverratio', '>', 3)
                ->whereIn('symbol', $overbuySymbols)
                ->pluck('symbol');
            $rt = $rs ? $rs->toArray() : [];
        }

        return $rt;
    }
}
