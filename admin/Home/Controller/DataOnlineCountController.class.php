<?php
namespace Home\Controller;

use Think\Controller;

class DataOnlineCountController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectDate(time2format(null, 2), '2015-03-20', '%y-%M-%d');
        $this->drawChart();
        $this->vTitle = 'data_online_count';
        $this->vIcon = 'bar-chart-o';
    }

    public function index()
    {

        if (!empty($_GET)) {
            //显示
            $starttime = strtotime(I('get.date'));
            $endtime = $starttime + 86399;
            $countNums = M('DataOnlineCount')->field(array('hour', 'count'))->where("`sid`='" . I('get.server_id') . "' && `hour` between '{$starttime}' and '{$endtime}'")->order('`hour` ASC')->select();
            if (!empty($countNums)) {
                foreach ($countNums as $aCountNum) {
                    $hour = date("H", $aCountNum['hour']);
                    $aResult = array($hour, $aCountNum['count']);
                    $result[] = $aResult;
                }
            } else {
                $result = array();
            }

            $this->vChartData = json_encode($result);
            $this->vChartLabel = I('get.date');
            $this->vChartXTicks = (string)count($result);
        }

        //显示页面
        $this->display();

    }

}