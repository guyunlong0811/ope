<?php
namespace Home\Controller;

use Think\Controller;

class AdminInfoController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'user';
    }

    //编辑管理员信息
    public function index()
    {

        //修改
        if (!empty($_POST)) {

            $data['uid'] = $_SESSION['uid'];

            //修改昵称
            $data['nickname'] = I('post.nickname');

            //修改密码
            if (I('post.password') != '') {
                if (I('post.password') != I('post.repassword')) {
                    C('G_ERROR', 'repassword_error');
                goto end;
            }
                $data['password'] = I('post.password');
            }
            $row = D('AdminAccount')->UpdateData($data);
            if ($row > 0)
                C('G_ERROR', 'success');
        }

        //查询个人信息
        if (!empty($_SESSION['uid'])) {
            $where['uid'] = $_SESSION['uid'];
            $this->data = D('AdminAccount')->getAdminRow($where);
        }

        //显示
        end:
        $this->alert = get_error();
        $this->display();//显示页面

    }

}