<?php
namespace Home\Controller;

use Think\Controller;

class UserMailLogController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectItem();
        $this->vIcon = 'group';
        $this->vTitle = 'user_mail_log';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $where = array();
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $where['tid'] = I('get.team_id');
            $page = $this->page(M()->db(I('get.server_id'), $dbConfig)->table('l_mail'), 'sql', $where);
            $list = M()->db(I('get.server_id'))->table('l_mail')->page($this->pg . ',' . $page->listRows)->where($where)->select();
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['type'] = $value['type'] == 1 ? L('notice_mail') : L('annex_mail');
                    $list[$key]['create_time'] = time2format($value['create_time']);
                    $list[$key]['ctime'] = time2format($value['ctime']);
                    $list[$key]['dtime'] = time2format($value['dtime']);
                    for ($i = 1; $i <= 4; ++$i) {
                        if ($list[$key]['item_' . $i . '_type'] == 0) {
                            $list[$key]['annex' . $i] = L($this->mBonusType[$value['item_' . $i . '_type']]);
                        } else {
                            $list[$key]['annex' . $i] = L($this->mBonusType[$value['item_' . $i . '_type']]) . '-' . $this->getBonusId($value['item_' . $i . '_type'], $value['item_' . $i . '_value_1']) . ':' . $value['item_' . $i . '_value_2'] . ';';
                        }
                    }
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