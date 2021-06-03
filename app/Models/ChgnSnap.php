<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChgnSnap extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'symbol', 'name', 'changepercent', 'open',
            'high', 'low', 'mktcap', 'turnoverratio', 'trade', 'nmc', 'pb'
        ];
}
