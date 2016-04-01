<?php
namespace Home\Controller;

use Think\Controller;

class BInitController extends BaseController
{
    //图表
    protected function drawChart($data = array(), $label = '', $xTicks = 20, $yTicks = 10)
    {
        $this->vChartData = $data;
        $this->vChartLabel = $label;
        $this->vChartXTicks = $xTicks;
        $this->vChartYTicks = $yTicks;
        $this->vCharthover = 'y';
    }

    //选择日期
    protected function selectDate($date = null, $min = '2015-03-20', $max = '%y-%M-{%d-1}', $fmt = 'yyyy-MM-dd')
    {
        $utime = get_daily_utime();
        $this->vDate = is_null($date) ? time2format($utime - 86400, 2) : $date;
        $this->vDateFmt = $fmt;
        $this->vDateMin = $min;
        $this->vDateMax = $max;
    }

    //选择日期
    protected function selectDateStartEnd($start = null, $end = null, $min = '2015-03-20', $max = '%y-%M-{%d-1}', $fmt = 'yyyy-MM-dd')
    {
        $utime = get_daily_utime();
        $this->vDateStart = is_null($start) ? time2format($utime - 8 * 86400, 2) : $start;
        $this->vDateEnd = is_null($end) ? time2format($utime - 86400, 2) : $end;
        $this->vDateFmt = $fmt;
        $this->vDateMin = $min;
        $this->vDateMax = $max;
    }

    //选择日期
    protected function selectDay($day = array(7, 14, 30))
    {
        $this->vDay = $day;
    }

    //伙伴
    protected function selectChannel()
    {
        $this->vChannel = D('UCChannel')->getAll();
    }

    //伙伴
    protected function selectPartner()
    {
        $partner = D('Static')->access('partner_group');
        foreach ($partner as $key => $value) {
            $arr = current($value);
            $config[$key] = $arr['name'];
        }
        $this->vPartnerConfig = $config;
    }

    //道具
    protected function selectItem()
    {
        $item = D('Static')->access('item');
        foreach ($item as $key => $value) {
            $config[$key] = $value['name'];
        }
        $this->vItemConfig = $config;
    }

    //任务
    protected function selectQuest()
    {
        $questConfig = D('Static')->access('quest');
        $questConfig = $questConfig[1];
        foreach ($questConfig as $key => $value) {
            $config[$key] = $value['name'];
        }
        $this->vQuestConfig = $config;
    }


}