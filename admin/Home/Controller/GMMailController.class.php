<?php
namespace Home\Controller;

use Think\Controller;

class GMMailController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_mail';
        $this->vFieldConfig = C('FIELD');
        $this->vItemConfig = D('Static')->access('item');
        $partner = D('Static')->access('partner_group');
        foreach ($partner as $key => $value) {
            $arr = current($value);
            $config[$key] = $arr['name'];
        }
        $this->vPartnerConfig = $config;
        $this->vEmblemConfig = D('Static')->access('emblem');
    }

    //显示列表
    public function index()
    {
        //查询所有信息
        $model = M('LMailSend');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $list[$key]['sid'] = $this->getServerName($value['sid']);
            $list[$key]['tid'] = $value['tid'] == 0 ? L('all_player') : $value['tid'];
            $list[$key]['type'] = $value['type'] == 1 ? L('notice_mail') : L('annex_mail');
            $list[$key]['send_time'] = time2format($value['ctime']);
            $list[$key]['result'] = $value['result'] == 1 ? L('success') : L('fail');
            for ($i = 1; $i <= 4; ++$i) {
                if ($list[$key]['item_' . $i . '_type'] == 0) {
                    $list[$key]['annex' . $i] = L($this->mBonusType[$value['item_' . $i . '_type']]);
                } else {
                    $list[$key]['annex' . $i] = L($this->mBonusType[$value['item_' . $i . '_type']]) . '-' . $this->getBonusId($value['item_' . $i . '_type'], $value['item_' . $i . '_value_1']) . ':' . $value['item_' . $i . '_value_2'] . ';';
                }
            }
        }
//        dump($list);
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

            //生成邮件内容
            $mail['type'] = (int)I('post.type');
            $mail['title'] = I('post.title');
            $mail['from'] = 'GM';
            $mail['des'] = I('post.des');
            if ($mail['type'] == 1) {//附件
                for ($i = 1; $i <= 4; ++$i) {
                    $mail['item_' . $i . '_type'] = 0;
                    $mail['item_' . $i . '_value_1'] = 0;
                    $mail['item_' . $i . '_value_2'] = 0;
                }
            } else {
                for ($i = 1; $i <= 4; ++$i) {
                    $mail['item_' . $i . '_type'] = I('post.annex_' . $i . '_type');
                    if ($mail['item_' . $i . '_type'] == '0') {
                        $mail['item_' . $i . '_value_1'] = 0;
                        $mail['item_' . $i . '_value_2'] = 0;
                    } else {
                        $mail['item_' . $i . '_value_1'] = I('post.annex_' . $i . '_value_1');
                        $mail['item_' . $i . '_value_2'] = I('post.annex_' . $i . '_value_2');
                    }
                }
            }
            $mail['open_script'] = '';
            $mail['behave'] = get_config('behave', array('gm', 'code',));
            $mail['ctime'] = $now;
            $mail['dtime'] = $now + (I('post.dtime') * 86400);

            //生成收件人
            if (I('post.server_id') == 0) {//全服&所有玩家
                //切换数据库
                foreach ($this->vServer as $sid => $value) {
                    //获取服务器所有的玩家
                    $list = M()->db($sid, change_db_server($sid, 'master'))->table('g_team')->where("`ctime`>0")->getField('tid', true);
                    //生成数据
                    if (!empty($list)) {
                        $i = 1;
                        $mailAll = array();
                        //开始事务
                        M()->db($sid)->execute('BEGIN');
                        foreach ($list as $tid) {
                            $mailAdd = $mail;
                            $mailAdd['tid'] = $tid;
                            $mailAll[] = $mailAdd;
                            //10W条数据执行一次
                            if ($i % 100000 == 0) {
                                if (false === M()->db($sid)->table('g_mail')->addAll($mailAll)) {
                                    $rs = false;
                                }
                                $mailAll = array();
                            }
                            ++$i;
                        }
                        //执行剩余数据
                        if (!empty($mailAll)) {
                            if (false === M()->db($sid)->table('g_mail')->addAll($mailAll)) {
                                $rs = false;
                            }
                            unset($mailAll);
                        }
                        //提交事务
                        if ($rs === false) {
                            M()->db($sid)->execute('ROLLBACK');
                        } else {
                            M()->db($sid)->execute('COMMIT');
                        }
                    }
                }
            } else {
                //获取数据库配置
                $sid = I('post.server_id');
                if (I('post.select_tid') == 1) {
                    //获取服务器所有的玩家
                    $list = M()->db($sid, change_db_server($sid, 'master'))->table('g_team')->where("`ctime`>0")->getField('tid', true);
                    //生成数据
                    if (!empty($list)) {
                        $i = 1;
                        $mailAll = array();
                        //开始事务
                        M()->db($sid)->execute('BEGIN');
                        foreach ($list as $tid) {
                            $mailAdd = $mail;
                            $mailAdd['tid'] = $tid;
                            $mailAll[] = $mailAdd;
                            //10W条数据执行一次
                            if ($i % 100000 == 0) {
                                if (false === M()->db($sid)->table('g_mail')->addAll($mailAll)) {
                                    $rs = false;
                                }
                                $mailAll = array();
                            }
                            ++$i;
                        }
                        //执行剩余数据
                        if (!empty($mailAll)) {
                            if (false === M()->db($sid)->table('g_mail')->addAll($mailAll)) {
                                $rs = false;
                            }
                            unset($mailAll);
                        }
                        //提交事务
                        if ($rs === false) {
                            M()->db($sid)->execute('ROLLBACK');
                        } else {
                            M()->db($sid)->execute('COMMIT');
                        }
                    }
                } else {
                    //处理数据
                    $list = explode('#', I('post.tid'));
                    if (!empty($list)) {
                        $mailAll = array();
                        foreach ($list as $tid) {
                            if (!empty($tid)) {
                                $mailAdd = $mail;
                                $mailAdd['tid'] = trim($tid);
                                $mailAll[] = $mailAdd;
                            }
                        }
                    }
                    //执行数据
                    if (!empty($mailAll)) {
                        if (false === M()->db(1, change_db_server($sid, 'master'))->table('g_mail')->addAll($mailAll)) {
                            $rs = false;
                        }
                        unset($mailAll);
                    }
                }

            }

            //返回结果
            if (!$rs) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

            //记录日志
            $log = $mail;
            $log['sid'] = I('post.server_id');
            $log['tid'] = !I('post.tid') ? 0 : I('post.tid');
            $log['result'] = $rs ? 1 : 0;
            D('LMailSend')->db(0)->CreateData($log);
        }

        $this->alert = get_error();
        $this->assign('bonus_type', $this->mBonusType);
        $this->display();//显示页面

    }

}