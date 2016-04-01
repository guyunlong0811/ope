<?php
namespace Home\Controller;

use Think\Controller;

class GMNoticeController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_notice';
        $this->vChannel = D('UCChannel')->getAll();//查询渠道信息
    }

    //显示列表
    public function index()
    {
        $model = D('DNotice');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
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

            $list[$key]['hot'] = $value['hot'] == 1 ? 'yes' : 'no';
            $list[$key]['starttime'] = time2format($value['starttime']);
            $list[$key]['ctime'] = time2format($value['ctime']);
            $list[$key]['endtime'] = $value['endtime'] == 0 ? L('none') : time2format($value['endtime']);
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
        if (false === D('DNotice')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_notice');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面

    }

    //删除
    public function delete()
    {
        if (false === D('DNotice')->DeleteData(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_notice');
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

            $add['tab'] = I('post.tab');
            $add['title'] = I('post.title');
            $add['hot'] = I('post.hot');
            $add['priority'] = I('post.priority');
            //时间
            if (I('post.time_type') == '0') {
                $add['starttime'] = strtotime(I('post.starttime0'));
                $save['endtime'] = 0;
            } else {
                $add['starttime'] = strtotime(I('post.starttime1'));
                $add['endtime'] = strtotime(I('post.endtime1'));
            }
            //内容
            $content = array();
            for ($i = 1; $i <= I('post.sub'); ++$i) {
                $arr = array();
                $arr['sub_title'] = I('post.subtitle' . $i);
                $arr['sub_content'] = I('post.content' . $i);
                $content[] = $arr;
            }
            $add['content'] = json_encode($content);
            if (false === D('DNotice')->CreateData($add)) {
                C('G_ERROR', D('DNotice')->getError());
            } else {
                C('G_ERROR', 'success');
            }
        }
        //显示
        $time['starttime'] = time2format();
        $time['endtime'] = time2format(strtotime('+15 days'));
        $this->vTime = $time;
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //编辑
    public function edit()
    {
        $index = I('get.id');
        if (!empty($_POST)) {

            //服务器
            if (I('post.server_type') == 'all') {
                $save['server'] = 0;
            } else {
                $server = '';
                foreach (I('post.server') as $value) {
                    $server .= $value . '#';
                }
                $save['server'] = substr($server, 0, -1);
            }

            //渠道
            if (I('post.channel_type') == 'all') {
                $save['channel'] = 0;
            } else {
                $channel = '';
                foreach (I('post.channel') as $value) {
                    $channel .= $value . '#';
                }
                $save['channel'] = substr($channel, 0, -1);
            }

            $save['tab'] = I('post.tab');
            $save['title'] = I('post.title');
            $save['hot'] = I('post.hot');
            $save['priority'] = I('post.priority');
            //时间
            if (I('post.time_type') == '0') {
                $save['starttime'] = strtotime(I('post.starttime0'));
                $save['endtime'] = 0;
            } else {
                $save['starttime'] = strtotime(I('post.starttime1'));
                $save['endtime'] = strtotime(I('post.endtime1'));
            }
            //内容
            $content = array();
            for ($i = 1; $i <= I('post.sub'); ++$i) {
                $arr = array();
                $arr['sub_title'] = I('post.subtitle' . $i);
                $arr['sub_content'] = I('post.content' . $i);
                $content[] = $arr;
            }
            $save['content'] = json_encode($content);
            $where['index'] = I('post.index');
            if (false === D('DNotice')->UpdateData($save, $where)) {
                C('G_ERROR', D('DNotice')->getError());
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_notice');
            }
            $index = I('post.index');
        }

        //处理当前数据
        $data = D('DNotice')->where("`index`='{$index}'")->find();
        $data['content'] = json_decode($data['content'], true);
        foreach ($data['content'] as $key => $value) {
            $data['content'][$key]['sub_title'] = $str = str_replace(array("\r\n", "\r", "\n"), '\n', $value['sub_title']);
            $data['content'][$key]['sub_content'] = $str = str_replace(array("\r\n", "\r", "\n"), '\n', $value['sub_content']);
        }

        $data['starttime'] = time2format($data['starttime']);
        $data['endtime'] = $data['endtime'] == 0 ? 0 : time2format($data['endtime']);

        //显示
        $this->vData = $data;
        $time['starttime'] = time2format();
        $time['endtime'] = time2format(strtotime('+15 days'));
        $this->vTime = $time;
        $this->alert = get_error();
        $this->display();//显示页面
    }

}