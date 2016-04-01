<?php
namespace Home\Controller;

use Think\Controller;

class AdminController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'user';
        $this->vTitle = 'admin_list';
    }

    //显示列表
    public function index()
    {
        //查询所有信息
        $model = M('AdminAccount');
        $order = array('uid' => 'asc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $field = $value['status'] == 1 ? 'active' : 'banned';
            $list[$key]['status'] = L('status_' . $field);
        }
        //显示
        $this->vTable = $list;
        $this->display();//显示页面
    }

    //修改状态
    public function status()
    {

        //超级管理员不更改
        if (in_array(I('get.id'), get_config('super_admin')))
            C('G_ERROR', 'not_update_super');
        else {
            if (false === D('AdminAccount')->changeStatus(I('get.id'))) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&p=" . I('get.p');
        $this->display("Public:jump");//显示页面

    }

    //新增管理员
    public function add()
    {

        if (!empty($_POST)) {

            $data['username'] = I('post.username');
            $data['nickname'] = I('post.nickname');
            $data['password'] = I('post.password');
            $data['ip_limit'] = I('post.ip_limit');
            //修改密码
            if (I('post.password') != '') {
                if (I('post.password') != I('post.repassword')) {
                    C('G_ERROR', 'repassword_error');
                    goto end;
                }
                $data['password'] = I('post.password');
            }

            $row = D('AdminAccount')->CreateData($data);
            if ($row > 0)
                C('G_ERROR', 'success');

        }

        //显示
        end:
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //编辑管理员
    public function edit()
    {

        //修改
        if (!empty($_POST)) {

            $data['uid'] = I('post.uid');

            //修改昵称
            $data['nickname'] = I('post.nickname');

            //修改ip
            $data['ip_limit'] = I('post.ip_limit');

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
        $id = I('get.id');
        if (!empty($id)) {
            $where['uid'] = $id;
            $this->data = D('AdminAccount')->getAdminRow($where);
        }

        //显示
        end:
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //管理员权限
    public function permission()
    {
        //修改
        if (!empty($_POST)) {
            //超级管理员不更改
            if (in_array(I('post.uid'), get_config('super_admin'))) {
                C('G_ERROR', 'not_update_super');
            } else {
                //删除所有权限
                $where['uid'] = I('post.uid');
                if (false === D('AdminPermission')->DeleteList($where)) {
                    goto end;
                }
                //增加权限
                $all = array();
                foreach (I('post.permission') as $value) {
                    $arr = explode('.', $value);
                    $data['uid'] = I('post.uid');
                    $data['controller'] = $arr[0];
                    $data['action'] = $arr[1];
                    $all[] = $data;
                }
                if (false === D('AdminPermission')->CreateAllData($all)) {
                    goto end;
                }
                C('G_ERROR', 'success');
            }

        }

        //查询个人信息
        $id = I('get.id');
        if (!empty($id)) {
            $where['uid'] = $id;
            $this->data = D('AdminAccount')->getAdminRow($where);
        }

        //获取已经开放的权限
        if (!empty($id)) {
            $this->permission = D('AdminPermission')->getList($id);
        }

        //显示
        end:
        $this->super = in_array(I('get.id'), get_config('super_admin')) ? '1' : '0';
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //管理员服务器权限
    public function server()
    {
        if (!empty($_POST)) {

            //超级管理员不更改
            if (in_array(I('get.id'), get_config('super_admin'))) {
                C('G_ERROR', 'not_update_super');
            } else {

                //删除所有权限
                $where['uid'] = I('post.uid');
                if (false === D('AdminServer')->DeleteList($where)) {
                    goto end;
                }

                //全服
                if (I('post.server_type') == 'all') {
                    $data['uid'] = I('post.uid');
                    $data['server'] = 0;
                    if (false === D('AdminServer')->CreateData($data)) {
                        goto end;
                    }
                } else {
                    //增加权限
                    $serverList = I('post.server');
                    if (!empty($serverList)) {
                        $all = array();
                        foreach (I('post.server') as $key => $value) {
                            $data['uid'] = I('post.uid');
                            $data['server'] = $value;
                            $all[] = $data;
                        }
                        if (false === D('AdminServer')->CreateAllData($all)) {
                            goto end;
                        }
                    }
                }

                C('G_ERROR', 'success');

            }

        }

        end:
        $id = I('get.id');
        //查询个人信息
        if (!empty($id)) {
            $where['uid'] = $id;
            $this->data = D('AdminAccount')->getAdminRow($where);
        }

        //获取已经开放的服务器
        if (!empty($id)) {
            $server = D('AdminServer')->getList($id);
        }
        if (empty($server)) {
            $permission = array();
        } else {
            foreach ($server as $value) {
                if ($value == '0') {
                    $permission = '0';
                    break;
                }
            }
            if ($permission != '0') {
                $permission = $server;
            }
        }

        //显示
        $this->server = $permission;
        $this->super = in_array($id, get_config('super_admin')) ? '1' : '0';
        $this->alert = get_error();
        $this->display();//显示页面
    }

}