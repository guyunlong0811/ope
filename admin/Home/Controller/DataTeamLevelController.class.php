<?php
namespace Home\Controller;

use Think\Controller;

class DataTeamLevelController extends BInitController
{

    private $mMaxLevel;

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDateStartEnd();
        $this->selectChannel();

        $this->vTitle = 'data_team_level';
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

            $select = D('DataTeamLevel')->getAll($where);
            $data = array();
            foreach ($select as $key => $value) {
                $thList[] = $key;
                $single = array();
                for ($i = 1; $i <= $this->mMaxLevel; ++$i) {
                    $count = isset($value[$i]) ? $value[$i] : 0;
                    $single[] = array((int)$i, (int)$count);
                    $data[$i][] = $count;
                }

                $chart['data'] = $single;
                $chart['label'] = $key;
                $chartList[] = $chart;
            }
//            dump($chartList);
            $this->vChartList = json_encode($chartList);
            $this->vChartXTicks = round($this->mMaxLevel / 5);
            $this->vTable = $data;
            $this->vThList = $thList;

        }

        //显示页面
        $this->display();

    }

}