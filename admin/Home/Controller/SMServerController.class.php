<?php
namespace Home\Controller;

use Think\Controller;

class SMServerController extends BInitController
{

    const REDIS_MAX_DB = 15;//从0开始
    const PRE_CREATE_NUM = 10000;//预创建帐号数
    const MERGE_SQL_COUNT = 10000;//合服操作数据库条目数
    private $mDBPath;

    //所有游戏库表
    static $sTableAll = array(

        'game' => array(
            'g_achievement',
            'g_arena',
            'g_babel',
            'g_count',
            'g_daily_register',
            'g_device',
            'g_emblem',
            'g_equip',
            'g_event',
            'g_fate',
            'g_friend',
            'g_guide',
            'g_instance_0',
            'g_instance_1',
            'g_instance_2',
            'g_instance_3',
            'g_instance_4',
            'g_instance_5',
            'g_instance_6',
            'g_instance_7',
            'g_instance_8',
            'g_instance_9',
            'g_item_0',
            'g_item_1',
            'g_item_2',
            'g_item_3',
            'g_item_4',
            'g_item_5',
            'g_item_6',
            'g_item_7',
            'g_item_8',
            'g_item_9',
            'g_league',
            'g_league_arena_rank',
            'g_league_arena_reg',
            'g_league_rank',
            'g_league_team',
            'g_level_bonus',
            'g_life_death_battle',
            'g_mail',
            'g_map_star_bonus',
            'g_member',
            'g_novice_login',
            'g_order',
            'g_params',
            'g_partner',
            'g_partner_quest',
            'g_pay',
            'g_pray',
            'g_pray_timed',
            'g_quest',
            'g_shop',
            'g_shop_arena',
            'g_shop_hero',
            'g_shop_league',
            'g_shop_mystery',
            'g_shop_normal',
            'g_shop_vip',
            'g_shop_vip_daily',
            'g_star',
            'g_team',
            'g_vip',
            'g_vip_bonus',
        ),

        'log' => array(
            'l_abyss',
            'l_activity_complete',
            'l_arena',
            'l_arena_battle',
            'l_cheat',
            'l_dynamic',
            'l_emblem',
            'l_equip_strengthen',
            'l_equip_upgrade',
            'l_iap',
            'l_instance',
            'l_item',
            'l_league',
            'l_league_battle',
            'l_league_battle_result',
            'l_league_dismiss',
            'l_league_donate',
            'l_league_food',
            'l_league_team',
            'l_league_team_member',
            'l_login',
            'l_mail',
            'l_member',
            'l_order',
            'l_partner',
            'l_pray',
            'l_share',
            'l_shop',
            'l_star',
            'l_team',
            'l_vip',
        ),

        'truncate' => array(
            't_daily_activity_bonus',
            't_daily_count',
            't_daily_event',
            't_daily_instance',
            't_daily_league',
            't_daily_online_bonus',
            't_daily_quest',
            't_daily_shop',
            't_specify_event',
            't_weekly_event',
        ),


    );

    private $mServerType;
    private $tidStart = 100000000;
    private $nickname = array();

    public function _initialize()
    {
        parent::_initialize();
        ini_set("display_errors", 1);
        error_reporting(E_ALL);
        $this->vIcon = 'cloud';
        $this->vTitle = 'sm_server';

        //加载模块
        $this->selectChannel();
        $this->mDBPath = COMMON_PATH . 'Common/db/';
        $this->mServerType = array(
            -2 => L('server_close_reg'),
            -1 => L('server_maintenance'),
            0 => L('server_normal'),
            1 => L('server_hot'),
            2 => L('server_recommend'),
            3 => L('server_new'),
        );
        $this->getScriptList();
    }

    //显示列表
    public function index()
    {

        if (I('get.search_channel_id') && I('get.search_channel_id') != '0') {
            $where['gid'] = C('GAME_ID');
            $where['channel_id'] = I('get.search_channel_id');
            $logicIdList = D('UCServer')->getLogicId($where);
            $where = array();
            $where['gid'] = C('GAME_ID');
            $where['id'] = array('in', $logicIdList);
            $logicList = D('UCLogic')->getAll(C('GAME_ID'), $where);
        } else {
            //查询所有信息
            $logicList = D('UCLogic')->getAll(C('GAME_ID'));
        }

        foreach ($logicList as $key => $value) {
            $logicList[$key]['server_name'] = $this->getServerName($value['sid']);
            $field = $value['activation'] == 1 ? 'yes' : 'no';
            $logicList[$key]['activation'] = L($field);
            $field = $value['status'] == 1 ? 'active' : 'banned';
            $logicList[$key]['status_name'] = L('status_' . $field);
        }

        //显示
        $this->vTable = $logicList;
        $this->vChannelId = I('get.search_channel_id') ? I('get.search_channel_id') : 0;
        $this->assign('vType', $this->mServerType);
        $this->display();//显示页面
    }

    //显示列表
    public function channel()
    {

        //查询渠道信息
        $channelList = D('UCChannel')->getAll();

        //查询所有信息
        $serverList = D('UCServer')->where("`logic_id`='" . I('get.id') . "'")->getAll();
        foreach ($serverList as $key => $value) {
            $serverList[$key]['channel'] = $channelList[$value['channel_id']];
            $field = $value['status'] == 1 ? 'active' : 'banned';
            $serverList[$key]['status'] = L('status_' . $field);
        }

        //显示
        $this->list = $serverList;
        $this->assign('vType', $this->mServerType);
        $this->display();//显示页面
    }

    //全服维护
    public function maintainAllServer()
    {

        $where['gid'] = C('GAME_ID');
        if (I('get.search_channel_id') != '0') {
            $where['channel_id'] = I('get.search_channel_id');
        }
        $logicIdList = D('UCServer')->getLogicId($where);
        unset($where);
        $where['id'] = array('in', $logicIdList);
        $serverList = D('UCLogic')->getOpenServer($where);
        foreach ($serverList as $value) {
            $this->flushRedisData($value['sid']);
            $list[] = $value['id'];
        }
        D('UCServer')->changeTypeAll($list, -1, I('get.search_channel_id'));
        $this->clearApc('server');

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面
    }

    //全服开启(只开启维护状态)
    public function reopenAllServer()
    {

        $where = array();
        if (!empty($_GET) && I('get.search_channel_id') != '0') {
            $where['gid'] = C('GAME_ID');
            $where['channel_id'] = I('get.search_channel_id');
            $logicIdList = D('UCServer')->getLogicId($where);
            unset($where);
            $where['id'] = array('in', $logicIdList);
        }

        $serverList = D('UCLogic')->getOpenServer($where);
        foreach ($serverList as $value) {
            $list[] = $value['id'];
        }
        D('UCServer')->changeTypeAll($list, 0, I('get.search_channel_id'));
        $this->clearApc('server');

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面
    }

    //修改逻辑服务器状态
    public function status()
    {

        if (false === D('UCLogic')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
            //查询服务器信息
            $serverInfo = D('UCLogic')->getRow(I('get.id'));
            //清除服务器redis
            $this->flushRedisData($serverInfo['sid']);

            $this->clearApc('server');
            //走10分钟脚本
            $url = $this->mScriptUrl[$this->mServerList[$serverInfo['sid']]['script']] . '?method=ExecMin10.index&sid=' . $serverInfo['sid'];
            curl_link($url);
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面

    }

    //强制将服务器玩家踢下线
    public function kick()
    {
        //清除服务器redis
        $list1 = D('Predis')->cli('game', I('get.server_id'))->keys("u:*");
        $list2 = D('Predis')->cli('game', I('get.server_id'))->keys("s:*");
        $list3 = D('Predis')->cli('game', I('get.server_id'))->keys("sn:*");
        if (!empty($list1)) {
            D('Predis')->cli('game', I('get.server_id'))->del($list1);
        }
        if (!empty($list2)) {
            D('Predis')->cli('game', I('get.server_id'))->del($list2);
        }
        if (!empty($list3)) {
            D('Predis')->cli('game', I('get.server_id'))->del($list3);
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面
    }

    //清除服务器redis
    private function flushRedisData($sid)
    {

        D('Predis')->cli('game', $sid)->flushdb();
        D('Predis')->cli('social', $sid)->flushdb();
        D('Predis')->cli('fight', $sid)->flushdb();

        //重新设置预创建帐号数量
        $dbConfig = change_db_server($sid, 'master');
        M()->db($sid, $dbConfig);
        $tid = M()->db($sid)->table('g_team')->where("`uid`='0' && `tid` > '1000000'")->min('tid');
        if (empty($tid)) {
            $pre = 10000;
        } else {
            $pre = $tid % 10000 - 1;
        }

        //设置预创建帐号数量
        D('Predis')->cli('game', $sid)->set('pre', $pre);

        //竞技场排名
        $arena_max_rank = M()->db($sid)->table('g_arena')->table('g_arena')->max('rank');
        $arena_max_rank = $arena_max_rank ? $arena_max_rank : 0;
        D('Predis')->cli('game', $sid)->set('arena_max_rank', $arena_max_rank);

        //所有排行榜都设为空
        D('Predis')->cli('game', $sid)->hset('rank', 'team', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'vip', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'star', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'arena_win_continuous', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'league', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'combo', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'combo_today', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'force', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'force_top', '[]');
        D('Predis')->cli('game', $sid)->hset('rank', 'achievement', '[]');

        return;
    }

    //修改渠道状态
    public function channelStatus()
    {

        if (false === D('UCServer')->changeStatus(I('get.channel_id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
            $this->clearApc('server');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/channel&server_id=" . I('get.server_id') . "&search_channel_id=" . I('get.search_channel_id') . "&id=" . I('get.id') . "";
        $this->display("Public:jump");//显示页面

    }

    //修改服务器是否需要激活吗
    public function activation()
    {

        if (false === D('UCLogic')->changeActivation(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
            $this->clearApc('server');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面

    }

    //修改服务器类型
    public function type()
    {

        if (false === D('UCServer')->changeType(I('get.channel_id'), I('get.type'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
            $this->clearApc('server');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/channel&server_id=" . I('get.server_id') . "&search_channel_id=" . I('get.search_channel_id') . "&id=" . I('get.id') . "";
        $this->display("Public:jump");//显示页面

    }

    //开新服
    public function open()
    {

        if (!empty($_POST)) {

            //游戏ID
            $gid = C('GAME_ID');
            $addLogic['gid'] = $gid;

            //检查平台网关是否重复
            if (I('post.platform_sid') > 0) {
                $wherePTSid = $addLogic;
                $wherePTSid['platform_sid'] = I('post.platform_sid');
                $count = D('UCLogic')->where($wherePTSid)->count();
                if ($count > 0) {
                    C('G_ERROR', 'platform_sid_exist');
                    goto end;
                }
            }

            //服务器ID
            if(I('post.sid') == '0'){
                $sid = D('UCLogic')->where($addLogic)->max('sid');
                $sid = $sid ? $sid : C('SERVER_START');
                ++$sid;
            }else{
                $whereSid = $addLogic;
                $whereSid['sid'] = I('post.sid');
                $count = D('UCLogic')->where($whereSid)->count();
                if ($count > 0) {
                    C('G_ERROR', 'server_id_exist');
                    goto end;
                }
                $sid = I('post.sid');
            }
            $addLogic['sid'] = $sid;

            //服务器是否需要激活码注册
            $addLogic['activation'] = 0;

            //数据库配置
            if (I('post.db_type') == 'old') {
                $info = D('UCLogic')->where("`gid`='{$gid}' && `sid`='" . I('post.db_select') . "'")->find();
                $addLogic['db_m_host'] = $info['db_m_host'];
                $addLogic['db_m_user'] = $info['db_m_user'];
                $addLogic['db_m_pwd'] = $info['db_m_pwd'];
                $addLogic['db_m_port'] = $info['db_m_port'];
                $addLogic['db_s_host'] = $info['db_s_host'];
                $addLogic['db_s_user'] = $info['db_s_user'];
                $addLogic['db_s_pwd'] = $info['db_s_pwd'];
                $addLogic['db_s_port'] = $info['db_s_port'];
            } else {
                $addLogic['db_m_host'] = I('post.db_master_host');
                $addLogic['db_m_user'] = I('post.db_master_user');
                $addLogic['db_m_pwd'] = I('post.db_master_pwd');
                $addLogic['db_m_port'] = I('post.db_master_port');
                $addLogic['db_s_host'] = I('post.db_slave_host');
                $addLogic['db_s_user'] = I('post.db_slave_user');
                $addLogic['db_s_pwd'] = I('post.db_slave_pwd');
                $addLogic['db_s_port'] = I('post.db_slave_port');
            }
            $addLogic['dbname'] = I('post.dbname');

            //检查数据库名是否重复
            $count = D('UCLogic')->where("`db_m_host`='{$addLogic['db_m_host']}' && `dbname`='" . I('post.dbname') . "'")->count();
            if ($count > 0) {
                C('G_ERROR', 'dbname_exist');
                goto end;
            }

            //log数据库
            if (I('post.log_db_type') == 'old') {
                $info = D('UCLogic')->where("`gid`='{$gid}' && `sid`='" . I('post.db_select') . "'")->find();
                $addLogic['log_db_host'] = $info['log_db_host'];
                $addLogic['log_db_user'] = $info['log_db_user'];
                $addLogic['log_db_pwd'] = $info['log_db_pwd'];
                $addLogic['log_db_port'] = $info['log_db_port'];
            } else {
                $addLogic['log_db_host'] = I('post.log_db_host');
                $addLogic['log_db_user'] = I('post.log_db_user');
                $addLogic['log_db_pwd'] = I('post.log_db_pwd');
                $addLogic['log_db_port'] = I('post.log_db_port');
            }
            $addLogic['log_dbname'] = I('post.log_dbname');

            //redis配置
            if (I('post.redis_type') == 'old') {
                $info = D('UCLogic')->where("`gid`='{$gid}' && `sid`='" . I('post.redis_select') . "'")->find();
                $addLogic['redis_host'] = $info['redis_host'];
                $addLogic['redis_port'] = $info['redis_port'];
            } else {
                $addLogic['redis_host'] = I('post.redis_host');
                $addLogic['redis_port'] = I('post.redis_port');
            }

            //redis库分配
            $info = D('UCLogic')->where("`redis_host`='{$addLogic['redis_host']}' && `redis_port`='{$addLogic['redis_port']}'")->order('`redis_game` DESC')->find();
            if (empty($info)) {
                $addLogic['redis_game'] = 0;
                $addLogic['redis_social'] = 1;
                $addLogic['redis_fight'] = 2;
            } else if ($info['redis_fight'] + 3 > self::REDIS_MAX_DB) {
                C('G_ERROR', 'redis_max_db');
                goto end;
            } else {
                $addLogic['redis_game'] = $info['redis_fight'] + 1;
                $addLogic['redis_social'] = $info['redis_fight'] + 2;
                $addLogic['redis_fight'] = $info['redis_fight'] + 3;
            }

            //脚本服务器分配
            $addLogic['script_server_id'] = I('post.script');

            //平台
            $addLogic['platform_url'] = !I('post.platform_url') ? '' : I('post.platform_url');
            $addLogic['platform_url'] = 'http://' . str_replace('http://', '', $addLogic['platform_url']);
            $addLogic['platform_sid'] = !I('post.platform_sid') ? '' : I('post.platform_sid');

            //状态
            $addLogic['status'] = 0;

            //写入数据库
            if (false === $id = D('UCLogic')->CreateData($addLogic)) {
                C('G_ERROR', 'fail');
                goto end;
            }

            //服务器ID
            $addServer['gid'] = $gid;

            //服务器ID
            $addServer['logic_id'] = $id;

            //服务器渠道
            $addServer['channel_id'] = I('post.channel');

            //服务器名称
            $addServer['name'] = I('post.name');

            //服务器配置类型
            $addServer['type'] = I('post.type');

            //状态
            $addServer['status'] = 0;

            //写入数据库
            if (false === D('UCServer')->CreateData($addServer)) {
                C('G_ERROR', 'fail');
                goto end;
            }

            //创建数据库
//            $exec = 'mysqladmin -h' . $addLogic['db_m_host'] . ' -u' . $addLogic['db_m_user'] . ' -p\'' . $addLogic['db_m_pwd'] . '\' create ' . $addLogic['dbname'] . ' DEFAULT CHARSET utf8 COLLATE utf8_general_ci;';
//            exec($exec);

            //创建表
            $exec = 'mysql -h' . $addLogic['db_m_host'] . ' -u' . $addLogic['db_m_user'] . ' -p\'' . $addLogic['db_m_pwd'] . '\' ' . $addLogic['dbname'] . ' < ' . $this->mDBPath . 'otl.sql' . ' --default-character-set=utf8';
            exec($exec);

            //创建日志数据库
            $exec = 'mysqladmin -h' . $addLogic['log_db_host'] . ' -u' . $addLogic['log_db_user'] . ' -p\'' . $addLogic['log_db_pwd'] . '\' create ' . $addLogic['log_dbname'] . ' DEFAULT CHARSET utf8 COLLATE utf8_general_ci;';
            exec($exec);

            //创建初始化sql脚本
            $filename = $this->initSql($sid);

            //执行
            $exec = 'mysql -h' . $addLogic['db_m_host'] . ' -u' . $addLogic['db_m_user'] . ' -p\'' . $addLogic['db_m_pwd'] . '\' ' . $addLogic['dbname'] . ' < ' . $this->mDBPath . $filename . ' --default-character-set=utf8';
            exec($exec);

            $this->clearApc('server');

            C('G_ERROR', 'success');

        }

        end:
        //逻辑服务器配置
        if (!empty($this->vServer)) {
            foreach ($this->vServer as $key => $value) {
                $db[$key] = 'S' . $key . '-' . $value['master']['db_host'] . ':' . $value['master']['db_port'];
                $logDB[$key] = 'S' . $key . '-' . $value['log']['db_host'] . ':' . $value['log']['db_port'];
                $redis[$key] = 'S' . $key . '-' . $value['redis']['host'] . ':' . $value['redis']['port'];
            }
        } else {
            $db = array();
            $redis = array();
        }
        //脚本服务器配置
        $this->vScript = M('ServerScript')->where("`status`='1'")->select();

        //查询渠道信息
        $this->db = $db;
        $this->logDB = $logDB;
        $this->redis = $redis;
        $this->channel = D('UCChannel')->getAll();
        $this->alert = get_error();
        $this->assign('vType', $this->mServerType);
        $this->display();//显示页面
    }

    //清档
    public function clear()
    {

        $time = time();

        //连接数据库
        $sid = I('get.server_id');
        $logicInfo = $this->mServerList[$sid];

        //备份库
        $exec = 'mysqldump -h' . $logicInfo['master']['db_host'] . ' -u' . $logicInfo['master']['db_user'] . ' -p\'' . $logicInfo['master']['db_pwd'] . '\' ' . $logicInfo['dbname'] . ' > ' . $this->mDBPath . $logicInfo['dbname'] . '_' . $time . '.sql --default-character-set=utf8';
        exec($exec);

        //备份g_params
        $exec = 'mysqldump -h' . $logicInfo['master']['db_host'] . ' -u' . $logicInfo['master']['db_user'] . ' -p\'' . $logicInfo['master']['db_pwd'] . '\' ' . $logicInfo['dbname'] . ' g_params > ' . $this->mDBPath . 'g_params_' . $time . '.sql --default-character-set=utf8';
        exec($exec);

        //创建表
        $exec = 'mysql -h' . $logicInfo['master']['db_host'] . ' -u' . $logicInfo['master']['db_user'] . ' -p\'' . $logicInfo['master']['db_pwd'] . '\' ' . $logicInfo['dbname'] . ' < ' . $this->mDBPath . 'otl.sql --default-character-set=utf8';
        exec($exec);

        //删除日志数据库
        $exec = 'mysqladmin -h' . $logicInfo['log']['db_host'] . ' -u' . $logicInfo['log']['db_user'] . ' -p\'' . $logicInfo['log']['db_pwd'] . '\'-e"drop database ' . $logicInfo['log_dbname'] . '"';
        exec($exec);

        //创建日志数据库
        $exec = 'mysqladmin -h' . $logicInfo['log']['db_host'] . ' -u' . $logicInfo['log']['db_user'] . ' -p\'' . $logicInfo['log']['db_pwd'] . '\' create ' . $logicInfo['log_dbname'] . ' DEFAULT CHARSET utf8 COLLATE utf8_general_ci;';
        exec($exec);

        //创建初始化sql脚本
        $filename = $this->initSql($sid);

        //执行
        $exec = 'mysql -h' . $logicInfo['master']['db_host'] . ' -u' . $logicInfo['master']['db_user'] . ' -p\'' . $logicInfo['master']['db_pwd'] . '\' ' . $logicInfo['dbname'] . ' < ' . $this->mDBPath . $filename . ' --default-character-set=utf8';
        exec($exec);

        //覆盖g_params
        $exec = 'mysql -h' . $logicInfo['master']['db_host'] . ' -u' . $logicInfo['master']['db_user'] . ' -p\'' . $logicInfo['master']['db_pwd'] . '\' ' . $logicInfo['dbname'] . ' < ' . $this->mDBPath . 'g_params_' . $time . '.sql --default-character-set=utf8';
        exec($exec);

        //清除redis
        $dbConfig = change_db_server($sid, 'master');
        D('Predis')->cli('game', $sid)->flushdb();
        D('Predis')->cli('social', $sid)->flushdb();
        D('Predis')->cli('fight', $sid)->flushdb();
        D('Predis')->cli('game', $sid)->set('pre', 0);
        $arena_max_rank = M()->db($sid, $dbConfig)->table('g_arena')->max('rank');
        D('Predis')->cli('game', $sid)->set('arena_max_rank', $arena_max_rank);
        C('G_ERROR', 'success');

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面
    }

    //
    private function initSql($sid, $filename = 'init.sql')
    {
        //修改内存占用量
        ini_set('memory_limit', '512M');
        create_sql('', $this->mDBPath, $filename, 'w');

        //战队
        $sqlTeamBase = "insert into `g_team` (`tid`,`uid`,`role_id`,`nickname`,`icon`,`level`,`exp`,`diamond_pay`,`diamond_free`,`gold`,`star`,`material_score`,`vality`,`vality_utime`,`energy`,`energy_utime`,`skill_point`,`skill_point_utime`,`fund`,`guide_skip`,`channel_id`,`login_continuous`,`last_login_time`,`ctime`) values ";

        //伙伴
        $sqlPartnerBase = "insert into `g_partner` (`tid`,`group`,`index`,`level`,`exp`,`favour`,`soul`,`skill_1_level`,`skill_2_level`,`skill_3_level`,`skill_4_level`,`skill_5_level`,`skill_6_level`,`force`,`utime`,`ctime`) values ";

        //装备
        $sqlEquipBase = "insert into `g_equip` (`tid`,`group`,`index`,`partner_group`,`level`,`extra_1_type`,`extra_1_id`,`extra_1_value`,`extra_1_lock`,`extra_2_type`,`extra_2_id`,`extra_2_value`,`extra_2_lock`,`extra_3_type`,`extra_3_id`,`extra_3_value`,`extra_3_lock`,`extra_4_type`,`extra_4_id`,`extra_4_value`,`extra_4_lock`) values ";

        //竞技场
        $sqlArenaBase = "insert into `g_arena` (`tid`,`rank`,`honour`,`partner`,`rand_list`,`win`,`rank_change`,`last_refresh_time`,`ctime`) values ";

        //vip
        $sqlVipBase = "insert into `g_vip` (`tid`,`index`,`score`,`pay_valid`,`pay`,`pay_count`,`first_pay`,`first_pay_level`,`first_pay_time`,`utime`) values ";

        //count
        $sqlCountBase = "insert into `g_count` (`tid`) values ";

        //vip
        $sqlItemBase = array();
        for ($i = 0; $i <= 9; ++$i) {
            $sqlItemBase[$i] = "insert into `g_item_{$i}` (`tid`,`item`,`count`) values ";
        }

        //quest
        $sqlQuestBase = "insert into `g_quest` (`tid`,`quest`,`ctime`,`utime`,`status`) values ";

        //quest
        $sqlQuestDailyBase = "insert into `t_daily_quest` (`tid`,`quest`,`count`,`ctime`,`utime`) values ";

        //quest
        $sqlQuestPartnerBase = "insert into `g_partner_quest` (`tid`,`quest`,`ctime`,`utime`,`status`) values ";


        $sqlTeam = '';
        $sqlArena = '';
        $sqlPartner = '';
        $sqlEquip = '';

        $sumPartner = 0;

        //获取机器人配置
        $nicknameConfig = D('Static')->access('nickname');
        $nicknameMax = count($nicknameConfig);
        $robotTeamConfig = D('Static')->access('robot_team');
        $robotArenaConfig = D('Static')->access('arena_robot');
        $partnerConfig = D('Static')->access('partner');

        //生成数据
        foreach ($robotArenaConfig as $value) {

            for ($i = $value['high']; $i <= $value['low']; ++$i) {

                //战队
                $tid = $i;
//                $team_nickname = $this->getNickname($nicknameConfig, $nicknameMax);
                $team_nickname = D('Static')->access('params', 'ROBOT_NAME_SUB') . $i;
                $team_icon = $robotTeamConfig[$value['robot_team']]['partner_1_group'];
                $team_level = $robotTeamConfig[$value['robot_team']]['team_level'];
                $sqlTeam .= "\r\n" . "('{$tid}','0',null,'{$team_nickname}','{$team_icon}','{$team_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                //竞技场
                $arenaPartnerList = array(
                    (int)$robotTeamConfig[$value['robot_team']]['partner_1_group'],
                    (int)$robotTeamConfig[$value['robot_team']]['partner_2_group'],
                    (int)$robotTeamConfig[$value['robot_team']]['partner_3_group'],
                    (int)$robotTeamConfig[$value['robot_team']]['partner_4_group'],
                    (int)$robotTeamConfig[$value['robot_team']]['partner_5_group'],
                );
                $arena_partner = json_encode($arenaPartnerList);
                $sqlArena .= "\r\n" . "('{$tid}',{$tid},'0','{$arena_partner}','[]','0','0','0','0'),";

                //伙伴&装备
                for ($j = 1; $j <= 5; ++$j) {
                    $partner_group = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_group'];
                    $partner_index = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_group'] . $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_quality'];
                    $partner_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $partner_favour = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_awake'] * 1000;
                    $partner_force = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_ability'];
                    $partner_skill_2_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $partner_skill_3_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlPartner .= "\r\n" . "('{$tid}','{$partner_group}','{$partner_index}','{$partner_level}','0','{$partner_favour}','0','1','{$partner_skill_2_level}','{$partner_skill_3_level}','0','0','0','{$partner_force}','0','0'),";
                    ++$sumPartner;
                    if ($sumPartner % 10000 == 0) {
                        $sql = $sqlPartnerBase . substr($sqlPartner, 0, -1) . ';';
                        create_sql($sql, $this->mDBPath, $filename);
                        $sqlPartner = '';
                    }

                    $equipIndex = floor($robotTeamConfig[$value['robot_team']]['partner_' . $j . '_quality'] / 2);
                    $equipIndex = $equipIndex == 0 ? 1 : $equipIndex;

                    $equip_group = $partnerConfig[$partner_index]['init_equipment_weapon'];
                    $equip_index = $partnerConfig[$partner_index]['init_equipment_weapon'] . '0' . $equipIndex;
                    $equip_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlEquip .= "\r\n" . "('{$tid}','{$equip_group}','{$equip_index}','{$partner_group}','{$equip_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                    $equip_group = $partnerConfig[$partner_index]['init_equipment_armor'];
                    $equip_index = $partnerConfig[$partner_index]['init_equipment_armor'] . '0' . $equipIndex;
                    $equip_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlEquip .= "\r\n" . "('{$tid}','{$equip_group}','{$equip_index}','{$partner_group}','{$equip_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                    $equip_group = $partnerConfig[$partner_index]['init_equipment_accessory'];
                    $equip_index = $partnerConfig[$partner_index]['init_equipment_accessory'] . '0' . $equipIndex;
                    $equip_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlEquip .= "\r\n" . "('{$tid}','{$equip_group}','{$equip_index}','{$partner_group}','{$equip_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                    if ($sumPartner % 5000 == 0) {
                        $sql = $sqlEquipBase . substr($sqlEquip, 0, -1) . ';';
                        create_sql($sql, $this->mDBPath, $filename);
                        $sqlEquip = '';
                    }

                }

            }

        }

        //生成robot信息
        if ($sqlTeam != '') {
            $sql = $sqlTeamBase . substr($sqlTeam, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlPartner != '') {
            $sql = $sqlPartnerBase . substr($sqlPartner, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlEquip != '') {
            $sql = $sqlEquipBase . substr($sqlEquip, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlArenaBase != '') {
            $sql = $sqlArenaBase . substr($sqlArena, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }

        //插入10000条玩家信息
        $sqlTeam = '';
        $sqlPartner = '';
        $sqlEquip = '';
        $sqlVip = '';
        $sqlCount = '';
        $sqlItem = array();
        for ($i = 0; $i <= 9; ++$i) {
            $sqlItem[$i] = '';
        }
        $sqlQuest = '';
        $sqlQuestDaily = '';
        $sqlQuestPartner = '';

        $sumItem = array();
        for ($i = 0; $i <= 9; ++$i) {
            $sumItem[$i] = 0;
        }
        $sumQuest = 0;
        $sumQuestDaily = 0;
        $sumQuestPartner = 0;

        $now = time();
        $teamConfig = D('Static')->access('team_creation', 1);

        //预创建帐号
        for ($i = 1; $i <= self::PRE_CREATE_NUM; ++$i) {
            $tid = $sid * 1000000 + $i;
            $team_icon = $teamConfig['init_icon'];
            $team_level = $teamConfig['init_level'];
            $team_exp = $teamConfig['init_exp'];
            $team_gold = $teamConfig['init_gold'];
            $team_vality = $teamConfig['init_vality'];
            $team_energy = $teamConfig['init_energy'];
            $team_skill_point = $teamConfig['init_skill_point'];
            $sqlTeam .= "\r\n" . "('{$tid}','0',null,'','{$team_icon}','{$team_level}','{$team_exp}','0','0','{$team_gold}','0','0','{$team_vality}','0','{$team_energy}','0','{$team_skill_point}','0','0','0','0','0','0','0'),";
            $sqlVip .= "\r\n" . "('{$tid}','1000','0','0','0','0','0','0','0','0'),";
            $sqlCount .= "\r\n" . "('{$tid}'),";
        }

        //partner&equip
        //预创建帐号
        $arrPartner = array();
        $arrPartner[] = $teamConfig['init_char'];
        foreach ($arrPartner as $partner) {

            //伙伴
            $partnerGroupConfig = D('Static')->access('partner_group', $partner);
            foreach ($partnerGroupConfig as $config) {
                if ($config['is_init'] == 1) {
                    $partnerConfig = $config;
                    break;
                }
            }
            $partner_group = $partnerConfig['group'];
            $partner_index = $partnerConfig['index'];

            //装备
            $weaponGroupConfig = D('Static')->access('equipment', $partnerConfig['init_equipment_weapon']);
            $armorGroupConfig = D('Static')->access('equipment', $partnerConfig['init_equipment_armor']);
            $accessoryGroupConfig = D('Static')->access('equipment', $partnerConfig['init_equipment_accessory']);

            foreach ($weaponGroupConfig as $config) {
                if ($config['is_init'] == 1) {
                    $weaponConfig = $config;
                    break;
                }
            }
            foreach ($armorGroupConfig as $config) {
                if ($config['is_init'] == 1) {
                    $armorConfig = $config;
                    break;
                }
            }
            foreach ($accessoryGroupConfig as $config) {
                if ($config['is_init'] == 1) {
                    $accessoryConfig = $config;
                    break;
                }
            }

            for ($i = 1; $i <= self::PRE_CREATE_NUM; ++$i) {
                $tid = $sid * 1000000 + $i;
                $sqlPartner .= "\r\n" . "('{$tid}','{$partner_group}','{$partner_index}','1','0','0','0','1','1','1','0','0','0','0','{$now}','0'),";
                $sqlEquip .= "\r\n" . "('{$tid}','{$weaponConfig['group']}','{$weaponConfig['index']}','{$partner_group}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";
                $sqlEquip .= "\r\n" . "('{$tid}','{$armorConfig['group']}','{$armorConfig['index']}','{$partner_group}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";
                $sqlEquip .= "\r\n" . "('{$tid}','{$accessoryConfig['group']}','{$accessoryConfig['index']}','{$partner_group}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                if ($i % 5000 == 0) {
                    $sql = $sqlEquipBase . substr($sqlEquip, 0, -1) . ';';
                    create_sql($sql, $this->mDBPath, $filename);
                    $sqlEquip = '';
                }
            }

        }

        //item
        for ($j = 1; $j <= 4; ++$j) {
            if ($teamConfig['init_item_' . $j] > 0 && $teamConfig['init_item_' . $j . '_count'] > 0) {
                for ($k = 1; $k <= self::PRE_CREATE_NUM; ++$k) {
                    $tid = $sid * 1000000 + $k;
                    $num = $tid % 10;
                    $sqlItem[$num] .= "\r\n" . "('{$tid}','{$teamConfig['init_item_' . $j]}','{$teamConfig['init_item_' . $j . '_count']}'),";
                    ++$sumItem[$num];
                    if ($sumItem[$num] % 10000 == 0) {
                        $sql = $sqlItemBase[$num] . substr($sqlItem[$num], 0, -1) . ';';
                        create_sql($sql, $this->mDBPath, $filename);
                        $sqlItem[$num] = '';
                    }
                }
            }
        }

        //quest
        $questConfig = D('Static')->access('quest');
        foreach ($questConfig as $type => $questGroupConfig) {
            foreach ($questGroupConfig as $index => $config) {
                if ($config['need_level'] <= 1 && $config['pre_instance'] == 0 && $config['pre_quest'] == 0 && ($config['pre_partner'] == 0 || in_array($config['pre_partner'], $arrPartner))) {
                    for ($i = 1; $i <= self::PRE_CREATE_NUM; ++$i) {
                        $tid = $sid * 1000000 + $i;
                        $sqlQuest .= "\r\n" . "('{$tid}','{$index}','{$now}','{$now}','0'),";
                        ++$sumQuest;
                        if ($sumQuest % 10000 == 0) {
                            $sql = $sqlQuestBase . substr($sqlQuest, 0, -1) . ';';
                            create_sql($sql, $this->mDBPath, $filename);
                            $sqlQuest = '';
                        }
                    }
                }
            }
        }

        //dailyQuest
        $dailyQuestConfig = D('Static')->access('quest_daily');
        foreach ($dailyQuestConfig as $index => $config) {
            if ($config['need_level'] <= 1 && $config['pre_instance'] == 0 && $config['pre_quest'] == 0) {
                for ($i = 1; $i <= self::PRE_CREATE_NUM; ++$i) {
                    $tid = $sid * 1000000 + $i;
                    $sqlQuestDaily .= "\r\n" . "('{$tid}','{$index}','0','{$now}','{$now}'),";
                    ++$sumQuestDaily;
                    if ($sumQuestDaily % 10000 == 0) {
                        $sql = $sqlQuestDailyBase . substr($sqlQuestDaily, 0, -1) . ';';
                        create_sql($sql, $this->mDBPath, $filename);
                        $sqlQuestDaily = '';
                    }
                }
            }
        }

        //partnerQuest
        foreach ($arrPartner as $partnerGroup) {
            $partnerQuestConfig = D('Static')->access('partner_quest_group', $partnerGroup);
            foreach ($partnerQuestConfig as $index => $config) {
                if ($config['need_1_type'] == 0) {
                    for ($i = 1; $i <= self::PRE_CREATE_NUM; ++$i) {
                        $tid = $sid * 1000000 + $i;
                        $sqlQuestPartner .= "\r\n" . "('{$tid}','{$index}','{$now}','{$now}','0'),";
                        ++$sumQuestPartner;
                        if ($sumQuestPartner % 10000 == 0) {
                            $sql = $sqlQuestPartnerBase . substr($sqlQuestPartner, 0, -1) . ';';
                            create_sql($sql, $this->mDBPath, $filename);
                            $sqlQuestPartner = '';
                        }
                    }
                }
            }
        }

        if ($sqlTeam != '') {
            $sql = $sqlTeamBase . substr($sqlTeam, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlPartner != '') {
            $sql = $sqlPartnerBase . substr($sqlPartner, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlEquip != '') {
            $sql = $sqlEquipBase . substr($sqlEquip, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlVip != '') {
            $sql = $sqlVipBase . substr($sqlVip, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlCount != '') {
            $sql = $sqlCountBase . substr($sqlCount, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlQuest != '') {
            $sql = $sqlQuestBase . substr($sqlQuest, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlQuestDaily != '') {
            $sql = $sqlQuestDailyBase . substr($sqlQuestDaily, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        if ($sqlQuestPartner != '') {
            $sql = $sqlQuestPartnerBase . substr($sqlQuestPartner, 0, -1) . ';';
            create_sql($sql, $this->mDBPath, $filename);
        }
        for ($i = 0; $i <= 9; ++$i) {
            if ($sqlItem[$i] != '') {
                $sql = $sqlItemBase[$i] . substr($sqlItem[$i], 0, -1) . ';';
                create_sql($sql, $this->mDBPath, $filename);
            }
        }

        //执行自增配置
        $sql = "alter table `g_team` AUTO_INCREMENT=" . $sid . "000001;";
        create_sql($sql, $this->mDBPath, $filename);
        $sql = "alter table `g_league` AUTO_INCREMENT=" . $sid . "000001;";
        create_sql($sql, $this->mDBPath, $filename);

        return $filename;

    }

    //重新导入机器人
    public function robot()
    {
        //连接数据库
        $sid = I('get.server_id');
        $logicInfo = $this->mServerList[$sid];

        //机器人配置
        $partnerConfig = D('Static')->access('partner');
        $robotTeamConfig = D('Static')->access('robot_team');
        $robotArenaConfig = D('Static')->access('arena_robot');

        //修改内存占用量
        ini_set('memory_limit', '512M');

        //文件生成路径
        $filename = 'robot.sql';
        create_sql('', $this->mDBPath, $filename, 'w');

        //删除原有数据
        $sql = "delete from `g_partner` where `tid`<=10000;";
        create_sql($sql, $this->mDBPath, $filename);

        $sql = "delete from `g_equip` where `tid`<=10000;";
        create_sql($sql, $this->mDBPath, $filename);

        //伙伴
        $sqlPartner = "insert into `g_partner` (`tid`,`group`,`index`,`level`,`exp`,`favour`,`soul`,`skill_1_level`,`skill_2_level`,`skill_3_level`,`skill_4_level`,`skill_5_level`,`skill_6_level`,`force`,`utime`,`ctime`) values ";

        //装备
        $sqlEquip = "insert into `g_equip` (`tid`,`group`,`index`,`partner_group`,`level`,`extra_1_type`,`extra_1_id`,`extra_1_value`,`extra_1_lock`,`extra_2_type`,`extra_2_id`,`extra_2_value`,`extra_2_lock`,`extra_3_type`,`extra_3_id`,`extra_3_value`,`extra_3_lock`,`extra_4_type`,`extra_4_id`,`extra_4_value`,`extra_4_lock`) values ";

        //生成数据
        foreach ($robotArenaConfig as $value) {

            //修改竞技场阵容
            $arenaPartnerList = array($robotTeamConfig[$value['robot_team']]['partner_1_group'], $robotTeamConfig[$value['robot_team']]['partner_2_group'], $robotTeamConfig[$value['robot_team']]['partner_3_group'], $robotTeamConfig[$value['robot_team']]['partner_4_group'], $robotTeamConfig[$value['robot_team']]['partner_5_group'],);
            $partner = json_encode($arenaPartnerList);

            //竞技场
            $sql = "update `g_arena` set `partner`='{$partner}' where `tid` between '{$value['high']}' and '{$value['low']}';";
            create_sql($sql, $this->mDBPath, $filename);

            for ($i = $value['high']; $i <= $value['low']; ++$i) {

                //伙伴装备
                for ($j = 1; $j <= 5; ++$j) {
                    $partner_tid = $i;
                    $partner_group = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_group'];
                    $partner_index = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_group'] . $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_quality'];
                    $partner_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $partner_favour = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_awake'] * 1000;
                    $partner_force = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_ability'];
                    $partner_skill_2_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $partner_skill_3_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlPartner .= "\r\n" . "('{$partner_tid}','{$partner_group}','{$partner_index}','{$partner_level}','0','{$partner_favour}','0','1','{$partner_skill_2_level}','{$partner_skill_3_level}','0','0','0','{$partner_force}','0','0'),";

                    //装备
                    $equipIndex = floor($robotTeamConfig[$value['robot_team']]['partner_' . $j . '_quality'] / 2);
                    $equipIndex = $equipIndex == 0 ? 1 : $equipIndex;
                    $equip_tid = $i;
                    $equip_group = $partnerConfig[$partner_index]['init_equipment_weapon'];
                    $equip_index = $partnerConfig[$partner_index]['init_equipment_weapon'] . '0' . $equipIndex;
                    $equip_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlEquip .= "\r\n" . "('{$equip_tid}','{$equip_group}','{$equip_index}','{$partner_group}','{$equip_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                    $equip_group = $partnerConfig[$partner_index]['init_equipment_armor'];
                    $equip_index = $partnerConfig[$partner_index]['init_equipment_armor'] . '0' . $equipIndex;
                    $equip_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlEquip .= "\r\n" . "('{$equip_tid}','{$equip_group}','{$equip_index}','{$partner_group}','{$equip_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";

                    $equip_group = $partnerConfig[$partner_index]['init_equipment_accessory'];
                    $equip_index = $partnerConfig[$partner_index]['init_equipment_accessory'] . '0' . $equipIndex;
                    $equip_level = $robotTeamConfig[$value['robot_team']]['partner_' . $j . '_level'];
                    $sqlEquip .= "\r\n" . "('{$equip_tid}','{$equip_group}','{$equip_index}','{$partner_group}','{$equip_level}','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'),";
                }

            }

        }

        $sqlPartner = substr($sqlPartner, 0, -1) . ';';
        $sqlEquip = substr($sqlEquip, 0, -1) . ';';

        create_sql($sqlPartner, $this->mDBPath, $filename);
        create_sql($sqlEquip, $this->mDBPath, $filename);

        //执行
        $exec = 'mysql -h' . $logicInfo['master']['db_host'] . ' -u' . $logicInfo['master']['db_user'] . ' -p\'' . $logicInfo['master']['db_pwd'] . '\' ' . $logicInfo['dbname'] . ' < ' . $this->mDBPath . $filename;
        if(false === exec($exec)){
            C('G_ERROR', 'fail');
        }

        //返回
        C('G_ERROR', 'success');

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&search_channel_id=" . I('get.search_channel_id') . "";
        $this->display("Public:jump");//显示页面
    }

    //多渠道
    public function channelAdd()
    {

        $where['id'] = I('get.id');
        $sid = D('UCLogic')->where($where)->getField('sid');

        if (!empty($_POST)) {

            $gid = C('GAME_ID');
            $sid = I('get.server_id');

            //获取logicId
            $logicId = D('UCLogic')->where("`gid`='{$gid}' && `sid`='{$sid}'")->getField('id');

            //获取已经开过的渠道ID
            $whereServer['gid'] = $gid;
            $whereServer['logic_id'] = $logicId;
            $opened = D('UCServer')->where($whereServer)->getField('channel_id', true);

            //服务器ID
            $add['gid'] = C('GAME_ID');

            //服务器ID
            $add['logic_id'] = I('get.id');

            //服务器名称
            $add['name'] = I('post.name');

            //服务器配置类型
            $add['type'] = I('post.type');

            //默认状态
            $add['status'] = 0;

            $all = array();
            foreach (I('post.channel') as $value) {
                if(!in_array($value, $opened)){
                    $add['channel_id'] = $value;
                    $all[] = $add;
                }
            }

            //写入数据库
            if (!empty($all) && false === D('UCServer')->CreateAllData($all)) {
                C('G_ERROR', 'fail');
                goto end;
            }

            C('G_ERROR', 'success');

        }

        end:
        $this->id = I('get.id');
        $this->sid = $sid;
        $this->channel = D('UCChannel')->getAll();
        $this->alert = get_error();
        $this->assign('vType', $this->mServerType);
        $this->display();//显示页面

    }

    //移动数据库
    public function move()
    {

        if (!empty($_POST)) {

            //获取数据库配置
            $info = D('UCLogic')->getRowBySid(I('post.db_from'));
            $fromHost = $info['db_m_host'];
            $fromUser = $info['db_m_user'];
            $fromPwd = $info['db_m_pwd'];
            $fromPort = $info['db_m_port'];
            $fromName = $info['dbname'];

            //获取目标数据库配置
            if (I('post.db_to') != '-1') {
                $info = D('UCLogic')->getRowBySid(I('post.db_to'));
                $toHost = $info['db_m_host'];
                $toUser = $info['db_m_user'];
                $toPwd = $info['db_m_pwd'];
                $toPort = $info['db_m_port'];
            } else {
                $toHost = I('post.db_host');
                $toUser = I('post.db_user');
                $toPwd = I('post.db_pwd');
                $toPort = I('post.db_port');
            }

            //检测两个数据库不在一台服务器上
            if ($fromHost == $toHost && $fromUser == $toUser && $fromPwd == $toPwd && $fromPort == $toPort) {
                C('G_ERROR', 'not_allow_same_server');
                goto end;
            }

            //将需要迁徙的数据库dump成sql
            $exec = "mysqldump -h{$fromHost} -u{$fromUser} -p'{$fromPwd}' {$fromName} > {$this->mDBPath}{$fromName}.sql";
            exec($exec);

            //创建数据库
            $exec = "mysqladmin -h{$toHost} -u{$toUser} -p'{$toPwd}' create {$fromName}";
            exec($exec);

            //将sql执行到目标服务器
            $exec = "mysql -h{$toHost} -u{$toUser} -p'{$toPwd}' {$fromName} < {$this->mDBPath}{$fromName}.sql";
            exec($exec);

            //删除sql
            unlink($this->mDBPath . $fromName . '.sql');

            //修改db配置
            $where['sid'] = I('post.db_from');
            $save['db_m_host'] = $toHost;
            $save['db_m_user'] = $toUser;
            $save['db_m_pwd'] = $toPwd;
            $save['db_m_port'] = $toPort;
            $save['db_s_host'] = $toHost;
            $save['db_s_user'] = $toUser;
            $save['db_s_pwd'] = $toPwd;
            $save['db_s_port'] = $toPort;
            D('UCLogic')->UpdateData($save, $where);

            //清除缓存
            $this->clearApc('server');

            //成功
            C('G_ERROR', 'success');

        }

        //返回
        end:
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //合服
    public function merge()
    {

        if (!empty($_POST)) {

            set_time_limit(0);
            ini_set('memory_limit', '2048M');

            //查询住服务器配置
            $gid = C('GAME_ID');
            $where['logic_id'] = D('UCLogic')->where("`gid`='{$gid}' && `sid`='" . I('post.merged_server_id') . "'")->getField('id');
            $update['logic_id'] = D('UCLogic')->where("`gid`='{$gid}' && `sid`='" . I('post.master_server_id') . "'")->getField('id');

            //写入数据库
            if (false === D('UCServer')->UpdateData($update, $where)) {
                C('G_ERROR', 'fail');
                goto end;
            }

            //导表
            $error = array();
            $dbConfigMaster = change_db_server(I('post.master_server_id'), 'master');
            $dbConfigMerge = change_db_server(I('post.merged_server_id'), 'master');

            //遍历表
            foreach (self::$sTableAll as $key => $value) {

                switch ($key) {

                    //游戏表
                    case 'game':

                        foreach ($value as $val) {

                            //查询未被使用的预创建帐号
                            $inTid = '';
                            $list = M()->db(I('post.merged_server_id'), $dbConfigMerge)->where("`tid`>'{$this->tidStart}' && `ctime`='0'")->getField('tid');
                            if (!empty($list)) {
                                $in = sql_in_condition($list);
                                $inTid = " || `tid`{$in}";
                            }

                            //删除多余信息逻辑
                            switch ($val) {
                                case 'g_arena':
                                case 'g_partner':
                                case 'g_equip':
                                case 'g_emblem':
                                case 'g_vip':
                                case 'g_count':
                                case 'g_quest':
                                case 'g_partner_quest':
                                case 'g_item_0':
                                case 'g_item_1':
                                case 'g_item_2':
                                case 'g_item_3':
                                case 'g_item_4':
                                case 'g_item_5':
                                case 'g_item_6':
                                case 'g_item_7':
                                case 'g_item_8':
                                case 'g_item_9':
                                case 'g_team':
                                    $sql = "delete from `{$val}` where `tid`<{$this->tidStart}" . $inTid;
                                    M()->db(I('post.merged_server_id'), $dbConfigMerge)->execute($sql);
                                    break;
                            }


                            //合服逻辑
                            switch ($val) {
                                case '':
                                case 'g_params':
                                case 'g_event':
                                case 'g_league_rank':
                                case 'g_shop':
                                    break;
                                case 'g_team':
                                    $this->copyWithRepeat('g_team', 'nickname', $dbConfigMaster, $dbConfigMerge);
                                    break;
                                case 'g_league':
                                    $this->copyWithRepeat('g_league', 'name', $dbConfigMaster, $dbConfigMerge);
                                    break;
                                case 'g_arena':
                                    $this->mergeArena($dbConfigMaster, $dbConfigMerge);
                                    break;
                                case 'g_mail':
                                case 'g_emblem':
                                    if (!$this->copyExceptId($val, $dbConfigMaster, $dbConfigMerge)) {
                                        $error[] = $val;
                                    }
                                    break;
                                case 'g_life_death_battle':
                                    if (!$this->copyAll($val, $dbConfigMaster, $dbConfigMerge, self::MERGE_SQL_COUNT / 20)) {
                                        $error[] = $val;
                                    }
                                    break;
                                default:
                                    if (!$this->copyAll($val, $dbConfigMaster, $dbConfigMerge, self::MERGE_SQL_COUNT)) {
                                        $error[] = $val;
                                    }
                            }
                        }
                        break;

                    //日志表
                    case 'log':
                        foreach ($value as $val) {
                            if (!$this->copyExceptId($val, $dbConfigMaster, $dbConfigMerge)) {
                                $error[] = $val;
                            }
                        }
                        break;

                    //定时数据表
                    case 'truncate':
                        foreach ($value as $val) {
                            if (!$this->copyAll($val, $dbConfigMaster, $dbConfigMerge, self::MERGE_SQL_COUNT)) {
                                $error[] = $val;
                            }
                        }
                        break;

                }

            }

            //执行成功
            if (empty($error)) {
                $this->clearApc('server');
                C('G_ERROR', 'success');
            }else{
                C('G_ERROR', 'fail');
                dump(111);
                dump($error);
            }

        }

        end:
        $this->channel = D('UCChannel')->getAll();
        $this->alert = get_error();
        $this->error = $error;
        $this->assign('vType', $this->mServerType);
        $this->display();//显示页面

    }

    //直接复制所有
    private function copyAll($table, $dbConfigMaster, $dbConfigMerge, $num)
    {
        $count = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table($table)->count();
        $times = ceil($count / $num);
        for ($i = 1; $i <= $times; ++$i) {
            $start = ($i - 1) * $num;
            $select = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table($table)->limit($start, $num)->select();
            if (!empty($select)) {
                if (false === M()->db(I('post.master_server_id'), $dbConfigMaster)->table($table)->addAll($select)) {
                    dump(M()->db(I('post.master_server_id'))->getLastSql());
                    return false;
                }
            }
            unset($select);
        }
        return true;
    }

    //直接复制所有,除了id
    private function copyExceptId($table, $dbConfigMaster, $dbConfigMerge)
    {
        $count = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table($table)->count();
        $times = ceil($count / self::MERGE_SQL_COUNT);
        for ($i = 1; $i <= $times; ++$i) {
            $start = ($i - 1) * self::MERGE_SQL_COUNT;
            $select = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table($table)->field('id', true)->limit($start, self::MERGE_SQL_COUNT)->select();
            if (!empty($select)) {
                if (false === M()->db(I('post.master_server_id'), $dbConfigMaster)->table($table)->addAll($select)) {
                    dump(M()->db(I('post.master_server_id'))->getLastSql());
                    return false;
                }
            }
        }
        return true;
    }

    //合并放置字段重复
    private function copyWithRepeat($table, $field, $dbConfigMaster, $dbConfigMerge)
    {

        //获取主服中所有的昵称集合
        $arrField = M()->db(I('post.master_server_id'), $dbConfigMaster)->table($table)->getField($field, true);

        //获取合服的信息
        $count = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table($table)->count();
        $times = ceil($count / self::MERGE_SQL_COUNT);
        for ($i = 1; $i <= $times; ++$i) {
            $start = ($i - 1) * self::MERGE_SQL_COUNT;
            $select = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table($table)->limit($start, self::MERGE_SQL_COUNT)->select();
            if (!empty($select)) {

                //如果昵称重复则替换
                foreach ($select as $key => $value) {
                    if (in_array($value[$field], $arrField)) {
                        $select[$key][$field] = $value[$field] . '(1)';
                    }
                }

                //插入数据库
                if (false === M()->db(I('post.master_server_id'), $dbConfigMaster)->table($table)->addAll($select)) {
//                    dump(M()->db(I('post.master_server_id'))->getLastSql());
                    return false;
                }
            }
        }
        return true;

    }

    //合并竞技场
    private function mergeArena($dbConfigMaster, $dbConfigMerge)
    {

        //最终数据
        $arenaAll = array();

        //主服竞技场情况
        $arenaMaster = M()->db(I('post.master_server_id'), $dbConfigMaster)->table('g_arena')->order('`rank` ASC')->select();

        //合服竞技场情况
        $arenaMergeSelect = M()->db(I('post.merged_server_id'), $dbConfigMerge)->table('g_arena')->order('`rank` ASC')->select();
        $arenaMerge = array();
        foreach($arenaMergeSelect as $value){
            $arenaMerge[$value['rank']] = $value;
        }

        //清空表
        if (false === M()->db(I('post.master_server_id'), $dbConfigMaster)->execute('truncate `g_arena`')) {
            return false;
        }

        //遍历主服
        $rank = 1;
        $rankNow = 1;
        foreach ($arenaMaster as $key => $value) {

            //加主服玩家
            $value['rank'] = $rankNow;
            $arenaAll[] = $value;

            //加合服玩家
            if (!empty($arenaMerge[$rank])) {
                ++$rankNow;
                $arenaMerge[$rank]['rank'] = $rankNow;
                $arenaAll[] = $arenaMerge[$rank];
                unset($arenaMerge[$rank]);
            }

            if ($rankNow % self::MERGE_SQL_COUNT == 0) {
                //插入数据
                if (false === M()->db(I('post.master_server_id'), $dbConfigMaster)->table('g_arena')->addAll($arenaAll)) {
                    return false;
                }
                $arenaAll = array();
            }

            ++$rank;
            ++$rankNow;

        }

        //如果主服遍历完合服还有玩家没有加入竞技场
        if (!empty($arenaMerge)) {
            foreach ($arenaMaster as $key => $value) {
                $value['rank'] = $rankNow;
                $arenaAll[] = $value;
                ++$rankNow;
            }
        }

        //插入数据库
        if (!empty($arenaAll)) {
            //插入数据
            if (false === M()->db(I('post.master_server_id'), $dbConfigMaster)->table('g_arena')->addAll($arenaAll)) {
                return false;
            }
        }

        return true;

    }

    //获取随机昵称
    private function getNickname($nicknameConfig, $nicknameMax)
    {
        //生成昵称
        $nickname1 = rand(1, $nicknameMax);
        $nickname2 = rand(1, $nicknameMax);
        $nickname = $nickname1 . $nickname2;
        if (in_array($nickname, $this->nickname)) {
            return $this->getNickname($nicknameConfig, $nicknameMax);
        } else {
            $this->nickname[] = $nickname;
            return $nicknameConfig[$nickname1]['name'] . $nicknameConfig[$nickname2]['surname'];
        }
    }

    //激活所有渠道
    public function channelOpenAll(){
        if (IS_AJAX) {
            $gid = C('GAME_ID');
            $sid = I('get.server_id');

            //获取logicId
            $logicId = D('UCLogic')->where("`gid`='{$gid}' && `sid`='{$sid}'")->getField('id');

            //修改状态
            $data['status'] = 1;
            $where['gid'] = $gid;
            $where['logic_id'] = $logicId;
            if(false === D('UCServer')->UpdateData($data, $where)){
                echo 'fail';
            }
            echo 'ok';
        }
        return;
    }

    //封禁所有渠道
    public function channelCloseAll(){
        if (IS_AJAX) {
            $gid = C('GAME_ID');
            $sid = I('get.server_id');

            //获取logicId
            $logicId = D('UCLogic')->where("`gid`='{$gid}' && `sid`='{$sid}'")->getField('id');

            //修改状态
            $data['status'] = 0;
            $where['gid'] = $gid;
            $where['logic_id'] = $logicId;
            if(false === D('UCServer')->UpdateData($data, $where)){
                echo 'fail';
            }
            echo 'ok';
        }
        return;
    }

    //修改所有渠道状态
    public function channelChangeAll(){
        if (IS_AJAX) {
            $gid = C('GAME_ID');
            $sid = I('get.server_id');

            //获取logicId
            $logicId = D('UCLogic')->where("`gid`='{$gid}' && `sid`='{$sid}'")->getField('id');

            //修改状态
            $data['type'] = I('get.type');
            $where['gid'] = $gid;
            $where['logic_id'] = $logicId;
            if(false === D('UCServer')->UpdateData($data, $where)){
                echo 'fail';
            }
            echo 'ok';
        }
        return;
    }

}