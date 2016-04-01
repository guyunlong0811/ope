<?php
namespace Home\Controller;

use Think\Controller;

class GMMarqueeController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_marquee';
        $this->getScriptList();
    }

    //显示列表
    public function index()
    {

        //查询所有信息
        $model = M('LNotice');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $sid = explode('#', $value['sid']);
            foreach ($sid as $sval) {
                if (!empty($sval)) {
                    $list[$key]['sid'] = $this->getServerName($sval) . '&';
                }
            }
            $list[$key]['sid'] = substr($list[$key]['sid'], 0, -1);
            $list[$key]['endtime'] = $value['endtime'] > 0 ? time2format($value['endtime']) : L('none');
            $list[$key]['interval'] = $value['interval'] > 0 ? $value['interval'] : L('none');
            switch ($value['result']) {
                case '0':
                    $list[$key]['result_name'] = L('fail');
                    break;
                case '1':
                    $list[$key]['result_name'] = L('success');
                    break;
                case '2':
                    $list[$key]['result_name'] = L('canceled');
                    break;
            }
            $list[$key]['ctime'] = time2format($value['ctime']);
        }
//        dump($list);
        //显示
        $this->vTable = $list;
        $this->display();//显示页面

    }

    //发送公告
    public function send()
    {
        if (!empty($_POST)) {
            $flag = true;
            //发送邮件
            $params['send_tid'] = 0;
            $params['content'] = I('post.content');
            $params['level'] = I('post.level');
            if (I('post.duration') == 0) {
                $params['endtime'] = 0;
                $params['interval'] = 0;
            } else {
                $params['endtime'] = time() + (60 * I('post.duration'));
                $params['interval'] = I('post.interval');
            }
            $log = $params;
            foreach (I('post.server') as $value) {
                $log['sid'] = $value;
                if (!script_link($this->mScriptUrl[$this->mServerList[$value]['script']], $value, $params, 'Notice.send')) {
                    $flag = false;
                }
            }
            if ($flag) {
                $log['result'] = 1;
                C('G_ERROR', 'success');
            } else {
                $log['result'] = 0;
                C('G_ERROR', 'fail');
            }
            D('LNotice')->CreateData($log);
        }

        $this->alert = get_error();
        $this->display();//显示页面
    }

    //重新发送公告
    public function resend()
    {
        $flag = true;
        //获取公告信息
        $where['id'] = I('get.notice_id');
        $row = M('LNotice')->where($where)->find();

        //重发公告
        $sid = $row['sid'];
        $params['send_tid'] = $row['send_tid'];
        $params['content'] = $row['content'];
        $params['level'] = $row['level'];
        $params['endtime'] = $row['endtime'];
        $params['interval'] = $row['interval'];
        if (!script_link($this->mScriptUrl[$this->mServerList[$sid]['script']], $sid, $params, 'Notice.send')) {
            $flag = false;
        }

        if ($flag) {
            C('G_ERROR', 'success');
            $log['result'] = 1;
            D('LNotice')->UpdateData($log, $where);//更新日志
        } else {
            C('G_ERROR', 'fail');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&p=" . $this->pg;
        $this->display("Public:jump");//显示页面
    }

    //撤销公告
    public function cancel()
    {

        //获取公告信息
        $where['id'] = I('get.notice_id');
        $row = M('LNotice')->where($where)->find();

        //取消公告
        $sid = $row['sid'];
        $params['send_tid'] = $row['send_tid'];
        $params['content'] = $row['content'];
        $params['level'] = $row['level'];
        $params['endtime'] = $row['endtime'];
        $params['interval'] = $row['interval'];
        if (script_link($this->mScriptUrl[$this->mServerList[$sid]['script']], $sid, $params, 'Notice.cancel')) {
            C('G_ERROR', 'success');
            $log['result'] = 2;
            D('LNotice')->UpdateData($log, $where);//更新日志
        } else {
            C('G_ERROR', 'fail');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&p=" . $this->pg;
        $this->display("Public:jump");//显示页面

    }

}