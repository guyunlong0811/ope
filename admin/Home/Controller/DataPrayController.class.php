<?php
namespace Home\Controller;

use Think\Controller;

class DataPrayController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate();
        $this->selectChannel();
        $this->drawChart();

        $this->vTitle = 'data_pray';
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

            //商品信息
            $prayConfig = D('Static')->access('pray');

            //查询信息
            $select = D('DataPrayMid')->field("`pray_id`,`is_free`,sum(`count`) as `count`")->where($where)->group('`pray_id`,`is_free`')->order('`pray_id` ASC, `is_free` DESC')->select();

            if (!empty($select)) {
                foreach ($select as $key => $value) {
                    $select[$key]['pray_name'] = $prayConfig[$value['pray_id']]['des'];
                    $select[$key]['is_free'] = $value['is_free'] == 1 ? L('yes') : L('no');
                }
            }
            $this->vTable = $select;

        }

        //显示页面
        $this->display();

    }

}