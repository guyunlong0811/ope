<?php
namespace Home\Controller;

use Think\Controller;

class UserItemChangeController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'group';
        $this->vTitle = 'user_item_change';

        $config = C('BEHAVE');
        foreach ($config as $value) {
            $behave[$value['code']] = $value['message'];
        }
        $this->vBehave = $behave;
        $this->selectItem();
    }

    public function index()
    {

        $this->isSubmit = 0;

        if (!empty($_GET)) {

            $this->isSubmit = 1;
            $this->server_id = I('get.server_id');
            if (!I('get.server_id') || !I('get.team_id')) {
                $this->accountInfo = array();
            } else {
                $this->server_id = I('get.server_id');
                $this->team_id = $where['tid'] = I('get.team_id');
                if (I('get.item_id') && I('get.item_id') != 'all') {
                    $where['item'] = I('get.item_id');
                }

                $dbConfig = change_db_server($this->server_id, 'master');
                $model = M()->db($this->server_id, $dbConfig)->table('l_item');
                $order = array('ctime' => 'desc',);
                $page = $this->page($model, 'sql', $where);
                $row = $model->page($this->pg . ',' . $page->listRows)->table('l_item')->where($where)->order($order)->select();
                $data = array();
                if (!empty($row)) {
                    foreach ($row as $key => $value) {
                        $row[$key]['item'] = $this->vItemConfig[$value['item']];
                        $row[$key]['behave'] = $this->vBehave[$value['behave']];
                        $row[$key]['ctime'] = time2format($value['ctime']);
                    }
                    $data = $row;
                }

            }

            $this->vTable = $data;

        }


        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}