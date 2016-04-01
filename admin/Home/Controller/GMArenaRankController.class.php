<?php
namespace Home\Controller;

use Think\Controller;

class GMArenaRankController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_arena_rank';
    }

    //显示列表
    public function index()
    {
        //查询所有信息
        $model = M('LArenaRank');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $list[$key]['server'] = $this->getServerName($value['sid']);
            $list[$key]['tid'] = $value['tid'] == 0 ? L('all_player') : $value['tid'];
            $list[$key]['ctime'] = time2format($value['ctime']);
        }

        //显示
        $this->vTable = $list;
        $this->display();//显示页面

    }

    //显示列表
    public function exchange()
    {

        if (!empty($_POST)) {

            $rs = false;

            //获取玩家当前排名
            $sid = I('post.server_id');
            $tid = I('post.tid');
            $rank = I('post.rank');
            $rankBefore = M()->db($sid, change_db_server($sid, 'master'))->table('g_arena')->where("`tid`='{$tid}'")->getField('rank');

            //如果当前排名已经在调整之后则不操作
            if($rank < $rankBefore){
                $rs = true;
            }else{

                //查询给定名次后的第一位机器人的tid
                $robotTid = M()->db($sid)->table('g_arena')->where("`rank`>'{$rank}'")->order('`rank` asc')->getField('tid');

                //对换两名玩家的排名
                $sql = "update `g_arena` as `a`,`g_arena` as `b` set `a`.`rank` = `b`.`rank`, `b`.`rank` = `a`.`rank` where `a`.`tid`='{$robotTid}' && `b`.`tid`='{$tid}' && `b`.`rank` < `a`.`rank`;";
                $row = M()->db($sid)->execute($sql);
                if ($row > 0) {//发生改变
                    $rs = true;
                }

            }

            //返回结果
            if (!$rs) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');

                //查询最新排名
                $rankAfter = M()->db($sid, change_db_server($sid, 'master'))->table('g_arena')->where("`tid`='{$tid}'")->getField('rank');

                //记录日志
                $log['sid'] = I('post.server_id');
                $log['tid'] = !I('post.tid') ? 0 : I('post.tid');
                $log['before'] = $rankBefore;
                $log['after'] = $rankAfter;
                D('LArenaRank')->db(0)->CreateData($log);

            }

        }

        $this->alert = get_error();
        $this->display();//显示页面

    }

}