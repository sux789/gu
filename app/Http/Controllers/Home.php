<?php

namespace App\Http\Controllers;

use App\Http\HeaderDebug;
use Illuminate\Http\Request;

class Home extends Controller
{
    //
    function index(){
     $obj=app()->make("FetchCate");
     $obj->sync();
    }
}
