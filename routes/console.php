<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\Sd;

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


Artisan::command('sd {act=handle}', function (Sd $sd) {
    if ($argv = $this->arguments()) {
        // dump($argv);
    }
    $class = get_class($this);
    $act = $this->argument('act');
    if ($act) {
        $this->info("## exec $class->$act ##");
    }

    if (method_exists($sd, $act)) {
        $sd->$act();
    } else {

        $this->info("# method $class->$act  not exists ");
    }


});


Artisan::command('cron', function (\App\Console\Commands\Cron $cron) {
    $cron->handle();
});
