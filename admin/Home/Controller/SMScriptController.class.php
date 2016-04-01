<?php
namespace Home\Controller;

use Think\Controller;

class SMScriptController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'cloud';
        $this->vTitle = 'sm_script';
    }

    //显示列表
    public function index()
    {
        //查询所有信息
        $list = M('ServerScript')->select();
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
        if (false === D('ServerScript')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面

    }

    //删除服务器
    public function delete()
    {
        if (false === D('ServerScript')->DeleteData(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面

    }

    //新增管理员
    public function add()
    {
        if (!empty($_POST)) {
            $add['name'] = I('post.name');
            $add['url'] = 'http://' . str_replace('http://', '', I('post.url'));
            if (false === $id = D('ServerScript')->CreateData($add)) {
                C('G_ERROR', 'fail');
            } else {
                $host = $add['url'] . '?c=Index&a=setIdent';
                $post['ident'] = $id;
                $post['token'] = md5($id . C('CACHE_VERIFY'));
                $rs = curl_link($host, 'post', $post);
                C('G_ERROR', $rs);
            }
        }
        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}