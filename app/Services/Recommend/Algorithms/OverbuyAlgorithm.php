<?php


namespace App\Services\Recommend\Algorithms;


use App\Models\Overbuy;
use App\Models\Snap;
use App\Services\Snap\TradeDayService;

class OverbuyAlgorithm
{
    static function get($days = 5)
    {
        $dateList = TradeDayService::lastDateSet();
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
