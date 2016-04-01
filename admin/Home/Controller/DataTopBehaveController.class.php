<?php
namespace Home\Controller;

use Think\Controller;

class DataTopBehaveController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate();

        $this->vTitle = 'data_top_behave';
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
            //日期
            $where['date_time'] = I('get.date');
            //类型
            $where['type'] = I('get.type');

            $select = D('DataTopBehave')->where($where)->order('`level` DESC,`tid` ASC')->select();
            $i = 1;
            foreach ($select as $key => $value) {
                $select[$key]['id'] = $i;
                $select[$key]['server_name'] = $this->getServerName($value['sid']);
                $select[$key]['instance_1'] = substr($value['instance_1'], 1, 2) . '-' . substr($value['instance_1'], 3, 2);
                $select[$key]['instance_2'] = substr($value['instance_2'], 1, 2) . '-' . substr($value['instance_2'], 3, 2);
                $select[$key]['partner_level_avg'] = sprintf("%01.1f", $value['partner_level'] / $value['partner_count']);
                $select[$key]['partner_favour_avg'] = sprintf("%01.1f", $value['partner_favour'] / $value['partner_count']);
                $select[$key]['skill_level_avg'] = sprintf("%01.1f", $value['skill_level'] / ($value['partner_count'] * 4));
                $select[$key]['equip_level_avg'] = sprintf("%01.1f", $value['equip_level'] / ($value['partner_count'] * 3));
                $select[$key]['equip_upgrade_avg'] = sprintf("%01.1f", $value['equip_upgrade'] / ($value['partner_count'] * 3));
                ++$i;
            }
            $this->vTable = $select;

        }

        //显示页面
        $this->display();

    }

}