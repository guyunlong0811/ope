<?php
namespace Home\Controller;

use Think\Controller;

class DataActiveUsersController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'bar-chart-o';
    }

    //
    public function index()
    {

        if (!empty($_GET)) {

            //开始时间
            $countNums = M('DataActiveUsers')->field('date_time,count')->where("`sid`='" . I('get.server_id') . "'")->order('`date_time` DESC')->limit(I('get.day'))->select();

            foreach ($countNums as $aCountNum) {
                $ts = strtotime($aCountNum['date_time']);
                $date = date("m-d", $ts);
                $a_tick_label = array($ts, $date);
                $a_active_user = array($ts, $aCountNum['count']);
                $tick_label[] = $a_tick_label;
                $active_users[] = $a_active_user;
            }

            $this->ticklable = json_encode($tick_label);
            $this->activeUsersData = json_encode($active_users);

        }

        //显示页面
        $this->display();

    }

}
