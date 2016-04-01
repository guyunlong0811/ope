<?php
namespace Home\Controller;

use Think\Controller;

class DataStatisticsDailyController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->selectDateStartEnd();
        $this->selectChannel();

        $this->vTitle = 'data_statistics_daily';
        $this->vIcon = 'bar-chart-o';
    }

    public function index()
    {

        if (!empty($_GET)) {

            //日期
            $where['date_time'] = array('between', array(I('get.starttime'), I('get.endtime'),));

            //服务器
            if (I('get.server_id') != '0') {
                $where['sid'] = I('get.server_id');
            } else {
                $where['sid'] = array('in', $this->mServerPermissionList);
            }

            //获取数据
            $select = D('DataStatisticsMid')->getAll($where);
//            $table = D('DataStatisticsMid')->field("`date_time`,sum(`all_count`) as `all_count`,sum(`create_count`) as `create_count`,sum(`lost_count`) as `lost_count`,sum(`pay_amount`) as `pay_amount`,sum(`pay_count`) as `pay_count`,sum(`pay_member`) as `pay_member`,sum(`login_count`) as `login_count`,sum(`login_day1_member`) as `login_day1_member`,sum(`login_day7_member`) as `login_day7_member`,sum(`login_day30_member`) as `login_day30_member`,sum(`day1`) as `day1`,sum(`day3`) as `day3`,sum(`day7`) as `day7`,sum(`day15`) as `day15`,sum(`day30`) as `day30`")->where($where)->group('`date_time`')->select();

            //处理数据
            $all['create_count'] = 0;
            foreach ($this->mRetentionDay as $day) {
                $all['day' . $day] = 0;
            }

            //处理每一天的数据
            $table = array();
            foreach ($select as $value) {

                if(!isset($table[$value['date_time']])){
                    $table[$value['date_time']]['date_time'] = $value['date_time'];
                }
                $table[$value['date_time']]['all_count'] += $value['all_count'];
                $table[$value['date_time']]['create_count'] += $value['create_count'];
                $table[$value['date_time']]['lost_count'] += $value['lost_count'];
                $table[$value['date_time']]['pay_amount'] += $value['pay_amount'];
                $table[$value['date_time']]['pay_count'] += $value['pay_count'];
                $table[$value['date_time']]['pay_member'] += $value['pay_member'];
                $table[$value['date_time']]['login_count'] += $value['login_count'];
                $table[$value['date_time']]['login_day1_member'] += $value['login_day1_member'];
                $table[$value['date_time']]['login_day7_member'] += $value['login_day7_member'];
                $table[$value['date_time']]['login_day30_member'] += $value['login_day30_member'];
                if($value['day2'] >= 0){
                    $table[$value['date_time']]['day2'] += $value['day2'];
                }else{
                    if(!isset($table[$value['date_time']]['day2'])){
                        $table[$value['date_time']]['day2'] = 0;
                    }
                }
                if($value['day3'] >= 0) {
                    $table[$value['date_time']]['day3'] += $value['day3'];
                }else{
                    if(!isset($table[$value['date_time']]['day3'])){
                        $table[$value['date_time']]['day3'] = 0;
                    }
                }
                if($value['day7'] >= 0) {
                    $table[$value['date_time']]['day7'] += $value['day7'];
                }else{
                    if(!isset($table[$value['date_time']]['day7'])){
                        $table[$value['date_time']]['day7'] = 0;
                    }
                }
                if($value['day15'] >= 0) {
                    $table[$value['date_time']]['day15'] += $value['day15'];
                }else{
                    if(!isset($table[$value['date_time']]['day15'])){
                        $table[$value['date_time']]['day15'] = 0;
                    }
                }
                if($value['day30'] >= 0) {
                    $table[$value['date_time']]['day30'] += $value['day30'];
                }else{
                    if(!isset($table[$value['date_time']]['day30'])){
                        $table[$value['date_time']]['day30'] = 0;
                    }
                }
            }

            //处理总数据
            foreach ($table as $key => $value) {

                $payAmount = $value['pay_amount'] / 100;
                $table[$key]['pay_amount'] = $payAmount;
                $table[$key]['arpu'] = sprintf("%01.1f", $payAmount / $value['login_day1_member']);
                $table[$key]['arppu'] = sprintf("%01.1f", $payAmount / $value['pay_member']);
                $table[$key]['pay_rate'] = sprintf("%01.2f", $value['pay_member'] / $value['login_day1_member'] * 100) . '%';

                //计算总和
                $all['create_count'] += $value['create_count'];
                $all['login_count'] += $value['login_count'];
                $all['login_day1_member'] += $value['login_day1_member'];
                $all['pay_amount'] += $value['pay_amount'] / 100;
                $all['pay_count'] += $value['pay_count'];
//                $all['pay_member'] += $value['pay_member'];
                if ($value['date_time'] == I('get.endtime')) {
                    $all['all_count'] += $value['all_count'];
                }

                foreach ($this->mRetentionDay as $day) {

                    if ($value['day' . $day] > 0) {
                        $all['day' . $day] += $value['day' . $day];
                        $all['day' . $day . '_create'] += $value['create_count'];
                    }

                    if ($value['create_count'] == 0) {
                        $table[$key]['day' . $day . '_rate'] = '/';
                    } else if ($value['day' . $day] == '-1') {
                        $table[$key]['day' . $day . '_rate'] = '-';
                    } else {
                        $table[$key]['day' . $day . '_rate'] = sprintf("%01.2f", ($value['day' . $day] / $value['create_count']) * 100) . '%';
                    }

                }

            }

            //处理全部
            foreach ($this->mRetentionDay as $day) {
                $all['pay_member'] = '/';
                if ($all['create_count'] == 0) {
                    $all['day' . $day . '_rate'] = '/';
                } else if ($all['day' . $day] == '-1') {
                    $all['day' . $day . '_rate'] = '-';
                } else {
                    $all['day' . $day . '_rate'] = sprintf("%01.2f", ($all['day' . $day] / $all['day' . $day . '_create']) * 100) . '%';
                }

            }

            $all['arpu'] = sprintf("%01.1f", $all['pay_amount'] / $all['login_day1_member']);
            $all['arppu'] = '/';
//            $all['arppu'] = sprintf("%01.1f", $all['pay_amount'] / $all['pay_member']);
            $all['pay_rate'] = '/';
//            $all['pay_rate'] = sprintf("%01.2f", $all['pay_member'] / $all['login_day1_member'] * 100) . '%';

            $this->vTable = $table;
            $this->vAll = $all;
        }

        //显示页面
        $this->display();

    }

}