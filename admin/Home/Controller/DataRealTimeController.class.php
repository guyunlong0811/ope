<?php
namespace Home\Controller;

use Think\Controller;

class DataRealTimeController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        //加载模块
//        $this->selectChannel();

        $this->vTitle = 'data_real_time';
        $this->vIcon = 'bar-chart-o';
    }

    public function index()
    {
        if (!empty($_GET)) {
            //渠道
//            if(I('get.channel_id') != '0'){
//                $where['channel_id') = I('get.channel_id');
//            }

            $cashConfig = D('Static')->access('cash');

            //查询今日数据
            $dbConfig = change_db_server(I('get.server_id'), 'master');

            //数据库
            $model = M()->db(I('get.server_id'), $dbConfig);

            //时间
            $start = strtotime(date('Y-m-d'));
            $end = $start + 86399;
            $whereTime = "between '{$start}' and '{$end}'";

            //查询今日注册玩家
            $table['nu'] = $model->db(I('get.server_id'))->table('g_team')->where("`ctime` {$whereTime}")->count();

            //查询今日活跃用户
            $table['au'] = $model->db(I('get.server_id'))->table('g_team')->where("`last_login_time` {$whereTime}")->count();

            //查询今日充值用户
            $select = M()->db(I('get.server_id'), $dbConfig)->table('l_order')->field("sum(`price`) as `amount`,count(`id`) as `count`,count(distinct(`tid`)) as `member`")->where("`status`='1' && `endtime` {$whereTime}")->select();
            foreach ($select as $value) {
                $table['pa'] = $value['amount'] ? $value['amount'] / 100 : 0;
                $table['pc'] = $value['count'] ? $value['count'] : 0;
                $table['pu'] = $value['member'] ? $value['member'] : 0;
            }


            $table['pr'] = sprintf("%01.2f", ($table['pu'] / $table['au']) * 100) . '%';
            $table['arpu'] = sprintf("%01.1f", ($table['pa'] / $table['au']));
            $table['arppu'] = sprintf("%01.1f", ($table['pa'] / $table['pu']));

            //查询当日充值档位情况
            $table['cash_today'] = $model->db(I('get.server_id'))->table('l_order')->field("`cash_id`, count(`id`) as `count`")->where("`endtime` {$whereTime} && `status`='1'")->group("`cash_id`")->select();
            if(!empty($table['cash_today']))
            foreach($table['cash_today'] as $key => $value){
                $table['cash_today'][$key]['cash'] = $cashConfig[$value['cash_id']]['name'];
            }else{
                $table['cash_today'] = array();
            }

            //查询总充值档位情况
            $table['cash_total'] = $model->db(I('get.server_id'))->table('g_pay')->field("`cash_id`, sum(`count`) as `count`")->group("`cash_id`")->select();
            foreach($table['cash_total'] as $key => $value){
                $table['cash_total'][$key]['cash'] = $cashConfig[$value['cash_id']]['name'];
            }

            //返回数据
            $this->vTable = $table;

        }

        //显示页面
        $this->display();

    }

}