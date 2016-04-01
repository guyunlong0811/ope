<?php
namespace Home\Controller;

use Think\Controller;

class GMActivityTimeController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_activity_time';
        $this->vChannel = D('UCChannel')->getAll();//查询渠道信息
        $this->selectChannel();

        $list = array();
        $select = D('Static')->access('activity_time');//查询渠道信息
        if (!empty($select)) {
            foreach ($select as $value) {
                $list[$value['index']] = $value['name'];
            }
        }
        $this->vInstance = $list;
    }

    //显示列表
    public function index()
    {

        //服务器
        $serverId  = I('get.server_id');
        if (!empty($serverId) && $serverId >= 0) {
            $where['server'] = $serverId;
        }
        //渠道
        $channelId = I('get.channel_id');
        if (!empty($channelId) && $channelId >= 0) {
            $where['channel'] = $channelId;
        }
        //模块
        $instance = I('get.instance');
        if (!empty($instance) && $instance != '0') {
            $where['instance'] = $instance;
        }

        $model = D('DActivityTime');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model, 'sql', $where);
        $list = $model->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
        foreach ($list as $key => $value) {

            //服务器
            $list[$key]['server'] = $value['server'];
            if ($value['server'] == '0') {
                $list[$key]['server_name'] = L('all_server');
            } else {
                $arrServer = explode('#', $value['server']);
                $list[$key]['server_name'] = '';
                foreach ($arrServer as $sval) {
                    if (!empty($sval)) {
                        $list[$key]['server_name'] .= 'S' . $sval . '&';
                    }
                }
                $list[$key]['server_name'] = substr($list[$key]['server_name'], 0, -1);
            }

            //渠道
            $list[$key]['channel'] = $value['channel'];
            if ($value['channel'] == '0') {
                $list[$key]['channel_name'] = L('all_channel');
            } else {
                $arrChannel = explode('#', $value['channel']);
                $list[$key]['channel_name'] = '';
                foreach ($arrChannel as $cval) {
                    if (!empty($cval)) {
                        $list[$key]['channel_name'] .= $this->vChannel[$cval] . '&';
                    }
                }
                $list[$key]['channel_name'] = substr($list[$key]['channel_name'], 0, -1);
            }

            $list[$key]['instance_name'] = $this->vInstance[$value['instance']];
            $list[$key]['starttime'] = time2format($value['starttime']);
            $list[$key]['endtime'] = time2format($value['endtime']);
            $list[$key]['status'] = $value['status'] == '1' ? L('status_active') : L('status_banned');
        }

        //显示
        $this->vTable = $list;
        $this->assign('vInstance', $this->vInstance);
        $this->display();//显示页面
    }

    //修改状态
    public function status()
    {
        if (false === D('DActivityTime')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_activity_time');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&channel_id=" . I('get.channel_id') . "&module=" . I('get.module') . "&p={$this->pg}";
        $this->display("Public:jump");//显示页面
    }

    //删除
    public function delete()
    {
        if (false === D('DActivityTime')->DeleteData(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_activity_time');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&channel_id=" . I('get.channel_id') . "&module=" . I('get.module') . "&p={$this->pg}";
        $this->display("Public:jump");//显示页面
    }

    //新增
    public function add()
    {

        if (!empty($_POST)) {

            //服务器
            if (I('post.server_type') == 'all') {
                $add['server'] = 0;
            } else {
                $server = '';
                foreach (I('post.server') as $value) {
                    $server .= $value . '#';
                }
                $add['server'] = substr($server, 0, -1);
            }

            //渠道
            if (I('post.channel_type') == 'all') {
                $add['channel'] = 0;
            } else {
                $channel = '';
                foreach (I('post.channel') as $value) {
                    $channel .= $value . '#';
                }
                $add['channel'] = substr($channel, 0, -1);
            }

            $add['instance'] = I('post.instance');
            $add['starttime'] = strtotime(I('post.starttime'));
            $add['endtime'] = strtotime(I('post.endtime'));
            $add['ctime'] = time();

            if (false === D('DActivityTime')->CreateData($add)) {
                C('G_ERROR', D('DActivityTime')->getError());
            } else {
                C('G_ERROR', 'success');
            }
        }
        //显示
        $time['starttime'] = time2format();
        $time['endtime'] = time2format(strtotime('+15 days'));
        $this->vTime = $time;
        $this->assign('vInstance', $this->vInstance);
        $this->alert = get_error();
        $this->display();//显示页面
    }

}