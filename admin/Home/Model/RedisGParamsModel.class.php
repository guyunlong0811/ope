<?php
namespace Home\Model;

use Think\Model;

class RedisGParamsModel
{

    public function getValue($key)
    {
        $key = strtoupper($key);
        $value = D('Predis')->cli('game')->hget('g_params', $key);
        return $value;
    }

}