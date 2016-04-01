<?php
namespace Home\Controller;

use Think\Controller;

class GMParamsController extends BaseController
{

    private $mKey = array(
        'ENABLE_EVENT',
        'ENABLE_MEMBER',
        'EVENT_ICON',
        'GAME_SERVICE',
        'TEAM_MAX_LEVEL',
        'FATE_OPEN_TIME',
        'NEW_SERVER_BONUS',
        'FIRST_PAY_RESET_TIME',
        'PRE_DOWNLOAD_BONUS',
        'FUND_RATE',
        'FUND_CLOSE',
        'MAINTAIN_TIPS',
    );

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
    }

    //显示列表
    public function index()
    {

        if (!empty($_POST)) {
            $where['index'] = I('post.key');
            $data['value'] = trimTT($_POST['value']);
            $dbConfig = change_db_server(I('post.server_id'), 'master');
            $rs = M()->db(I('post.server_id'), $dbConfig)->table('g_params')->where($where)->save($data);
            $log['sid'] = I('post.server_id');
            $log['key'] = I('post.key');
            $log['value'] = trimTT($_POST['value']);;
            $log['result'] = $rs ? 1 : 0;
            D('LParams')->CreateData($log);
            D('Predis')->cli('game', I('post.server_id'))->del('g_params');
            if ($rs) {
                C('G_ERROR', 'success');
            } else {
                C('G_ERROR', 'fail');
            }
        }

        $this->alert = get_error();
        $this->assign('keyList', $this->mKey);
        $this->display();//显示页面

    }

    public function content()
    {
//        if (IS_AJAX) {
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $where['index'] = I('get.key');
            echo M()->db(1, $dbConfig)->table('g_params')->where($where)->getField('value');
//        }
    }

}