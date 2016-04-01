<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{

    private $mWarning = array(

        //战队等级
        'team_level' => array(
            1 => 'exceed_max_open_team_level',
        ),

        //水晶
        'team_diamond' => array(
            1 => 'get_diamond_over_max',
            2 => 'use_diamond_over_max',
        ),

        //金币
        'team_gold' => array(
            1 => 'get_gold_over_max',
            2 => 'use_gold_over_max',
        ),

        //体力
        'team_vality' => array(
            1 => 'get_vality_over_max',
            2 => 'use_vality_over_max',
            3 => 'current_vality_over_max',
        ),

        //伙伴魂力
        'partner_soul' => array(
            1 => 'get_soul_over_max',
        ),

        //荣誉值
        'arena_honour' => array(
            1 => 'get_honour_over_max',
            2 => 'use_honour_over_max',
        ),

        //公会资金
        'league_fund' => array(
            1 => 'get_fund_over_max',
        ),

        //贡献度
        'league_contribution' => array(
            1 => 'get_contribution_over_max',
        ),

    );

    public function _initialize()
    {

        parent::_initialize();
        $this->vIcon = 'home';
        $this->vController = 'Index';

    }

    public function index()
    {
        if (I('get.error')) {
            C('G_ERROR', I('get.error'));
            $this->alert = get_error();
        }

        /*$online = 0;//当前在线玩家数
        $nuu = 0;//昨日新增玩家数
        $dau = 0;//昨日活跃玩家数
        $pu = 0;//昨日付费玩家数
        foreach ($this->vServer as $key => $value) {
            $keyList = D('Predis')->cli('game', $key)->keys('u:*');
            $online += count($keyList);
//            $online += D('DataOnlineCount')->getCurrentCount($key);
            $nuu += D('DataStatisticsMid')->getYesterdayCount($key);
            $dau += D('DataStatisticsMid')->getYesterdayActiveCount($key);
            $pu += D('DataStatisticsMid')->getYesterdayPayUserCount($key);
        }

        $this->online = $online;
        $this->nuu = $nuu;
        $this->dau = $dau;
        $this->pu = $pu;*/

        //查询数据
        $where = "`status`=0 && ";
        foreach ($this->vServer as $key => $value) {
            $where .= "`sid`={$key} || ";
        }
        $where = substr($where, 0, -4);
        $select = M('LWarning')->where($where)->limit(1000)->select();

        //数据处理
        $warning = array();
        if (!empty($select)) {
            foreach ($select as $value) {
                $value['server_name'] = $this->getServerName($value['sid']);
                $value['type'] = $this->mWarning[$value['attr']][$value['type']];
                $value['ctime'] = time2format($value['ctime']);
                $warning[$value['attr']][] = $value;
            }
        }

        //显示
        $this->warning = $warning;
        $this->assign('vWarning', $this->mWarning);
        $this->display();

    }

    //修改状态
    public function status()
    {

        if (false === D('LWarning')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__;
        $this->display("Public:jump");//显示页面

    }

}