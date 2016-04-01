<?php
namespace Home\Controller;

use Think\Controller;

class DataCurrencyController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate();

        $this->vTitle = 'data_currency';
        $this->vIcon = 'bar-chart-o';
        $this->vType = array(1, 2, 3, 4, 5, 6,);
        $config = C('BEHAVE');
        foreach ($config as $value) {
            $behave[$value['code']] = $value['message'];
        }
        $this->vBehave = $behave;

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
            //日期
            $where['date_time'] = I('get.date');
            //类型
            $where['type'] = I('get.type');

            $select = D('DataCurrency')->field('`behave`,`count`')->where($where)->select();
            $sum = 0;
            foreach ($select as $key => $value) {
                $sum += $value['count'];
            }

            $this->vTable = $select;
            $this->vSum = $sum;

        }

        //显示页面
        $this->display();

    }

}