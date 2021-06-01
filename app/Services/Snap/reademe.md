# 1从腾讯抓取
## TxApiService.php 腾讯股票快照
组合了 抓取 TxWebFetchService.php  和 解析 TxWebParserService.php
1. TxApiService::lastTime 读取股票快照最后时间,用于判断当天是否有交易
2. TxApiService::get 
## SnapSyncService  同步快照 同步到本地
1. SnapSyncService::runNew 检查代码表,有新添加,每日运行一次
1. SnapSyncService::update 根据代码跟新


## 2 定时
    

