<?php

namespace App\Pool;

use EasySwoole\Pool\Config;
use EasySwoole\Pool\AbstractPool;
use EasySwoole\Pool\Exception\Exception;

class MysqlPool extends AbstractPool
{
    public function __construct(Config $conf)
    {
        try {
            parent::__construct($conf);
        } catch (Exception $e) {
            throw new \Exception('配置错误~！');
        }
    }

    // 创建对象
    protected function createObject()
    {
        return new MysqlObject();
    }
}
