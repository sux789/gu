<?php

namespace App\Http\Controllers;

use App\Http\HeaderDebug;
use App\Services\Stock\StockService;
use Illuminate\Http\Request;

class Home extends Controller
{
    function index()
    {

        $data = ['list' => StockService::listRecommend()];
        return view('index', $data);
    }
}
