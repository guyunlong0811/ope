<?php
namespace Home\Controller;

use Think\Controller;

class UserInfoController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'group';
        $this->vTitle = 'user_info';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $this->server_id = I('get.server_id');
            $where = array();
            if (I('get.team_id')) {
                $where['tid'] = I('get.team_id');
            }

            if (I('get.channel_uid')) {
                $list = D('UCAccount')->where("`channel_uid`='" . I('get.channel_uid') . "'")->getField('uid', true);
                if (!empty($list)) {
                    $where['uid'] = array('in', $list);
                }
            }

            if (I('get.user_id')) {
                $where['uid'] = I('get.user_id');
            }

            if (I('get.nickname')) {
                $where['nickname'] = array('LIKE', '%' . I('get.nickname') . '%');
                I('get.nickname');
            }

            if (empty($where)) {
                $list = array();
            } else {

                $dbConfig = change_db_server(I('get.server_id'), 'master');
                $field = array('tid', 'uid', 'nickname', 'level', 'exp', 'diamond_pay', 'diamond_free', 'gold', 'vality', 'vality_utime', 'guide_skip', 'ctime', 'last_login_time',);
                $list = M()->db(I('get.server_id'), $dbConfig)->table('g_team')->field($field)->where($where)->select();
                if (!empty($list)) {
                    foreach ($list as $key => $value) {
                        $list[$key]['ctime'] = time2format($value['ctime']);
                        $list[$key]['last_login_time'] = time2format($value['last_login_time']);
                    }
                } else {
                    $list = array();
                }

            }

            $this->vTable = $list;
        }

        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //详情页
    public function detail()
    {
        if (!empty($_GET)) {
            $dbConfig = change_db_server(I('get.server_id'), 'master');

            //获取游戏信息
            $sql = "select `gt`.`tid`,`gt`.`uid`,`gt`.`role_id`,`gt`.`channel_id` as `channel`,`gt`.`nickname`,`gt`.`level`,`gt`.`exp`,`gt`.`diamond_pay`,`gt`.`diamond_free`,`gt`.`gold`,`gt`.`vality`,`gt`.`vality_utime`,`gt`.`skill_point`,`gt`.`skill_point_utime`,`gt`.`ctime`,`gt`.`last_login_time`,`gv`.`index` as `vip`,`gv`.`score`,`gv`.`pay_valid`,`gv`.`pay`,`gv`.`utime` as `vip_utime`,count(`gp`.`group`) as `partner_count` from `g_team` as `gt`,`g_vip` as `gv`,`g_partner` as `gp` where `gt`.`tid`='" . I('get.tid') . "' && `gt`.`tid`=`gv`.`tid` && `gt`.`tid`=`gp`.`tid` limit 1";
            $row = M()->db(1, $dbConfig)->query($sql);
            $row = $row[0];

            //获取用户UC信息
            $field = array('cip', 'channel_uid');
            $ucInfo = D('UCAccount')->field($field)->where("`uid`='{$row['uid']}'")->find();

            if (!empty($row)) {

                $row = array_merge($row, $ucInfo);

                //渠道名
                $row['channel'] = D('UCChannel')->where("`channel_id`='{$row['channel']}'")->getField('name');

                //vip等级
                $row['vip'] = $row['vip'] % 1000;

                //查询会员情况
                $now = time();
                $member = M()->db(I('get.server_id'), $dbConfig)->table('g_member')->where("`tid`='" . I('get.tid') . "' && (`expire`='0' || `expire`>='{$now}')")->getField('type');
                if (!empty($memberList)) {
                    $row['member'] = substr($member, 0, -2);
                } else {
                    $row['member'] = '/';
                }

                //时间格式
                $row['skill_point_utime'] = $row['skill_point_utime'] == 0 ? '/' : time2format($row['skill_point_utime']);
                $row['vality_utime'] = $row['vality_utime'] == 0 ? '/' : time2format($row['vality_utime']);
                $row['ctime'] = time2format($row['ctime']);
                $row['last_login_time'] = time2format($row['last_login_time']);
                $row['vip_utime'] = $row['vip_utime'] == 0 ? '/' : time2format($row['vip_utime']);
                $this->vTable = $row;
            } else {
                $this->redirect('Home/UserInfoCheck/index');
            }

            //显示
            $this->alert = get_error();
            $this->display();//显示页面

        } else {
            $this->redirect('Home/UserInfo/index');
        }

    }

    //跳过新手引导
    public function skipGuide()
    {
        if (IS_AJAX) {
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $where['tid'] = I('get.tid');
            if (false === M()->db(I('get.server_id'), $dbConfig)->table('g_team')->where($where)->setField('guide_skip', 1)) {
                echo 'fail';
            } else {
                echo 'ok';
            }
        }
        return;
    }

    //强制下线
    public function kick()
    {
        if (IS_AJAX) {
            $token = D('Predis')->cli('game', I('get.server_id'))->del('u:' . I('get.uid'));
            D('Predis')->cli('game', I('get.server_id'))->del('u:' . I('get.uid'));
            D('Predis')->cli('game', I('get.server_id'))->del('t:' . $token);
            echo 'ok';
        }
        return;
    }

}