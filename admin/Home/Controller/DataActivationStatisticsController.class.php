<?php
namespace Home\Controller;

use Think\Controller;

class DataActivationStatisticsController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->selectDate();
        $this->selectChannel();

        $this->vTitle = 'data_activation_statistics';
        $this->vIcon = 'bar-chart-o';

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
            $start = time2format(strtotime(I('get.date')) - 86400, 2);
            $where['date_time'] = array('between', array($start, I('get.date'),));

            //查询昨日&今日数据
            $field = array('create_count', 'date_time', 'login_count', 'login_day1_member', 'login_day7_member', 'login_day30_member',);
            $select = D('DataStatisticsMid')->field($field)->where($where)->select();

            //数据处理
            $table['today_create'] = 0;
            $table['yesterday_create'] = 0;
            $table['login_day1_count'] = 0;
            $table['login_all_member'] = 0;
            $table['login_day1_member'] = 0;
            $table['login_day7_member'] = 0;
            $table['login_day30_member'] = 0;
            foreach ($select as $value) {
                if ($value['date_time'] == I('get.date')) {
                    $table['today_create'] += $value['create_count'];
                    $table['login_day1_count'] += $value['login_count'];
                    $table['login_day1_member'] += $value['login_day1_member'];
                    $table['login_day7_member'] += $value['login_day7_member'];
                    $table['login_day30_member'] += $value['login_day30_member'];
                } else {
                    $table['yesterday_create'] += $value['create_count'];
                }
            }

            //查询本周总数
            $monday = time2format(strtotime("-1 monday"), 2);
            $where['date_time'] = array('between', array($monday, I('get.date'),));
            $table['this_week_create'] = D('DataStatisticsMid')->where($where)->sum('create_count');

            //查询上周同期总数
            $lastMonday = time2format(strtotime($monday) - (7 * 86400), 2);
            $lastToday = time2format(strtotime(I('get.date')) - (7 * 86400), 2);
            $where['date_time'] = array('between', array($lastMonday, $lastToday,));
            $table['last_week_create'] = D('DataStatisticsMid')->where($where)->sum('create_count');

            //计算周环比
            $table['ppi_create'] = sprintf("%01.2f", (($table['this_week'] - $table['last_week']) / $table['last_week']) * 100) . '%';

            //查询本月总数
            $month1 = substr(I('get.date'), 0, -2) . '01';
            $where['date_time'] = array('between', array($month1, I('get.date'),));
            $table['month_create'] = D('DataStatisticsMid')->where($where)->sum('create_count');

            //查询近7天重复登录人数
            $last7day = time2format(strtotime("-7 days"), 2);
            $where['date_time'] = array('between', array($last7day, I('get.date'),));
            $table['login_day7_create'] = D('DataStatisticsMid')->where($where)->sum('create_count');
            $table['login_day7_count'] = D('DataStatisticsMid')->where($where)->sum('login_count');

            //查询近30天重复登录人数
            $last7day = time2format(strtotime("-30 days"), 2);
            $where['date_time'] = array('between', array($last7day, I('get.date'),));
            $table['login_day30_create'] = D('DataStatisticsMid')->where($where)->sum('create_count');
            $table['login_day30_count'] = D('DataStatisticsMid')->where($where)->sum('login_count');

            //查询近30天重复登录人数
            unset($where['date_time']);
            $table['login_all_count'] = D('DataStatisticsMid')->where($where)->sum('login_count');

            //返回数据
            $this->vTable = $table;

        }

        //显示页面
        $this->display();

    }

}