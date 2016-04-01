<?php
namespace Home\Controller;

use Think\Controller;

class DataRechargeDistributionController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        //加载模块
        $this->drawChart();
        $this->selectDateStartEnd();
        $this->selectChannel();

        $this->vTitle = 'data_recharge_distribution';
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
            $select = D('DataStatisticsMid')->getPay($where);
            for ($i = 0; $i < count($select); ++$i) {
                $tick[] = array($i, date("m-d", strtotime($select[$i]['date_time'])));
                $data[] = array($i, $select[$i]['pay_amount'] / 100);
                $select[$i]['pay_amount'] = $select[$i]['pay_amount'] / 100;
            }

            $this->vChartLabel = L('pay_amount');
            $this->vChartXTicks = json_encode($tick);
            $this->vChartData = json_encode($data);
            $this->vTable = $select;
        }

        //显示页面
        $this->display();

    }

}