<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 14:50
 */

namespace EasySwoole\DDL\Filter\Limit;


use EasySwoole\DDL\Blueprint\Column;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterVarchar implements FilterInterface
{
    public static function run(Column $column)
    {
        if ($column->getColumnLimit() < 0 || $column->getColumnLimit() > 65535) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type varchar(limit), limit must be range 1 to 255');
        }
    }
}