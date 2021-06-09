<?php



return [
    // 每日初始化,检查当天是否有快照
    [
        'cmd' => [
            'class' => \App\Services\Cron\Commands\SnapInitCommand::class,
            'method' => 'handle',// default handle
        ],
        'ons' => [
            [
                'begin' => '09:31',
                'end' => '09:51',
                'interval_minute' => 10, //  间隔分钟 0  是当天执行一次
                'days' => [1, 2, 3, 4, 5], // 星期
                // and more ...
            ],
        ],

    ],
    // 初始化收盘
    [
        'cmd' => [
            'class' => \App\Services\Cron\Commands\SnapSyncClosedCommand::class,
            'method' => 'handle',// default handle
        ],
        'ons' => [
            [
                'begin' => '16:30',
                'end' => '17:40',
                'interval_minute' => 10, //  间隔分钟 0  是当天执行一次
                'days' => [1, 2, 3, 4, 5], // 星期
            ],
        ],

    ],
    // 初始化超买涨停
    [
        'cmd' => [
            'class' => \App\Services\Cron\Commands\OverbuyInitCommand::class,
            'method' => 'handle',// default handle
        ],
        'ons' => [
            [
                'begin' => '16:35',
                'end' => '16:40',
                'interval_minute' => 10, //  间隔分钟 0  是当天执行一次
                'days' => [1, 2, 3, 4, 5], // 星期
            ],
        ],

    ],

];
