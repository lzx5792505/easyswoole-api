<?php

namespace App\HttpController\Api;

use App\Models\User;
use App\Server\RedisServer;
use EasySwoole\Http\Message\Status;

class Index extends Base
{

    public function index()
    {
        $user = new User();

        try {
            $reslst = $user->getById(1);
            if (!$user->redis->get('userInfo' . $reslst[0]['id'])) {
                $user->redis->set('userInfo' . $reslst[0]['id'], $reslst[0]['phone']);
                $user->redis->expire('userInfo' . $reslst[0]['id'], 400);
            }
            $res = $user->redis->get('userInfo' . $reslst[0]['id']);
            return $this->writeJson(201, 'ok', $res);
        } catch (\Throwable $e) {
            throw new \Exception('请求失败！');
        }
    }

    public function test()
    {
        $redis = new RedisServer('222', 10);
        $a = $redis->add(4);
        return $this->writeJson(201, 'ok', $a);
        // $user->redis = (new User())->redis;
        // return $this->writeJson(201, 'ok', $user->redis->get('userInfo1'));
    }
}
