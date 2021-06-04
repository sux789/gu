<?php


namespace App\Services\Recommend;


use App\Models\Recommend;
use App\Models\Snap;

class RecommendService
{
    /**
     * 算法配置
     * @var array
     */
    static array $algorithms = [
        'overbuy' =>
            [
                'caller' => [OverbuyAlgorithm::class, 'lists'],
                'argv' => [],
            ],
    ];

    static function run()
    {
        $date = Snap::lastTradeDate();
        $data = [];

        foreach (self::$algorithms as $algorithm => $item) {
            $rs = call_user_func_array($item['caller'], $item['argv'] ?? []);
            Recommend::where(compact('date', 'algorithm'))->delete();
            foreach ($rs as $symbol) {
                $data=compact('date','algorithm','symbol');
                Recommend::create($data);
            }
        }
    }
}
