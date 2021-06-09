<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 记录任务计划执行状态和结果统计
 * Class Crontab
 * @package App\Models
 */
class Crontab extends Model
{
    use HasFactory;

    protected $fillable = ['cmd', 'time_spent', 'title', 'name', 'state', 'result'];
}
