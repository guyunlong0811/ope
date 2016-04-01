<?php
namespace Home\Controller;

use Think\Controller;

class DataNodesMonitorController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectDateStartEnd(time2format(strtotime('-6 days'), 2), time2format(null, 2), '2015-03-20', $max = '%y-%M-%d');
        $this->drawChart();
        $this->vIcon = 'bar-chart-o';
        $this->vTitle = 'data_nodes_monitor';
    }

    public function index()
    {

        if (!empty($_GET)) {

            //查询新手引导配置
            $guideSConfig = D('Static')->access('guide');
            $guideSeqConfig = D('Static')->access('guide_seq');

            //数据处理
            $step1 = array();
            foreach ($guideSeqConfig as $key => $value) {
                if ($value['seq_type'] == '0') {
                    $step1[] = $key;//获取新手引导的大步骤index
                }
            }
            $in = sql_in_condition($step1);

            //获取新手引导序列

            //条件
//            $utime = C('DAILY_UTIME');
            $utime = '00:00:00';
            $start = strtotime(I('get.starttime') . ' ' . $utime);
            $end = strtotime(I('get.endtime') . ' ' . $utime) + 86399;

            //连接数据库
            $dbConfig = change_db_server(I('get.server_id'), 'master');

            //查询用户注册数量
            $createSkipCount = M()->db(1, $dbConfig)->table('g_team')->where("`ctime` between '{$start}' and '{$end}' && `guide_skip`='1'")->count();
            $createNotSkipCount = M()->db(1, $dbConfig)->table('g_team')->where("`ctime` between '{$start}' and '{$end}' && `guide_skip`='0'")->count();

            //未跳过情况
            $sql = "select `gg`.`step2`,count(`gg`.`step2`) as `count` from (select * from `g_guide` where `step1` in{$in} group by `tid`,`step1`) as `gg`,`g_team` as `gt` where `gg`.`tid`=`gt`.`tid` && `gt`.`ctime` between '{$start}' and '{$end}' && `gt`.`guide_skip`='0' group by `gg`.`step2`";
            $select = M()->db(1, $dbConfig)->query($sql);
            foreach ($select as $value) {
                $notSkipData[$value['step2']] = $value['count'];
            }

            //跳过情况
            $sql = "select `gg`.`step2`,count(`gg`.`step2`) as `count` from (select * from `g_guide` where `step1` in{$in} group by `tid`,`step1` DESC) as `gg`,`g_team` as `gt` where `gg`.`tid`=`gt`.`tid` && `gt`.`ctime` between '{$start}' and '{$end}' && `gt`.`guide_skip`='1' group by `gg`.`step2`";
            $select = M()->db(1, $dbConfig)->query($sql);
            foreach ($select as $value) {
                $skipData[$value['step2']] = $value['count'];
            }


//            dump($notSkipData);
//            dump($skipData);

            //计算数据
            $dataAll[] = array(
                'step' => 0,
                'des' => L('user_create_count'),
                'not_skip_count' => $createNotSkipCount,
                'skip_count' => $createSkipCount,
                'stay_count' => $createNotSkipCount + $createSkipCount,
                'not_skip_rate' => 100,
                'skip_rate' => 100,
                'stay_rate' => 100,
            );


            foreach ($guideSeqConfig as $key => $value) {

                if ($value['seq_type'] == '0') {

                    //计算队列
                    $seq = array_reverse(json_decode($value['seq'], true));

                    //初始化数据
                    $countNotSkip = 0;
                    $countSkip = 0;
                    $dataStep = array();

                    //遍历队列
                    foreach ($seq as $val) {

                        $data = array();
                        $data['step'] = $val;
                        $data['des'] = $guideSConfig[$val]['memo'];
                        $notSkip = isset($notSkipData[$val]) ? $notSkipData[$val] : 0;
                        $skip = isset($skipData[$val]) ? $skipData[$val] : 0;
                        $countNotSkip += $notSkip;
                        $countSkip += $skip;
                        $data['not_skip_count'] = $countNotSkip;
                        $data['skip_count'] = $countSkip;
                        $data['stay_count'] = $countNotSkip + $countSkip;
                        $data['not_skip_rate'] = sprintf("%01.2f", ($countNotSkip / $createNotSkipCount) * 100);
                        $data['skip_rate'] = sprintf("%01.2f", ($countSkip / $createSkipCount) * 100);
                        $data['stay_rate'] = sprintf("%01.2f", (($countNotSkip + $createSkipCount) / ($createNotSkipCount + $createSkipCount)) * 100);
                        $dataStep[] = $data;

                    }

                    //放入总数据
                    $dataAll = array_merge($dataAll, array_reverse($dataStep));

                }

            }

//            dump($dataAll);
            $this->vTable = $dataAll;

            //图形
            $chartNotSkip = array();
            $chartSkip = array();
            $chartStay = array();
            $xTick[] = array(0, 'create');
            $i = 0;
            foreach ($dataAll as $value) {
                $xTick[] = array($i, $value['step']);
                $chartNotSkip[] = array($i, $value['not_skip_rate'] * 100);
                $chartSkip[] = array($i, $value['skip_rate'] * 100);
                $chartStay[] = array($i, $value['stay_rate'] * 100);
                ++$i;
            }

            $chartData['data'] = $chartNotSkip;
            $chartData['label'] = L('not_skip');
            $chartList[] = $chartData;

            $chartData['data'] = $chartSkip;
            $chartData['label'] = L('skip');
            $chartList[] = $chartData;

            $chartData['data'] = $chartStay;
            $chartData['label'] = L('stay');
            $chartList[] = $chartData;

//            dump($chartList);
//            dump($xTick);
            for ($i = 0; $i <= 10; ++$i) {
                $num1 = $i * 1000;
                $num2 = $i * 10;
                $yTick[] = array($num1, $num2 . '%');
            }

            $this->vChartList = json_encode($chartList);
            $this->vChartXTicks = json_encode($xTick);
            $this->vChartYTicks = json_encode($yTick);
            $this->vCharthover = "y / 100 + '%'";
        }

        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}