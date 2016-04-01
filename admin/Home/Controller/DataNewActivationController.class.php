<?php
namespace Home\Controller;

use Think\Controller;

class DataNewActivationController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDateStartEnd();
        $this->selectChannel();

        $this->vTitle = 'data_new_activation';
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
            //渠道
            if (I('get.channel_id') != '0') {
                $where['channel_id'] = I('get.channel_id');
            }
            //日期
            $where['date_time'] = array('between', array(I('get.starttime'), I('get.endtime'),));

            //开始时间
            $select = D('DataStatisticsMid')->getNewUser($where);
            $day = (strtotime(I('get.endtime')) - strtotime(I('get.starttime'))) / 86400;
            for ($i = 0; $i <= $day; ++$i) {
                $ts = strtotime(I('get.starttime')) + ($i * 86400);
                $date = time2format($ts, 2);
                $count = isset($select[$date]) ? $select[$date] : 0;
                $tick[] = array($i, date("m-d", $ts));
                $data[] = array($i, $count);
            }
            $this->vChartXTicks = json_encode($tick);
            $this->vChartLabel = count($data);
            $this->vChartData = json_encode($data);

        }

        //显示页面
        $this->display();

    }

}