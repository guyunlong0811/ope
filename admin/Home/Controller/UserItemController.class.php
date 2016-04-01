<?php
namespace Home\Controller;

use Think\Controller;

class UserItemController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectItem();
        $this->vIcon = 'group';
        $this->vTitle = 'user_item';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $where = array();
            $dbConfig = change_db_server(I('get.server_id'), 'master');

            $where['tid'] = I('get.team_id');
            $table = 'g_item_' . I('get.team_id') % 10;
            $page = $this->page(M()->db(1, $dbConfig)->table($table), 'sql', $where);
            $list = M()->db(1, $dbConfig)->table($table)->page($this->pg . ',' . $page->listRows)->where($where)->select();

            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['name'] = $this->vItemConfig[$value['item']];
                }
            } else {
                $list = array();
            }
            $this->vTable = $list;
        }

        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}