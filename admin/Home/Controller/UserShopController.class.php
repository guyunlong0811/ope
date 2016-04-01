<?php
namespace Home\Controller;

use Think\Controller;

class UserShopController extends BInitController
{

    const BEHAVE_START = 5000;
    const BEHAVE_END = 5099;
    static private $shopBehave = array(5001, 5002, 5003, 5004, 5005,);

    public function _initialize()
    {
        parent::_initialize();
        $this->selectDateStartEnd();
        $this->selectItem();
        $this->vIcon = 'group';
        $this->vTitle = 'user_shop';
        $this->vShopBehave = self::$shopBehave;
    }

    public function index()
    {

        if (!empty($_GET)) {

            $where = array();
            if (I('get.team_id') > 0) {
                $where['tid'] = I('get.team_id');
            }

            if (I('get.item_id') > 0) {
                $where['item'] = I('get.item_id');
            }

            //ctime
            if (I('get.starttime')) {
                $start = strtotime(I('get.starttime') . ' 00:00:00');
            } else {
                $start = strtotime(time2format(null, 2) . ' 00:00:00');
            }

            if (I('get.endtime')) {
                $end = strtotime(I('get.endtime') . ' 23:59:59');
            } else {
                $end = strtotime(time2format(null, 2) . ' 23:59:59');
            }

            $where['count'] = array('GT', 0);
            $where['ctime'] = array('BETWEEN', array($start, $end,));

            //behave
            if (I('get.behave_id') > 0) {
                $where['behave'] = I('get.behave_id');
            } else {
                $where['behave'] = array('BETWEEN', array(self::BEHAVE_START, self::BEHAVE_END,));
            }


            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $page = $this->page(M()->db(I('get.server_id'), $dbConfig)->table('l_item'), 'sql', $where);
            $list = M()->db(I('get.server_id'))->table('l_item')->page($this->pg . ',' . $page->listRows)->where($where)->order('`ctime` DESC')->select();
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['name'] = $this->vItemConfig[$value['item']];
                    $list[$key]['ctime'] = time2format($value['ctime']);
                }
            } else {
                $list = array();
            }
            $this->vTable = $list;

            //计算玩家购买人数
            $buyUserCount = 0;
            if (I('get.team_id') == 0 && !empty($list)) {
                $buyUserCount = M()->db(I('get.server_id'))->table('l_item')->where($where)->getField('count(distinct `tid`)');
            }

        }

        //显示
        $this->buyUserCount = $buyUserCount;
        $this->alert = get_error();
        $this->display();//显示页面
    }

}