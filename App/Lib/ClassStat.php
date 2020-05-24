<?php

namespace App\Lib;

class ClassStat
{
    public function uploadClassStat()
    {
        return [
            "image" => "\App\Lib\Upload\Image",
            "video" => "\App\Lib\Upload\Video",
        ];
    }

    public function initClass($type, $supportedClass, $params = [], $needInstance = true)
    {
        if (!array_key_exists($type, $supportedClass)) {
            return false;
        }

        $className = $supportedClass[$type];

        return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }
}
