<?php

namespace EasySwoole\EasySwoole;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Http\Message\Status;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        $config =  new \EasySwoole\Pool\Config();
        // MySQL连接池
        \EasySwoole\Pool\Manager::getInstance()->register(
            new \App\Pool\MysqlPool($config),
            \App\Pool\MysqlObject::TYPE
        );
        // redis连接池
        \EasySwoole\Pool\Manager::getInstance()->register(
            new \App\Pool\RedisPool($config, new \EasySwoole\Redis\Config\RedisConfig(\Yaconf::get("redis"))),
            'redis'
        );
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        /**
         * 设置跨域
         */
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        if ($request->getMethod() === 'OPTIONS') {
            $response->withStatus(Status::CODE_OK);
            return false;
        }

        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}
