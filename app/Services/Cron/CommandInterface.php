<?php


namespace App\Services\Cron;


interface  CommandInterface
{
    static function startable();

    static function handle();

    static function isAborted();

    static function isFinished();

    static function isEmpty();
}
