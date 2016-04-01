<?php
namespace Home\Controller;

use Think\Controller;
use Think;

class HomeController extends Controller
{

    //登录
    public function login()
    {

        if ($_SESSION['uid']) {
            redirect(__APP__ . MODULE_NAME . '/Index/index');
        }
        if (!empty($_POST)) {
            if (D('AdminAccount')->login(I('post.username'), I('post.password'))) {
                redirect(__APP__ . MODULE_NAME . '/Index/index');
            }
        }
        $this->alert = get_error();
        $this->display();

    }

    //登出
    public function logout()
    {
        unset($_SESSION['uid']);
        redirect(__APP__ . MODULE_NAME . '/Home/login');
    }

    //守护进程
    public function nohup()
    {
        set_time_limit(0);
        if (I('get.type') == 'DailyMid') {
            $ret = curl_link(OPE_URL . "?s=/Home/Script/exec&interval=Daily&utime=mid");
        } else {
            $ret = curl_link(OPE_URL . "?s=/Home/Script/exec&interval=" . I('get.type'));
        }
        echo $ret;
        write_log($ret, 'result/' . I('get.type') . '/');
        return true;
    }

}