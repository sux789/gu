<?php


namespace App\Services\Recommend;


use App\Models\Recommend;
use App\Services\Snap\TradeDayService;


class RecommendService
{
    /**
     * 算法配置
     * @var array
     */
    static $algorithms = [
        'overbuy' =>
            [
                'caller' => [\App\Services\Recommend\Algorithms\OverbuyAlgorithm::class, 'get'],
                'argv' => [],
            ],
    ];

    static function run()
    {
        $date = TradeDayService::lastDate();
        $data = [];

        foreach (self::$algorithms as $algorithm => $item) {
            $rs = call_user_func_array($item['caller'], $item['argv'] ?? []);
            Recommend::where(compact('date', 'algorithm'))->delete();
            foreach ($rs as $symbol) {
                $data = compact('date', 'algorithm', 'symbol');
                Recommend::create($data);
            }
        }
    }
}
