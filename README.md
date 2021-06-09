# 股票推荐定时任务代码说明

1. 需要和风格
    1. 数据库和代码风格按laravel习惯
    2. 没有任何性能:用户访问定时任务算法结果,没有任何性能压力
2. 展示重点拆分
    1. 按目录拆分
    2. 类拆分足够小,非Service结尾都是拆分出来
    3. 拆分函数小

## 1 分类说明

### 1.1 目录app/Services/Chgn 热门概念

概念详情爬虫服务ChgnDetailSyncService.php,拆出爬虫任务ChgnDetailTask.php

### 1.2 目录app/Services/Cron 定时任务,比crontab多了管理

拆分未状态管理,运行服务,命令.扩展新任务则再Commands目录里面加文件

### 1.3 目录app/Services/Snap 股票快照

1. 拆分出同步SnapSyncService.php服务,异步抓取和削峰处理
2. 拆分出交易日处理,TradeDayService.php,里面方法用同一个缓存减少bug

### 1.4 app/Services/WebClient

TxSnapService.php爬虫 由抓取TxSnapFetcher.php和解析TxSnapParser.php组合而成

## 2 总结

1. 拆分是**我代码bug极少和易理解原因**,个人代码只有分成小目标才是最有用的.  
   很多技术问题和进度问题都来自不清晰  
2. 通常大多数人实现了这样定时任务是一堆很难理解代码,对后续工作是极大浪费
