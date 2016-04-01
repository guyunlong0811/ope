<?php
namespace Home\Controller;

use Think\Controller;

class DataPayFirstCashController extends BInitController
{

    private $mMaxLevel = 99;

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate();
        $this->selectChannel();

        $this->vTitle = 'data_pay_first_cash';
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
            $cashConfig = D('Static')->access('cash');
//            dump($cashConfig);

            //查询信息
            $select = D('DataPayFirstCash')->field("`cash_id`,sum(`count`) as `count`")->where($where)->group('cash_id')->order('`cash_id` ASC')->select();

            if (!empty($select)) {
                foreach ($select as $key => $value) {
                    $select[$key]['cash_name'] = $cashConfig[$value['cash_id']]['name'];
                    $select[$key]['cash_des'] = $cashConfig[$value['cash_id']]['des'];
                    $select[$key]['cash_price'] = $cashConfig[$value['cash_id']]['price'] / 100;
                }
            }
            $this->vTable = $select;

        }

        //显示页面
        $this->display();

    }

}