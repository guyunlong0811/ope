<?php
namespace Home\Controller;

use Think\Controller;

class GMBannedController extends BaseController
{

    private $mType;

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_banned';
        $this->mType[1] = L('banned');
        $this->mType[2] = L('silence');
    }

    //显示列表
    public function index()
    {
        if (!empty($_GET)) {

            $where['gid'] = C('GAME_ID');
            $where['sid'] = I('get.server_id');
            if (I('get.uid')) {
                $where['uid'] = I('get.uid');
            }
            $order = array('id' => 'desc',);
            $page = $this->page(D('UCBanned'), 'sql', $where);
            $list = D('UCBanned')->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
            //获取返回参数
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['sid'] = $this->getServerName($value['sid']);
                    $list[$key]['type'] = $this->mType[$value['type']];
                    $list[$key]['starttime'] = time2format($value['starttime']);
                    $list[$key]['endtime'] = time2format($value['endtime']);
                }
            }

        }

        //显示
        $this->list = $list;
        $this->display();//显示页面
    }

    //新增封禁
    public function add()
    {

        if (!empty($_POST)) {
            $now = time();
            $add['uid'] = I('post.uid');
            $add['gid'] = get_config('GAME_ID');
            $add['type'] = I('post.type');
            $add['starttime'] = $now;
            switch (I('post.time_type')) {
                case '1':
                    $times = 86400;
                    break;
                case '2':
                    $times = 3600;
                    break;
            }
            $add['endtime'] = $now + (I('post.time') * $times);
            $add['reason'] = I('post.reason');
            foreach (I('post.server') as $value) {
                $add['sid'] = $value;
                $addAll[] = $add;
            }

            //插入数据库
            if (false === D('UCBanned')->CreateAllData($addAll)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

        }

        //显示
        end:
        $this->alert = get_error();
        $this->type = $this->mType;
        $this->display();//显示页面
    }

    //解封
    public function open()
    {
        if (false === D('UCBanned')->open(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&p=" . $this->pg;
        $this->display("Public:jump");//显示页面
    }

}