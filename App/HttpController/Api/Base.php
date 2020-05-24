<?php

namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class Base extends Controller
{
    public $redis;

    /**
     * -------------------------------------------------------
     * 权限相关
     * -------------------------------------------------------
     * @param  string
     * @return boole
     */
    public function onRequest($action): ?bool
    {
        $this->getParams();

        return true;
    }

    /**
     * -------------------------------------------------------
     * 获取 params
     * -------------------------------------------------------
     * @return array
     */
    public function getParams()
    {
        $params = $this->request()->getRequestParam();

        $params['page'] = !empty($params['page']) ? intval($params['page']) : \Yaconf::get('page.page');

        $params['size'] = !empty($params['size']) ? intval($params['size']) : \Yaconf::get('page.size');

        $params['from'] = ($params['page'] - 1) * $params['size'];

        $this->params = $params;
    }

    /**
     * json数据格式输出
     *@statusCode
     */
    public function writeJson($statusCode = 200, $message = null, $result = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = array(
                "code" => $statusCode,
                "message" => $message,
                "result" => $result
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
            return true;
        } else {
            trigger_error("response has end");
            return false;
        }
    }
}
