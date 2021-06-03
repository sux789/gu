<?php


namespace App\Services\WebClient;

/**
 * 腾讯股票快照解析器,
 * 根据新浪改了数据库字段名,代码没有再修改
 */
class TxSnapParser
{

    const POS_NAME = 1;
    const POS_PRICE_NOW = 3;
    const POS_PRICE_LAST = 4;
    const POS_PRICE_OPEN = 5;
    const POS_PRICE_TOP = 33;
    const POS_PRICE_BOTTOM = 34;
    const POS_PRICE_RATE = 32;
    const POS_VOL = 6;
    const POS_VOL_RATE = 38;

    const POS_TIME = 30;
    const POS_PE = 39;
    const POS_TV = 45;
    const POS_CV = 44;
    const POS_SELL_1 = 19;
    const POS_SELL_1_VOL = 20;
    const POS_BUY_1 = 9;
    const POS_BUY_1_VOL = 10;


    static function handle($result)
    {
        $rt = [];

        $result = iconv('GBK', 'UTF-8', $result);
        $lines = explode(";", $result);
        foreach ($lines as $line) {
            $rs = self::parseRawLine($line);
            if (isset($rs[66])) {
                $rs = Self::toAssoc($rs);
                $rt[$rs['symbol']] = $rs;
            }
        }
        return $rt;
    }


    private static function parseDate($data)
    {
        $rt = '';
        if (isset($data[self::POS_TIME])) {
            $rt = date('Y-m-d', strtotime($data[self::POS_TIME]));
        }
        return $rt;
    }


    private static function toAssoc($data)
    {
        $rt = [];
        $isSh = false !== array_search('sh', $data);
        $prefix = $isSh ? 'sh' : 'sz';

        if ($date = self::parseDate($data)) {
            $rt['date'] = $date;
            $rt['trade_time'] = date('Y-m-d H:i:s', strtotime($data[self::POS_TIME]));
            $rt['name'] = $data[self::POS_NAME];
            $rt['trade'] = $data[self::POS_PRICE_NOW];
            $rt['last'] = $data[self::POS_PRICE_LAST];
            $rt['open'] = $data[self::POS_PRICE_OPEN];
            $rt['high'] = $data[self::POS_PRICE_TOP];
            $rt['low'] = $data[self::POS_PRICE_BOTTOM];
            $rt['changepercent'] = floatval($data[self::POS_PRICE_RATE]);
            $rt['vol'] = $data[self::POS_VOL];
            $rt['turnoverratio'] = $data[self::POS_VOL_RATE];
            $rt['pe'] = $data[self::POS_PE];
            $rt['mktcap'] = $data[self::POS_TV];
            $rt['nmc'] = $data[self::POS_CV];
            $rt['over_buy'] = $data[self::POS_SELL_1] < 0.01 ? $data[self::POS_BUY_1_VOL] : 0;
            //$rt['limit_down_vol'] = $data[self::POS_BUY_1] < 0.01 ? $data[self::POS_SELL_1_VOL] : 0;
            $rt['symbol'] = $prefix . $data[2];
            //$rt['fetch_time']=date('Y-m-d H:i:s');
        }
        return $rt;
    }


    private static function parseRawLine($line)
    {
        $rt = [];
        $pattern = '/v_(sh|sz)(\d{6})="([^"]+)/';
        preg_match_all($pattern, $line, $out);
        if (isset($out[3][0])) {
            $rt = explode('~', $out[3][0]);
            $rt[] = $out[1][0];
        }
        return $rt;

    }
}
