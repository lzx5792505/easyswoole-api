<?php

namespace  App\Models;;

use App\Pool\MysqlObject;
use EasySwoole\Pool\Manager;
use EasySwoole\ORM\AbstractModel;

class Model extends AbstractModel
{
    public $db;
    public $redis;

    /**
     * ---------------------------------------------------------------
     * 获取mysql连接池
     * ---------------------------------------------------------------
     */
    public function __construct()
    {
        //mysql连接池
        $mysqlObject = Manager::getInstance()->get(\Yaconf::get('pool.type'))->getObj(\Yaconf::get('mysql.POOL_TIME_OUT'));
        if ($mysqlObject instanceof MysqlObject) {
            $this->db = $mysqlObject;
        } else {
            throw new \Exception('获取mysql连接失败');
        }
        //redis连接池
        $this->redis = Manager::getInstance()->get('redis')->getObj();
        if (empty($this->redis)) {
            throw new \Exception('获取redis连接失败');
        }
    }

    /**
     * ---------------------------------------------------------------
     * 通过ID 获取 基本信息
     * ---------------------------------------------------------------
     * @param [type] $id
     * 
     * @return void
     */
    public function getById(int $id)
    {
        if (empty($id)) {
            return [];
        }
        $this->db->queryBuilder()->where('id', $id)->getOne($this->tableName);
        $result = $this->db->execBuilder();
        return $result ?? [];
    }

    /**
     * ---------------------------------------------------------------
     * 释放连接池
     * ---------------------------------------------------------------
     * @return void
     */
    public function __destruct()
    {
        if ($this->db instanceof MysqlObject) {
            Manager::getInstance()->get(\Yaconf::get('pool.type'))->recycleObj($this->db);
            $this->db = null;
        }
        Manager::getInstance()->get('redis')->recycleObj($this->redis);
        $this->redis = null;
    }
}
