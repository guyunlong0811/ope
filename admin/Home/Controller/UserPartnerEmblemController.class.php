<?php
namespace Home\Controller;

use Think\Controller;

class UserPartnerEmblemController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectPartner();
        $this->vIcon = 'group';
        $this->vTitle = 'user_partner_emblem';
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
                $where['partner'] = I('get.partner_group_id');
            }

            if (empty($where)) {
                $list = array();
            } else {

                $dbConfig = change_db_server(I('get.server_id'), 'master');
                $page = $this->page(M()->db(I('get.server_id'), $dbConfig)->table('g_emblem'), 'sql', $where);
                $order = array('partner' => 'asc', 'slot' => 'asc');
                $list = M()->db(I('get.server_id'))->table('g_emblem')->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
                if (!empty($list)) {
                    foreach ($list as $key => $value) {
                        $list[$key]['partner'] = $value['partner'] == 0 ? '/' : $this->vPartnerConfig[$value['partner']];
                        $list[$key]['slot'] = $value['slot'] == 0 ? '/' : $value['slot'];
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