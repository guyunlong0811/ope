<?php
namespace Home\Controller;

use Think\Controller;

class UserOrderController extends BInitController
{

    static private $statusType = array(
        2 => '订单完成(自定义)',
        1 => '订单完成',
        0 => '订单失败',
        -1 => '订单取消',
        -2 => '订单过期',
        -3 => '验证失败',
        -4 => '重复凭证',
        -5 => '商品增加失败',
        -6 => '价格有误',
    );

    public function _initialize()
    {
        parent::_initialize();
        $this->selectDateStartEnd(time2format(strtotime('-6 days'), 2), time2format(null, 2), '2015-03-20', $max = '%y-%M-%d');
        $this->vIcon = 'group';
        $this->vTitle = 'user_order';
    }

    public function index()
    {

        if (!empty($_GET)) {

            //条件
            $where['tid'] = I('get.team_id');
            $start = strtotime(I('get.starttime') . ' 00:00:00');
            $end = strtotime(I('get.endtime') . ' 23:59:59');
            $where1 = "`ctime` between '{$start}' and '{$end}'";
            $where2 = "`endtime` between '{$start}' and '{$end}'";

            //查询
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $gameData = M()->db(I('get.server_id'), $dbConfig)->table('g_order')->where($where)->where($where1)->select();
            $logData = M()->db(I('get.server_id'))->table('l_order')->where($where)->where($where2)->select();

            //查询渠道信息
            $channelList = D('UCChannel')->getAll();

            //查询商品信息
            $cashConfig = D('Static')->access('cash');

            if (!empty($gameData)) {
                foreach ($gameData as $key => $value) {
                    $gameData[$key]['cash'] = $cashConfig[$value['cash_id']]['name'];
                    $gameData[$key]['channel'] = $channelList[$value['channel_id']];
                    $gameData[$key]['ctime'] = time2format($value['ctime']);
                }
            } else {
                $gameData = array();
            }

            if (!empty($logData)) {
                foreach ($logData as $key => $value) {
                    $logData[$key]['cash'] = $cashConfig[$value['cash_id']]['name'];
                    $logData[$key]['channel'] = $channelList[$value['channel_id']];
                    $logData[$key]['starttime'] = time2format($value['starttime']);
                    $logData[$key]['endtime'] = time2format($value['endtime']);
                    $logData[$key]['status'] = self::$statusType[$value['status']];
                }
            } else {
                $logData = array();
            }


            $this->gameData = $gameData;
            $this->logData = $logData;
//            dump($this->gameData);
//            dump($this->logData);
        }

        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}