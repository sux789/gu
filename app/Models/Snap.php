<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class Snap extends Model
{
    use HasFactory;

    static $initSymbolSet = ['sh601398', 'sh600519', 'sh601318'];

    protected $fillable = [
        'date',
        'trade_time',
        'name',
        'trade',
        'last',
        'date',
        'open',
        'high',
        'low',
        'changepercent',
        'turnoverratio',
        'pe',
        'mktcap',
        'nmc',
        'over_buy',
        'symbol',
        'vol',
    ];

    /**
     * 今日是否存在交易
     * 需要初始化后定时任务后才知道,所以用has
     */
    static function hasTodayTrade()
    {
        $todayTime = Date::now()->toDateString();
        return (bool)Snap::where('trade_time', '>', $todayTime)
            ->whereIn('symbol', self::$initSymbolSet)
            ->value('id');
    }


    static function lastTradeDate()
    {
        $maxTradeDate = self::max('date');
        $today = Date::now()->toDateString();
        return min($maxTradeDate, $today);
    }

    static function listTradeDate($limit = 30)
    {
        $rs = self::orderBy('date', 'desc')->distinct()->limit($limit)->pluck('date');
        return $rs ? $rs->toArray() : [];
    }
}
