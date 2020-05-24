<?php

namespace App\Pool;

use EasySwoole\Pool\Manager;
use EasySwoole\Mysqli\Client;
use EasySwoole\Pool\ObjectInterface;

class MysqlObject extends  Client implements ObjectInterface
{
    const TYPE = "mysql";

    public function __construct()
    {
        $mysql = \Yaconf::get("mysql"); // 获取ini中的配置
        parent::__construct(new \EasySwoole\Mysqli\Config($mysql));
    }

    // 被连接池 unset 的时候执行
    public function gc()
    {
        // TODO: Implement gc() method.
        $this->close();
    }

    // 被连接池 回收的时候执行
    public function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }

    // 取出连接池的时候被调用，若返回false，则当前对象被弃用回收
    public function beforeUse(): ?bool
    {
        // TODO: Implement beforeUse() method.
        return true;
    }

    /**
     * 用于获取连接
     */
    public static function borrowPool(): ?MysqlObject
    {
        try {
            return Manager::getInstance()->get(self::TYPE)->getObj(500, 6);
        } catch (\Throwable $e) {
            \EasySwoole\EasySwoole\Trigger::getInstance()->error("获取连接失败");
        }
    }

    /**
     * 用于归还连接
     */
    public static function returnPool(?MysqlObject $client)
    {
        try {
            Manager::getInstance()->get(self::TYPE)->recycleObj($client);
        } catch (\Throwable $e) {
            \EasySwoole\EasySwoole\Trigger::getInstance()->error("归还连接失败");
        }
    }
}
