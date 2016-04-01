<?php
namespace Home\Controller;

use Think\Controller;

class UserQuestController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->selectQuest();
        $this->vIcon = 'group';
        $this->vTitle = 'user_quest';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $where = array();
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $where['tid'] = I('get.team_id');
            $order['status'] = 'asc';
            $order['ctime'] = 'desc';
            $order['utime'] = 'desc';
            $list = M()->db(I('get.server_id'), $dbConfig)->table('g_quest')->where($where)->order($order)->select();
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['quest_name'] = $this->vQuestConfig[$value['quest']];
                    $list[$key]['ctime'] = time2format($value['ctime']);
                    $list[$key]['utime'] = $value['utime'] > 0 ? time2format($value['utime']) : '/';
                    $list[$key]['status_info'] = $value['status'] == 1 ? L('finished') : L('unfinished');
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
    public function complete()
    {

        C('G_SERVER', I('get.server_id'));
        $dbConfig = change_db_server(I('get.server_id'), 'master');
        $data['status'] = 1;
        $where['tid'] = I('get.team_id');
        $where['quest'] = I('get.quest_id');
        if (false === M()->db(I('get.server_id'), $dbConfig)->table('g_quest')->data($data)->where($where)->save()) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }
        save_sql(M('GQuest')->getLastSql());

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&team_id=" . I('get.team_id') . "";
        $this->display("Public:jump");//显示页面

    }

}