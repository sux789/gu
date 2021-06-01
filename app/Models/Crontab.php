<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crontab extends Model
{
    use HasFactory;

    protected $fillable = ['cmd', 'time_spent', 'title', 'name', 'state', 'result'];
}
