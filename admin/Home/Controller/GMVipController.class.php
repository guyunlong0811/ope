<?php
namespace Home\Controller;

use Think\Controller;

class GMVipController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_vip';
        $this->vVipConfig = D('Static')->access('vip');
    }

    //显示列表
    public function index()
    {

        //查询所有信息
        $model = M('LVipSend');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $list[$key]['sid'] = $this->getServerName($value['sid']);
            $list[$key]['tid'] = $value['tid'] == 0 ? L('all_player') : $value['tid'];
            $list[$key]['vip'] = $this->vVipConfig[$value['vip_index']]['level'];
            $list[$key]['ctime'] = time2format($value['ctime']);
            $list[$key]['result'] = $value['result'] == 1 ? L('success') : L('fail');
        }

        //显示
        $this->vTable = $list;
        $this->display();//显示页面

    }

    //显示列表
    public function send()
    {

        if (!empty($_POST)) {

            $rs = true;
            $now = time();
            $behave = get_config('behave', array('gm', 'code',));

            //连接数据库
            $sid = I('post.sid');

            //计算人数
            $list = explode('#', I('post.tid'));
            $GVipWhere['tid'] = array('in', $list);

            //查询玩家当前数据
            $select = M()->db($sid, change_db_server($sid, 'master'))->table('g_vip')->field('`tid`,`index`,`score`')->where($GVipWhere)->select();

            //计算积分
            $GVipData['index'] = I('post.vip_index');
            $GVipData['score'] = $this->vVipConfig[I('post.vip_index')]['score'];
            $GVipData['utime'] = $now;

            //更新g_vip
            if (false === M()->db($sid)->table('g_vip')->where($GVipWhere)->save($GVipData)) {
                $rs = false;
            } else {

                $addAll = array();
                foreach ($select as $value) {
                    $add = array();
                    $add['tid'] = $value['tid'];
                    $add['behave'] = $behave;
                    $add['ctime'] = $now;
                    if ($value['index'] != I('post.vip_index')) {
                        $add['attr'] = 'index';
                        $add['value'] = I('post.vip_index') - $value['index'];
                        $add['before'] = $value['index'];
                        $add['after'] = I('post.vip_index');
                        $addAll[] = $add;
                    }
                    if ($value['score'] != $GVipData['score']) {
                        $add['attr'] = 'score';
                        $add['value'] = $GVipData['score'] - $value['score'];
                        $add['before'] = $value['score'];
                        $add['after'] = $GVipData['score'];
                        $addAll[] = $add;
                    }
                }

                //记录日志
                if (false === M()->db($sid)->table('l_vip')->addAll($addAll)) {
                    $rs = false;
                }

            }

            //返回结果
            if (!$rs) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

            //记录日志
            $log['sid'] = I('post.sid');
            $log['tid'] = I('post.tid');
            $log['vip_index'] = I('post.vip_index');
            $log['vip_score'] = $GVipData['score'];
            $log['result'] = $rs ? 1 : 0;
            D('LVipSend')->db(0)->CreateData($log);
        }

        $this->alert = get_error();
        $this->display();//显示页面

    }

}