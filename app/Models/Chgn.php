<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chgn extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title','fetch_state','fetch_count','fetch_page'];
}
