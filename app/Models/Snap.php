<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snap extends Model
{
    use HasFactory;
    protected $fillable=[
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
        'pe',
        'mktcap',
        'nmc',
        'over_buy',
        'symbol',
        'vol',
        ];

}
