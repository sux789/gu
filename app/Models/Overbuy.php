<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 涨停记录
 * Class Overbuy
 * @package App\Models
 */
class Overbuy extends Model
{
    use HasFactory;

    static function listLastSymbol($dayOffset = 5)
    {
        $startDate = self::getDateOffset($dayOffset);
        $rs = self::where('date', '>=', $startDate)->distinct()->pluck('symbol');
        return $rs ? $rs->toArray() : [];
    }

    static function listDate($limit = 30)
    {
        $rs = self::orderBy('date', 'desc')->distinct()->limit($limit)->pluck('date');
        return $rs ? $rs->toArray() : [];
    }

    private static function getDateOffset($offset)
    {
        $list = self::listDate($offset);
        return min($list);
    }
}
