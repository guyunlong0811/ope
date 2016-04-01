<?php
namespace Home\Controller;

use Think\Controller;

class DataPayRankDailyController extends BInitController
{

    private $mLimit = 30;//获取排名

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate();
        $this->selectChannel();

        $this->vTitle = 'data_pay_rank_daily';
        $this->vIcon = 'bar-chart-o';

    }

    public function index()
    {

        if (!empty($_GET)) {
            //服务器
            if (I('get.server_id') != '0') {
                $where['sid'] = I('get.server_id');
            }
            //渠道
            if (I('get.channel_id') != '0') {
                $where['channel_id'] = I('get.channel_id');
            }
            //日期
            $where['date_time'] = I('get.date');

            //查询渠道信息
            $channelList = D('UCChannel')->getAll();

            //查询信息
            $select = D('DataPayRank')->field("`sid`,`channel_id`,`tid`,`nickname`,`level`,`pay`,`count`")->where($where)->limit($this->mLimit)->select();
            if (!empty($select)) {
                foreach ($select as $key => $value) {
                    $select[$key]['server'] = $this->getServerName($value['sid']);
                    $select[$key]['channel'] = $channelList[$value['channel_id']];
                    $select[$key]['pay'] = $value['pay'] / 100;
                }
            }
            $this->vTable = $select;

        }

        //显示页面
        $this->display();

    }

}