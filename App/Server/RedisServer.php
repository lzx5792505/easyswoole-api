<?php

namespace App\Server;

use App\Models\Model;

class RedisServer extends BaseServer
{
    private $redis;   //redis对象
    private $queue;   //令牌插
    private $max;     //最大令牌

    public function __construct($queue, $max)
    {
        $this->queue  = $queue;
        $this->max    = $max;
        $this->redis  = (new Model())->redis;
    }

    /**
     * -------------------------------------------------------
     * 添加令牌
     * -------------------------------------------------------
     * @return void
     */
    public  function add($num = 0)
    {
        //当前剩余令牌数
        $curnum = $this->redis->lLen($this->queue);
        //最大令牌
        $maxnum = intval($this->max);
        //添加令牌
        $num = $maxnum >= $curnum + $num ? $num : $maxnum - $curnum;
        //加入令牌
        if ($num > 0) {
            $token = array_fill(0, $num, 1);
            foreach ($token as $val) {
                $this->redis->lPush($this->queue, $val);
            }
            return $num;
        }
        return 0;
    }

    /**
     * -------------------------------------------------------
     * 获取令牌
     * -------------------------------------------------------
     * @return void
     */
    public function get()
    {
        return $this->redis->rPop($this->queue) ? true : false;
    }

    /**
     * -------------------------------------------------------
     * 重置令牌
     * -------------------------------------------------------
     * @return void
     */
    public function reset()
    {
        $this->redis->unlink($this->queue);
    }
}
