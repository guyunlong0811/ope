<?php
namespace Home\Controller;

use Think\Controller;

class SMChannelController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'cloud';
        $this->vTitle = 'sm_channel';
        $this->vSys = array(10000, 15000, 20000, 30000);
    }

    //显示列表
    public function index()
    {

        //查询所有信息
        $where['gid'] = C('GAME_ID');
        $list = D('UCChannel')->field('gid', true)->where($where)->select();

        foreach ($list as $key => $value) {
            $field = $value['status'] == 1 ? 'active' : 'banned';
            $list[$key]['status_name'] = L('status_' . $field);
        }

        //显示
        $this->vTable = $list;
        $this->display();//显示页面
    }

    //修改状态
    public function status()
    {
        if (false === D('UCChannel')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面

    }

    //新增
    public function add()
    {

        if (!empty($_POST)) {

            //计算新增渠道ID
            foreach($this->vSys as $key => $value){
                if(I('post.system') == $value){
                    $key = $key + 1;
                    $where['channel_id'] = array('between', array(I('post.system'), $this->vSys[$key]));
                    break;
                }
            }
            $channelId = D('UCChannel')->where($where)->max('channel_id');
            ++$channelId;

            //插入数据
            $add['gid'] = C('GAME_ID');
            $add['channel_id'] = $channelId;
            $add['name'] = I('post.name');
            $add['code'] = I('post.code');
            $add['callback'] = I('post.callback');
            $add['status'] = 0;
            if (false === D('UCChannel')->CreateData($add)) {
                $error = D('UCChannel')->getError();
                if(empty($error)){
                    $error = 'fail';
                }
                C('G_ERROR', $error);
            } else {
                C('G_ERROR', 'success');
            }

        }

        //显示
        end:
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //编辑
    public function edit()
    {

        //修改
        if (!empty($_POST)) {

            $where['gid'] = C('GAME_ID');
            $where['channel_id'] = array('neq', I('post.id'));
            $where['name'] = I('post.name');
            $count = D('UCChannel')->where($where)->count();

            if($count > 0){
                C('G_ERROR', 'channel_name_existed');
            }else{
                $where = array();
                $where['gid'] = C('GAME_ID');
                $where['channel_id'] = I('post.id');
                $data['name'] = I('post.name');
                $data['code'] = I('post.code');
                $data['callback'] = I('post.callback');
                if (false === D('UCChannel')->UpdateData($data, $where)) {
                    C('G_ERROR', 'fail');
                } else {
                    C('G_ERROR', 'success');
                }
            }

        }

        //查询s信息
        $id = I('get.id');
        if (!empty($id)) {
            $where['gid'] = C('GAME_ID');
            $where['channel_id'] = I('get.id');
            $this->vData = D('UCChannel')->where($where)->find();
        }

        //显示
        end:
        $this->alert = get_error();
        $this->display();//显示页面
    }

}