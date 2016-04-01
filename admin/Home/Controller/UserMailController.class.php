<?php
namespace Home\Controller;

use Think\Controller;

class UserMailController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectItem();
        $this->vIcon = 'group';
        $this->vTitle = 'user_mail';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $where = array();
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $where['tid'] = I('get.team_id');
            $page = $this->page(M()->db(I('get.server_id'), $dbConfig)->table('g_mail'), 'sql', $where);
            $list = M()->db(I('get.server_id'))->table('g_mail')->page($this->pg . ',' . $page->listRows)->where($where)->select();
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['type'] = $value['type'] == 1 ? L('notice_mail') : L('annex_mail');
                    $list[$key]['ctime'] = time2format($value['ctime']);
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

    //删除邮件
    public function remove()
    {

        C('G_SERVER', I('get.server_id'));
        $where['id'] = I('get.mail_id');
        $info = M()->db(I('get.server_id'), change_db_server(I('get.server_id'), 'master'))->table('g_mail')->where($where)->find();
        if (false === M()->db(I('get.server_id'))->table('g_mail')->where($where)->delete()) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }
        save_sql(M()->db(I('get.server_id'))->table('g_mail')->getLastSql());
        $log = $info;
        unset($log['id']);
        $log['create_time'] = time();
        $log['status'] = 3;
        M()->db(I('get.server_id'))->table('l_mail')->add($log);
        save_sql(M()->db(I('get.server_id'))->table('l_mail')->getLastSql());
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&team_id=" . I('get.team_id') . "&p=" . $this->pg;
        $this->display("Public:jump");//显示页面

    }

}