<?php
namespace Home\Controller;

use Think\Controller;

class DataLostStatisticsController extends BInitController
{

    private $mMaxLevel = 99;

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate();
        $this->selectChannel();
        $this->drawChart();

        $this->vTitle = 'data_lost_statistics';
        $this->vIcon = 'bar-chart-o';
        $this->mMaxLevel = D('Static')->access('params', 'TEAM_LEVEL_MAX');

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
            $where['date_time'] = I('get.date');
            $select = D('DataTeamLevel')->where($where)->select();

            //创建数据
            $data = array();
            for ($i = 1; $i <= $this->mMaxLevel; ++$i) {
                $data[$i]['level'] = $i;
                $data[$i]['count'] = 0;
                $data[$i]['lost'] = 0;
            }

            //处理数据
            $sumAll = 0;
            $sumLost = 0;
            foreach ($select as $value) {
                $data[$value['level']]['count'] += $value['count'];
                $data[$value['level']]['lost'] += $value['lost'];
                $sumAll += $value['count'];
                $sumLost += $value['lost'];
            }

            //计算比率
            foreach ($data as $level => $value) {
                $data[$level]['level_rate'] = sprintf("%01.2f", ($value['count'] / $sumAll) * 100) . '%';
                $lostRate = sprintf("%01.2f", ($value['lost'] / $value['count']) * 100);
                $data[$level]['lost_rate'] = $lostRate . '%';
                $single[] = array($level, $lostRate * 100);
            }

            //总数据
            $total['count'] = $sumAll;
            $total['lost'] = $sumLost;
            $total['level_rate'] = '100%';
            $total['lost_rate'] = sprintf("%01.2f", ($total['lost'] / $total['count']) * 100) . '%';

            $this->vTable = $data;
            $this->vTotal = $total;

            //图表
            for ($i = 0; $i <= 10; ++$i) {
                $num1 = $i * 1000;
                $num2 = $i * 10;
                $yTick[] = array($num1, $num2 . '%');
            }

            $this->vChartData = json_encode($single);
            $this->vChartLabel = L('lost_rate');
            $this->vChartYTicks = json_encode($yTick);
            $this->vCharthover = "y / 100 + '%'";

        }

        //显示页面
        $this->display();

    }

}