<?php
namespace Home\Controller;

use Think\Controller;

class DataConsumeController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'bar-chart-o';
    }

    public function index()
    {

        if (!empty($_GET)) {

            //消费数据
            $consumeList = M('DataConsume')->field('date_time,pay_user,pay')->where("`sid`='" . I('get.server_id') . "'")->order('`date_time` DESC')->limit(I('get.day'))->select();

            //活跃用户数据
            $select = M('DataActiveUsers')->field('date_time,count')->where("`sid`='" . I('get.server_id') . "'")->order('`date_time` DESC')->limit(I('get.day'))->select();
            foreach ($select as $value) {
                $activeList[$value['date_time']] = $value['count'];
            }

            //计算值
            foreach ($consumeList as $value) {

                //表格部分
                $tableArr = array();
                $tableArr['date_time'] = $value['date_time'];
                $tableArr['pu'] = $value['pay_user'];
                $tableArr['pr'] = sprintf("%01.2f", ($value['pay_user'] / $activeList[$value['date_time']]) * 100) . '%';
                $tableArr['arpu'] = sprintf("%01.2f", ($value['pay'] / $activeList[$value['date_time']] / 100));
                $tableArr['arppu'] = sprintf("%01.2f", ($value['pay'] / $value['pay_user'] / 100));
                $table[] = $tableArr;

                //折线图部分
                $ts = strtotime($value['date_time']);
                $date = date("m-d", $ts);
                $tick_label[] = array($ts, $date);
                $jsonPU[] = array($ts, $tableArr['pu']);
                $jsonPR[] = array($ts, $tableArr['pr']);
                $jsonARPU[] = array($ts, $tableArr['arpu']);
                $jsonARPPU[] = array($ts, $tableArr['arppu']);

            }

//            $chart[] = array('label' => 'PU','data' => json_encode($jsonPU));
//            $chart[] = array('label' => 'PR','data' => json_encode($jsonPR));
            $chart[] = array('label' => 'ARPU', 'data' => json_encode($jsonARPU));
            $chart[] = array('label' => 'ARPPU', 'data' => json_encode($jsonARPPU));

            $this->table = $table;
            $this->chart = $chart;
            $this->ticklable = json_encode($tick_label);
        }

        //显示页面
        $this->display();

    }

}