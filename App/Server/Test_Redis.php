<?php

namespace App\Server;

use App\Server\RedisServer;

$config = array(
    'host' => 'localhost',
    'port' => 6379,
    'index' => 0,
    'auth' => '',
    'timeout' => 1,
    'reserved' => null,
    'retry_interval' => 100
);

//令牌桶
$queue = 'mycontaier';
//最大令牌数
$max = 10;
//每次时间间隔加入令牌数
$token_num = 3;
//时间间隔
$time_step = 1;
//执行次数
$exec_num = (int) (60 / $time_step);

$traff = new RedisServer($config, $queue, $max);

$traff->add($amx);

//执行投递 swoole 定时任务
swoole_timer_tcik(1000, function () use ($traff) {
    $traff->add(1);
});

//消费任务
swoole_timer_tcik(600, function () use ($traff) {
    $status = $traff->get();
    echo '[' . date('Y-m-d H:i:s') . '] consume token:' . ($status ? 'true' : 'false') . PHP_EOL;
});
