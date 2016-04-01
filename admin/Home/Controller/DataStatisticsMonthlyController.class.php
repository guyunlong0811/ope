<?php
namespace Home\Controller;

use Think\Controller;

class DataStatisticsMonthlyController extends BInitController
{

    const FIRST_YEAR = 2015;

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->selectChannel();

        $this->vTitle = 'data_statistics_monthly';
        $this->vIcon = 'bar-chart-o';
    }

    public function index()
    {
        $year = date('Y');
        $this->vYears = range(self::FIRST_YEAR, $year);
        $this->vMonths = range(1, 12);


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

            //年月
            if (I('get.year') > '0') {
                if (I('get.month') > '0') {
                    $month = I('get.year') . '-' . I('get.month');
                    $where['month'] = date('Y-m', strtotime($month));
                }else{
                    $where['month'] = array('like', I('get.year') . '-%');
                }
            }

            //获取数据
            $select = D('DataStatisticsMonthly')->getAll($where);
            foreach($select as $key => $value){
                $select[$key]['channel'] = $value['channel_id'] . '-' . $this->vChannel[$value['channel_id']];
                $select[$key]['pay_amount'] = sprintf("%01.2f", $value['pay_amount'] / 100);
                $select[$key]['arpu'] = sprintf("%01.1f", $value['pay_amount'] / $value['activity_count'] / 100);
                $select[$key]['arppu'] = sprintf("%01.1f", $value['pay_amount'] / $value['pay_member'] / 100);
                $select[$key]['pay_rate'] = sprintf("%01.1f", $value['pay_member'] / $value['activity_count'] * 100) . '%';

            }

            $this->vTable = $select;
        }

        //显示页面
        $this->display();

    }

}