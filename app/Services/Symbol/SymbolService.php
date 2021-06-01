<?php


namespace App\Services\Symbol;


use App\Models\Chgn;
use App\Models\Symbol;
use Illuminate\Support\Facades\DB;

class SymbolService
{
    const MIN_SYMBOL_NUM = 4000; // 抓取股票数至少 4 千
    const FETCH_STATE_OK = 1; // 抓取

    static function listForFetch()
    {
        $rs = DB::table('symbols')
            ->where('fetch_state', self::FETCH_STATE_OK)
            ->get('symbol')
            ->toArray();
        return array_column($rs, 'symbol');
    }
}
