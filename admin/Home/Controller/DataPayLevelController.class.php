<?php
namespace Home\Controller;

use Think\Controller;

class DataPayLevelController extends BInitController
{

    private $mMaxLevel = 99;

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDateStartEnd();
        $this->selectChannel();
        $this->drawChart();

        $this->vTitle = 'data_pay_level';
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
            $where['date_time'] = array('between', array(I('get.starttime'), I('get.endtime'),));
            $select = D('DataPayLevel')->where($where)->select();

            //创建数据
            $data = array();
            for ($i = 1; $i <= $this->mMaxLevel; ++$i) {
                $data[$i]['level'] = $i;
                $data[$i]['amount'] = 0;
                $data[$i]['member'] = 0;
                $data[$i]['count'] = 0;
                $data[$i]['diamond_pay_produce'] = 0;
                $data[$i]['diamond_pay_recover'] = 0;
                $data[$i]['diamond_free_produce'] = 0;
                $data[$i]['diamond_free_recover'] = 0;
                $data[$i]['gold_produce'] = 0;
                $data[$i]['gold_recover'] = 0;
            }

            //处理数据
            foreach ($select as $value) {
                $data[$value['level']]['amount'] += $value['amount'] / 100;
                $data[$value['level']]['member'] += $value['member'];
                $data[$value['level']]['count'] += $value['count'];
                $data[$value['level']]['diamond_pay_produce'] += $value['diamond_pay_produce'];
                $data[$value['level']]['diamond_pay_recover'] += $value['diamond_pay_recover'];
                $data[$value['level']]['diamond_free_produce'] += $value['diamond_free_produce'];
                $data[$value['level']]['diamond_free_recover'] += $value['diamond_free_recover'];
                $data[$value['level']]['gold_produce'] += $value['gold_produce'];
                $data[$value['level']]['gold_recover'] += $value['gold_recover'];
            }

            //图表
            foreach ($data as $level => $value) {
                $single[] = array((int)$level, (int)$value['amount'] / 100);

            }

            $this->vTable = $data;
            $this->vChartData = json_encode($single);
            $this->vChartLabel = L('pay_amount');
            $this->vChartXTicks = round($this->mMaxLevel / 5);

        }

        //显示页面
        $this->display();

    }

}