<?php
namespace Home\Controller;

use Think\Controller;

class UserPrayController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vTitle = 'user_pray';
        $this->vIcon = 'group';
        $this->vPray = D('Static')->access('pray');//获取祈愿选项
    }

    public function index()
    {

        if (!empty($_GET)) {

            $list = array();
            if (I('get.server_id') && I('get.team_id')) {

                //查询条件
                $where['tid'] = I('get.team_id');
                if (I('get.pray_id') != 'all') {
                    $where['pray_id'] = I('get.pray_id');
                }
                if (I('get.is_free') != 'all') {
                    $where['is_free'] = I('get.is_free');
                }
                $order = array('ctime' => 'desc',);

                //查询
                $dbConfig = change_db_server(I('get.server_id'), 'master');
                $page = $this->page(M()->db(I('get.server_id'), $dbConfig)->table('l_pray'), 'sql', $where);
                $list = M()->db(I('get.server_id'))->table('l_pray')->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
                if (!empty($list)) {

                    foreach ($list as $key => $value) {
                        $bonus = json_decode($value['bonus'], true);
                        $bonusList = array();
                        foreach ($bonus as $val) {
                            $arr['type'] = L($this->mBonusType[$val['type']]);
                            $arr['id'] = $this->getBonusId($val['type'], $val['id']);
                            $arr['count'] = $val['count'];
                            $bonusList[] = $arr;
                        }
                        $list[$key]['bonus_list'] = $bonusList;
                        $list[$key]['is_free'] = $value['is_free'] == '1' ? L('free') : L('paid');
                        $list[$key]['memo'] = $this->vPray[$value['pray_id']]['memo'];
                        $list[$key]['ctime'] = time2format($value['ctime']);
                    }

                }

            }

            $this->vTable = $list;

        }
        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}