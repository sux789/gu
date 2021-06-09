<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 推荐结果,记录算法名称推荐日期
 * @package App\Models
 */
class Recommend extends Model
{
    use HasFactory;

    protected $fillable = [
        'algorithm',
        'date',
        'symbol',
    ];
}
