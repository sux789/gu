<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

/**
 * 快照,实时数据
 */
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

}
