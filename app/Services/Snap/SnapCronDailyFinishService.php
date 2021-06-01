<?php


namespace App\Services\Snap;


use App\Models\Snap;
use http\Message\Body;
use Illuminate\Support\Facades\DB;

class SnapCronDailyFinishService extends SnapCronBaseService
{
    const END_TIME = '16:00';

    static function getSymbolSet()
    {
        $rs = self::pluckUnRefreshed();
        return $rs;
    }

    static function isFinished()
    {
        return !self::pluckUnRefreshed();
    }

    private static function pluckUnRefreshed()
    {
        $date = Snap::min('date');
        $endTime = self::END_TIME;
        $lastCreateTime = "$date $endTime";
        return Snap::where('updated_at', '<', $lastCreateTime)->pluck('symbol')->toArray();
    }

}
