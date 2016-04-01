<?php
namespace Home\Controller;

use Think\Controller;

class SMCacheController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'cloud';
    }

    //显示列表
    public function index()
    {

        $result = array();
        if (!empty($_POST)) {

            //清除Redis
            $resultRedis = $this->clearRedis();

            //清除APC
            $apcKey = I('post.key') ? I('post.key') : null;
            $resultApc = $this->clearApc($apcKey);

            //结果
            $result = $resultApc + $resultRedis;
        }

//        dump($result);
        $this->result = $result;
        $this->display();//显示页面

    }

}