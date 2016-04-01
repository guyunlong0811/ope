<?php
namespace Home\Controller;

use Think\Controller;

class DataStatisticsAsiaController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->selectDateStartEnd();
        $this->selectChannel();

        $this->vTitle = 'data_statistics_index';
        $this->vIcon = 'bar-chart-o';
    }

    public function index()
    {

        if (!empty($_GET)) {

            //服务器
            if ($_GET['server_id'] != '0') {
                $where['sid'] = $_GET['server_id'];
            } else {
                $where['sid'] = array('in', $this->mServerPermissionList);
            }
            //服务器
            if ($_GET['channel_id'] != '0') {
                $where['channel_id'] = $_GET['channel_id'];
            }
            //日期
            $where['date_time'] = array('between', array($_GET['starttime'], $_GET['endtime'],));

            //获取数据
            $table = D('DataStatisticsMid')->getAll($where);

            //获取渠道信息
            $channelList = D('UCChannel')->getAll();

            //处理数据
            $all['create_count'] = 0;
            foreach ($this->mRetentionDay as $day) {
                $all['day' . $day] = 0;
            }

            foreach ($table as $key => $value) {

                $table[$key]['channel_name'] = $channelList[$value['channel_id']];
                $payAmount = $value['pay_amount'] / 100;
                $table[$key]['dau'] = $value['login_day1_member'];
                $table[$key]['ndau'] = $value['create_count'];
                $table[$key]['pndau'] = $value['first_pay_member'];
                $table[$key]['odau'] = $value['login_day1_member'] - $value['create_count'];
                $table[$key]['podau'] = $value['pay_old_login_member'];
                $table[$key]['arpu'] = sprintf("%01.1f", $payAmount / $value['login_day1_member']);
                $table[$key]['arpnu'] = sprintf("%01.1f", $payAmount / $value['create_count']);
                $table[$key]['pay_rate'] = sprintf("%01.2f", $value['pay_member'] / $value['login_day1_member'] * 100) . '%';
                $table[$key]['login_pay_rate'] = sprintf("%01.1f", $value['podau'] / $value['login_day1_member']);
                $table[$key]['pay_amount_asia_web'] = $value['pay_amount_asia_web'] / 100;
                $table[$key]['pay_count_asia_web'] = $value['pay_count_asia_web'];
                $table[$key]['pay_member_asia_web'] = $value['pay_member_asia_web'];

                //计算总和
                $all['create_count'] += $table[$key]['create_count'];
                $all['dau'] += $table[$key]['dau'];
                $all['ndau'] += $table[$key]['ndau'];
                $all['pndau'] += $table[$key]['pndau'];
                $all['odau'] += $table[$key]['odau'];
                $all['podau'] += $table[$key]['podau'];
                $all['pay_amount'] += $payAmount;
                $all['pay_member'] += $value['pay_member'];
                $all['pay_count'] += $value['pay_count'];
                $all['login_day1_member'] += $value['login_day1_member'];
                if ($value['date_time'] == $_GET['endtime']) {
                    $all['all_count'] += $value['all_count'];
                }
                $all['pay_amount_asia_web'] += $value['pay_amount_asia_web'] / 100;
                $all['pay_count_asia_web'] += $value['pay_count_asia_web'];
                $all['pay_member_asia_web'] += $value['pay_member_asia_web'];

            }

            $all['arpnu'] = sprintf("%01.1f", $all['pay_amount'] / $all['create_count']);
            $all['arpu'] = sprintf("%01.1f", $all['pay_amount'] / $all['login_day1_member']);
            $all['pay_rate'] = sprintf("%01.2f", $all['pay_member'] / $all['login_day1_member'] * 100) . '%';
            $all['login_pay_rate'] = sprintf("%01.1f", $all['podau'] / $all['login_day1_member']);

            $this->vTable = $table;
            $this->vAll = $all;
        }

        //显示页面
        $this->display();

    }

}