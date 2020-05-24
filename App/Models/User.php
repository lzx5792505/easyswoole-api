<?php

namespace  App\Models;

use EasySwoole\ORM\Utility\Schema\Table;

class User extends Model
{
    /**
     * 表的获取
     * 此处需要返回一个 EasySwoole\ORM\Utility\Schema\Table
     * @return Table
     */
    public    $tableName = "users";
    protected $connectionName = "default";
    protected $autoTimeStamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
