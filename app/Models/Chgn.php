<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 新浪行情热门概念
 * Class Chgn
 * @package App\Models
 */
class Chgn extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title','fetch_state','fetch_count','fetch_page'];
}
