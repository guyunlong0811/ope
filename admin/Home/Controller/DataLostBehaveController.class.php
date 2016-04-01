<?php
namespace Home\Controller;

use Think\Controller;

class DataLostBehaveController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->selectDate();

        $this->vTitle = 'data_lost_behave';
        $this->vIcon = 'bar-chart-o';

    }

    public function index()
    {

        if (!empty($_GET)) {
            //服务器
            if (I('get.server_id') != '0') {
                $where['sid'] = I('get.server_id');
            } else {
                $where['sid'] = array('in', $this->mServerPermissionList);
            }
            //日期
            $where['date_time'] = I('get.date');

            $select = D('DataLostBehave')->where($where)->order('`last_login_time` ASC')->select();
            foreach ($select as $key => $value) {
                $select[$key]['server_name'] = $this->getServerName($value['sid']);
                $select[$key]['instance'] = substr($value['instance'], 1, 2) . '-' . substr($value['instance'], 3, 2);
                $select[$key]['create_time'] = time2format($value['create_time']);
                $select[$key]['last_login_time'] = time2format($value['last_login_time']);
                $select[$key]['vality_utime'] = time2format($value['vality_utime']);
            }
            $this->vTable = $select;

        }

        //显示页面
        $this->display();

    }

}