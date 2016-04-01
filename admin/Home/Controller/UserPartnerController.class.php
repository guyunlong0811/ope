<?php
namespace Home\Controller;

use Think\Controller;

class UserPartnerController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectPartner();
        $this->vIcon = 'group';
        $this->vTitle = 'user_partner';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $this->server_id = I('get.server_id');
            $where = array();
            if (I('get.team_id')) {
                $where['tid'] = I('get.team_id');
            }

            if (I('get.partner_group_id') != 0) {
                $where['group'] = I('get.partner_group_id');
            }

            if (empty($where)) {
                $list = array();
            } else {

                $dbConfig = change_db_server(I('get.server_id'), 'master');
                $page = $this->page(M()->db(I('get.server_id'), $dbConfig)->table('g_partner'), 'sql', $where);
                $order = array('group' => 'asc', 'index' => 'desc', 'level' => 'desc');
                $list = M()->db(I('get.server_id'))->table('g_partner')->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
                if (!empty($list)) {
                    foreach ($list as $key => $value) {
                        $list[$key]['partner_name'] = $this->vPartnerConfig[$value['group']];
                        $list[$key]['index'] = $value['index'] % 10;
                        $list[$key]['ctime'] = time2format($value['ctime']);
                    }
                } else {
                    $list = array();
                }

            }

            $this->vTable = $list;
        }

        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}