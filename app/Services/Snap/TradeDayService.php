<?php


namespace App\Services\Snap;


use App\Models\Snap;
use Illuminate\Support\Facades\Date;

/**
 * 股票快照
 * @note 所有方法依赖方法lastDateSetCache,对减少bug有好处
 */
class TradeDayService
{
    const DATE_SET_LEN = 30; //默认返回日期数,习俗30均线必须的

    /**
     * 最后一个交易日
     * @return mixed
     */
    static function lastDate()
    {
        $dateSet = self::lastDateSetCache();
        return $dateSet[0] ?? '';
    }

    /**
     * 最近交易日
     * @param int $len
     * @return array
     */
    static function lastDateSet($len = 30)
    {
        $rs= self::lastDateSetCache($len);
        return array_slice($rs,0,$len);
    }

    /**
     * 是否是交易日
     * @param null $date 空则查询当天
     * @return mixed
     */
    static function isTradingDay($date = null)
    {
        $date = $date ?: Date::now()->toDateString();
        $dateSet = self::lastDateSetCache();
        $search = array_search($date, $dateSet);
        return is_numeric($search);
    }

    /**
     * 今天是否是交易日
     * @return mixed
     */
    static function todayIsTradingDay()
    {
        return self::isTradingDay();
    }

    /**
     * 现在是否在交易时间
     * @return bool
     */
    static function nowIsTrading()
    {
        // step 1 $isInTradePeriod 检查时间是否在交易区间
        $isInTradePeriod = false;
        $nowHour = date('H:i');
        $isMorningTrade = $nowHour > '09:30' && $nowHour < '11:30';
        $isTailTrade = $nowHour > '13:00' && $nowHour < '15:00';
        $isInTradePeriod = $isMorningTrade or $isTailTrade;

        // step 2 check trade day
        return $isInTradePeriod && self::todayIsTradingDay();
    }

    /**
     * 交易日缓存
     * 发现读交易日地方多,统一一个缓存
     * @param int $len
     * @return mixed
     */
    private static function lastDateSetCache($len = 0)
    {
        static $cache = [
            'len' => 0,
            'rs' => [],
        ];


        $len = max(self::DATE_SET_LEN, $len);
        if ($len > $cache['len']) {
            $rs = Snap::orderBy('date', 'desc')->distinct()->limit($len)->pluck('date');
            $cache['rs'] = $rs ? $rs->toArray() : [];
            $cache['len'] = $len;
        }

        return $cache['rs'];
    }
}
