<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test', function () {
    \App\Services\Fetch\FetchCodeService::handle();
    // \App\Services\Fetch\FetchCateTaskService::addTask(1);
    //\App\Services\Fetch\FetchCateTaskService::addTask(700004);
})->purpose('Display an inspiring quote');
