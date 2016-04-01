<?php
namespace Home\Controller;

use Think\Controller;

class RunController extends BaseController
{

    public function index()
    {

        $select1 = M('LRunCopy')->field("`api`,count(1) as `count`")->group('api')->select();
        foreach ($select1 as $value) {
            $all[$value['api']]['count'] = $value['count'];
        }

        $where = "`second` - `curl_second` > 3";
        $select2 = M('LRunCopy')->field("`api`,count(1) as `count`")->where($where)->group('api')->select();
        foreach ($select2 as $value) {
            $over[$value['api']]['all'] = $all[$value['api']]['count'];
            $over[$value['api']]['count'] = $value['count'];
            $over[$value['api']]['rate'] = sprintf("%01.2f", ($value['count'] / $all[$value['api']]['count']) * 100) . '%';
        }

        foreach ($select2 as $value) {
            unset($all[$value['api']]);
        }


        dump($over);
//        dump($all);

    }

}