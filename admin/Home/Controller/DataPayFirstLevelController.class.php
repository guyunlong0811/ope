<?php
namespace Home\Controller;

use Think\Controller;

class DataPayFirstLevelController extends BInitController
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

        $this->vTitle = 'data_pay_first_level';
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
            $select = D('DataPayFirstLevel')->field('`level`,sum(`count`) as `count`')->where($where)->group('level')->select();
            if (!empty($select)) {
                foreach ($select as $value) {
                    $selectData[$value['level']] = $value['count'];
                }
            }

            //创建数据
            $data = array();
            for ($i = 1; $i <= $this->mMaxLevel; ++$i) {
                $data[$i]['level'] = $i;
                $data[$i]['count'] = isset($selectData[$i]) ? $selectData[$i] : 0;
            }

            //图表
            foreach ($data as $level => $value) {
                $single[] = array((int)$level, (int)$value['count']);
            }

            $this->vTable = $data;
            $this->vChartData = json_encode($single);
            $this->vChartLabel = L('pay_first_count');
            $this->vChartXTicks = round($this->mMaxLevel / 5);

        }

        //显示页面
        $this->display();

    }

}