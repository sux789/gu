<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {

        $sql = str_replace("?", "'%s'", $event->sql);
        $log = vsprintf($sql, $event->bindings);
        if (is_numeric(stripos(PHP_SAPI, 'cli'))) {
            echo "\n:", $log, "\n";
        }

    }
}
