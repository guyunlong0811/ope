<?php
namespace Home\Controller;

use Think\Controller;

class GMExchangeController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_exchange';
    }

    //显示列表
    public function index()
    {

        //UID
        if (!empty($_GET)) {
            $type = D('UCExchangeType')->getAll();
            $model = D('UCExchange');

            if (I('get.code') == '') {
                $where['sid'] = I('get.server_id');
            } else {
                $where = "`code` like '" . I('get.code') . "%'";
            }

            $page = $this->page($model, 'sql', $where);
            $list = $model->page($this->pg . ',' . $page->listRows)->where($where)->select();
            foreach ($list as $key => $value) {
                $list[$key]['type_name'] = $type[$value['type']]['name'];
                $list[$key]['ctime'] = time2format($value['ctime']);
                $list[$key]['uid'] = $value['uid'] > 0 ? $value['uid'] : L('no_use');
                if ($value['sid'] != '0') {
                    $list[$key]['sid'] = $this->getServerName($value['sid']);
                } else {
                    $list[$key]['sid'] = L('no_use');
                }
                $list[$key]['utime'] = $value['utime'] > 0 ? time2format($value['utime']) : L('no_use');
                $list[$key]['status'] = $value['status'] == 0 ? L('no_use') : L('used');
            }
        }

        //显示
        $this->list = $list;
        $this->display();//显示页面
    }

}