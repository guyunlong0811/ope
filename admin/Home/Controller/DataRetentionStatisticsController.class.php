<?php
namespace Home\Controller;

use Think\Controller;

class DataRetentionStatisticsController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->selectDateStartEnd();
        $this->selectChannel();

        $this->vTitle = 'data_retention_statistics';
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
            //服务器
            if (I('get.channel_id') != '0') {
                $where['channel_id'] = I('get.channel_id');
            }
            //日期
            $where['date_time'] = array('between', array(I('get.starttime'), I('get.endtime'),));

            //获取数据
            $table = D('DataStatisticsMid')->getRetention($where);

            //获取渠道信息
            $channelList = D('UCChannel')->getAll();

            //处理数据
            $all['create_count'] = 0;
            foreach ($this->mRetentionDay as $day) {
                $all['day' . $day] = 0;
            }
            foreach ($table as $key => $value) {

                $table[$key]['channel_name'] = $channelList[$value['channel_id']];

                //计算总和
                $all['create_count'] += $value['create_count'];

                foreach ($this->mRetentionDay as $day) {

                    if ($value['day' . $day] > 0) {
                        $all['day' . $day] += $value['day' . $day];
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

                if ($all['create_count'] == 0) {
                    $all['day' . $day . '_rate'] = '/';
                } else if ($all['day' . $day] == '-1') {
                    $all['day' . $day . '_rate'] = '-';
                } else {
                    $all['day' . $day . '_rate'] = sprintf("%01.2f", ($all['day' . $day] / $all['create_count']) * 100) . '%';
                }

            }

            $this->vTable = $table;
            $this->vAll = $all;
        }

        //显示页面
        $this->display();

    }

}