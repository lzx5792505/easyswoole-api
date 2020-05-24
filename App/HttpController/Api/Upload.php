<?php

namespace App\HttpController\Api;

use App\Lib\ClassStat;

class Upload extends Base
{

    public function uploadFiles()
    {
        $request = $this->request();

        $type = array_keys($request->getSwooleRequest()->files)[0];

        if (empty($type)) {
            return $this->writeJson(400, '上传文件不合法');
        }

        try {
            $stat = new ClassStat();

            $classStats = $stat->uploadClassStat();

            $uploadFile = $stat->initClass($type, $classStats, [$request, $type]);

            $file = $uploadFile->upload();
        } catch (\Exception $e) {
            return $this->writeJson(400, $e->getMessage(), []);
        }

        if (empty($file)) {
            return $this->writeJson(400, "上传失败", []);
        }

        $data = [
            'url' => $file,
        ];

        return $this->writeJson(200, $data, "OK");
    }
}
