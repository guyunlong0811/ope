<?php
namespace Home\Controller;

use Think\Controller;

class DataPayDetailController extends BInitController
{

    private $mLimit = 30;//获取排名

    public function _initialize()
    {
        parent::_initialize();

        //加载模块
        $this->drawChart();
        $this->selectDate(time2format(null, 2), time2format(strtotime('-7 days'), 2), time2format(null, 2));
        $this->selectChannel();

        $this->vTitle = 'data_pay_detail';
        $this->vIcon = 'bar-chart-o';

    }

    public function index()
    {

        if (!empty($_GET)) {
            //服务器
            $dbConfig = change_db_server(I('get.server_id'), 'master');

            //渠道
            if (I('get.channel_id') != '0') {
                $wherePage['channel_id'] = I('get.channel_id');
            }

            //日期
            $starttime = strtotime(I('get.date'));
            $endtime = $starttime + 86399;
            $wherePage['endtime'] = array('between', array($starttime, $endtime,));

            //查询渠道信息
            $channelList = D('UCChannel')->getAll();

            //查询商品信息
            $cashConfig = D('Static')->access('cash');

            //查询信息
            $wherePage['status'] = 1;
            $page = $this->page(M()->db(1, $dbConfig)->table('l_order'), 'sql', $wherePage);
            $list = M()->db(1, $dbConfig)->table('l_order')->page($this->pg . ',' . $page->listRows)->join('`g_team` on `g_team`.`tid`=`l_order`.`tid`')->field("`g_team`.`tid`,`g_team`.`nickname`,`l_order`.`cash_id`,`l_order`.`price`,`l_order`.`channel_id`,`l_order`.`level`,`l_order`.`endtime`")->where("`l_order`.`endtime` between '{$starttime}' and '{$endtime}' && `l_order`.`status`=1")->order("`endtime` ASC")->select();
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['channel'] = $channelList[$value['channel_id']];
                    $list[$key]['cash'] = $cashConfig[$value['cash_id']]['name'];
                    $list[$key]['price'] = $value['price'] / 100;
                    $list[$key]['endtime'] = time2format($value['endtime']);
                }
            }
            $this->vTable = $list;

        }

        //显示页面
        $this->display();

    }

}