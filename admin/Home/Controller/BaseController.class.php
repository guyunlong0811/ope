<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{

    protected $mSuper = false;//是否是超级管理员
    protected $mServerList = array();//游戏服务器列表
    protected $mServerPermissionList = array();//游戏服务器列表
    protected $mTransFlag;//事务是否成功
    protected $mBonusType = array(
        '0' => 'no_bonus',
        '1' => 'item',
        '2' => 'attr',
        '3' => 'partner',
        '4' => 'favour',
        '5' => 'soul',
//        '6' => 'skill_point',
        '7' => 'partner_exp',
        '9' => 'emblem',
    );
    protected $mRetentionDay = array(2, 3, 7, 15, 30);
    protected $mNow;
    protected $mScriptUrl;

    //前置工作
    public function _initialize()
    {

        set_time_limit(0);
        header_info();
        $this->mNow = time();

        //服务器
//        S(C('APC_PREFIX') . 'server', null);
        $serverList = S(C('APC_PREFIX') . 'server');
        if (empty($serverList)) {
            $gid = C('GAME_ID');
            $logic = D('UCLogic')->where("`gid`='{$gid}'")->select();
            $server = D('UCServer')->where("`gid`='{$gid}'")->select();
            foreach ($logic as $value) {
                $serverList[$value['sid']]['dbname'] = $value['dbname'];
                $serverList[$value['sid']]['log_dbname'] = $value['log_dbname'];
                $serverList[$value['sid']]['master']['db_host'] = $value['db_m_host'];
                $serverList[$value['sid']]['master']['db_user'] = $value['db_m_user'];
                $serverList[$value['sid']]['master']['db_pwd'] = $value['db_m_pwd'];
                $serverList[$value['sid']]['master']['db_port'] = $value['db_m_port'];
                $serverList[$value['sid']]['all']['db_host'] = $value['db_m_host'] . ',' . $value['db_s_host'];
                $serverList[$value['sid']]['all']['db_user'] = $value['db_m_user'] . ',' . $value['db_s_user'];
                $serverList[$value['sid']]['all']['db_pwd'] = $value['db_m_pwd'] . ',' . $value['db_s_pwd'];
                $serverList[$value['sid']]['all']['db_port'] = $value['db_m_port'] . ',' . $value['db_s_port'];
                $serverList[$value['sid']]['log']['db_host'] = $value['log_db_host'];
                $serverList[$value['sid']]['log']['db_user'] = $value['log_db_user'];
                $serverList[$value['sid']]['log']['db_pwd'] = $value['log_db_pwd'];
                $serverList[$value['sid']]['log']['db_port'] = $value['log_db_port'];
                $serverList[$value['sid']]['redis']['host'] = $value['redis_host'];
                $serverList[$value['sid']]['redis']['port'] = $value['redis_port'];
                $serverList[$value['sid']]['redis']['game'] = $value['redis_game'];
                $serverList[$value['sid']]['redis']['social'] = $value['redis_social'];
                $serverList[$value['sid']]['redis']['fight'] = $value['redis_fight'];
                $serverList[$value['sid']]['redis']['status'] = $value['status'];
                $serverList[$value['sid']]['script'] = $value['script_server_id'];
                $serverList[$value['sid']]['platform']['sid'] = $value['platform_sid'];
                $serverList[$value['sid']]['platform']['url'] = $value['platform_url'];

                foreach ($server as $val) {

                    if ($value['id'] == $val['logic_id']) {
                        $channel['channel_id'] = $val['channel_id'];
                        $channel['name'] = $val['name'];
                        $channel['type'] = $val['type'];
                        $channel['status'] = $val['status'];
                        $serverList[$value['sid']]['channel'][] = $channel;
                    }

                }

            }

            //存储缓存
            S(C('APC_PREFIX') . 'server', $serverList);

        }
        $this->mServerList = $serverList;

        //执行脚本放过会员检查
        if (CONTROLLER_NAME == 'Script' || CONTROLLER_NAME == 'Test') {
            return;
        }

        //检查登录状态
        if (!isset($_SESSION['uid'])) {
            redirect(__APP__ . MODULE_NAME . '/Home/logout');
        }

        //检测管理员合法性
        $where['uid'] = $_SESSION['uid'];
        $admin = D('AdminAccount')->getAdminRow($where);
        if (S(C('APC_PREFIX') . 'IDENT') != 'fg') {

            //管理员信息是否存在
            if (!D('AdminAccount')->checkStatus($admin)) {
                redirect(__APP__ . MODULE_NAME . '/Home/logout');
            }
            //管理员状态
            if ($admin['status'] != 1) {
                redirect(__APP__ . MODULE_NAME . '/Home/logout');
            }
            //IP限制
            if ($admin['ip_limit'] != '') {
                if (!check_ip($admin['ip_limit'])) {
                    redirect(__APP__ . MODULE_NAME . '/Home/logout');
                }
            }

        }

        //判断是否为超级管理员
        if (in_array($_SESSION['uid'], get_config('super_admin'))) {
            $this->mSuper = true;
        }

        //判断权限
        if (!$this->permission()) {
            redirect(__APP__ . MODULE_NAME . '/Index/index&error=no_permission');
        }

        //模块
        $this->vController = CONTROLLER_NAME;

        //昵称
        $this->vNickname = $admin['nickname'];
        $this->vApp = APP_STATUS;

        //功能
        $allFunction = get_config('Function');
        foreach ($allFunction as $key1 => $value1) {

            //将menu显示成特定语言
            $allFunction[$key1]['name'] = L($value1['name']);

            //将子menu显示成特定语言
            foreach ($allFunction[$key1]['list'] as $key2 => $value2) {

                $allFunction[$key1]['list'][$key2]['name'] = L($value2['name']);
                foreach ($value2['permission'] as $key3 => $value3) {
                    $allFunction[$key1]['list'][$key2]['permission'][$key3]['name'] = L($value3['name']);
                }

            }

        }

        $this->vFunction = $allFunction;
//        dump($this->vFunction);

        //服务器权限
        $server = D('AdminServer')->getList($_SESSION['uid']);
        if (!$this->mSuper && !in_array('0', $server)) {
            foreach ($server as $value) {
                if (isset($serverList[$value])) {
                    $serverAvailable[$value] = $serverList[$value];
                    $serverAvailable[$value]['server_name'] = $this->getServerName($value);
                    $this->mServerPermissionList[] = $value;
                }

            }
        } else {
            $serverAvailable = $serverList;
            foreach ($serverAvailable as $key => $value) {
                $serverAvailable[$key]['server_name'] = $this->getServerName($key);
                $this->mServerPermissionList[] = $key;
            }
        }
        $this->vServer = $serverAvailable;

        //页码
        $this->s = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $this->pg = I('get.p') ? I('get.p') : 1;
        $this->vGet = $_GET;
        $this->vPost = $_POST;

    }

    //获取是script列表
    protected function getScriptList()
    {
        $this->mScriptUrl = D('ServerScript')->getAll();
    }

    //开始事务
    protected function transBegin($sid)
    {
        M()->db(1, change_db_server($sid, 'master'))->startTrans();
        $this->mTransFlag = false;
    }

    //结束事务
    protected function transEnd($sid)
    {
        if (!$this->mTransFlag) {
            M()->db(1, change_db_server($sid, 'master'))->rollback();
        } else {
            M()->db(1, change_db_server($sid, 'master'))->commit();
            C('G_ERROR', 'success');
        }
        return $this->mTransFlag;
    }

    //分页
    protected function page($model = false, $style = 'sql', $where = '1=1', $num = 15)
    {//分页

        if ($style == 'sql') {
            $count = $model->where($where)->count();
            $count = !empty($count) ? $count : '0';
        } else if ($style == 'array') {
            $count = count($model);
        } else {
            return false;
        }
//		if($count == 0)return false;
        //dump($count);
        $page = new \Think\Page($count, $num);
        $page->rollPage = 5;
        $page->setConfig('first', '<<');
        $page->setConfig('prev', '<');
        $page->setConfig('next', '>');
        $page->setConfig('last', '>>');
        $page->setConfig('theme', "%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%");//rows %NOW_PAGE%/%TOTAL_PAGE%pages
        $this->vPageBar = $page->show();
        return $page;

    }

    //分页
    protected function pageApi($count, $num = 10)
    {//分页

        $page = new \Think\Page($count, $num);
        $page->setConfig('first', '<<');
        $page->setConfig('prev', '<');
        $page->setConfig('next', '>');
        $page->setConfig('last', '>>');
        $page->setConfig('theme', "%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% total:%TOTAL_ROW%");//rows %NOW_PAGE%/%TOTAL_PAGE%pages
        $this->vPageBar = $page->show();
        return $page;

    }

    //记录管理员日志
    private function permission()
    {
        //超级管理员
        if ($this->mSuper) {
            return true;
        } else if (CONTROLLER_NAME == 'Index' && ACTION_NAME == 'index') {
            return true;
        } else {
            $flag = true;

            //功能权限
            if (!D('AdminPermission')->isPermission($_SESSION['uid'], CONTROLLER_NAME, ACTION_NAME)) {
                $flag = false;
            }

            //服务器权限
            if (isset($_REQUEST['server_id']) && $_REQUEST['server_id'] != 0 && !D('AdminServer')->isPermission($_SESSION['uid'], $_REQUEST['server_id'])) {
                $flag = false;
            }

            return $flag;
        }

    }

    //记录管理员日志
    private function log()
    {
        if (CONTROLLER_NAME == 'AdminLog' || CONTROLLER_NAME == 'Script' || CONTROLLER_NAME == 'Test') {
            return;
        }
        $data['uid'] = $_SESSION['uid'];
        $data['controller'] = CONTROLLER_NAME;
        $data['action'] = ACTION_NAME;
        $data['server'] = C('G_SERVER');
        $sql = C('G_SQL');
        foreach ($sql as $value) {
            $data['sql'] .= $value . ';';
        }
        $data['ip'] = get_ip();
        D('AdminLog')->CreateData($data);
        return;
    }

    //http下载头部
    public function download($url, $file)
    {
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        readfile($url);
    }

    //清除所有APC
    protected function clearApc($apcKey = null)
    {

        //清除GM服务器APC
        if (empty($apcKey)) {
            apc_clear_cache('user');
            $rs = apc_clear_cache();
        } else {
            if ($apcKey == 'server') {
                apc_delete(C('APC_PREFIX') . 'server');
            }
            $rs = apc_delete(C('APC_PREFIX') . $apcKey);
        }
        if ($rs === true) {
            $rs = 'success';
        } else {
            $rs = 'fail';
        }
        $result['admin']['name'] = 'Admin';
        $result['admin']['info'] = L($rs);

        //清除脚本服务器APC
        $where['status'] = '1';
        $scriptServerList = M('ServerScript')->where($where)->select();
        $result['script'] = array();
        if (!empty($scriptServerList)) {
            foreach ($scriptServerList as $value) {
                $result['script'][$value['id']]['name'] = $value['name'];
                $host = $value['url'] . '?c=Index&a=clearApc';
                $post['key'] = $apcKey;
                $post['token'] = md5($apcKey . C('CACHE_VERIFY'));
                $rs = curl_link($host, 'post', $post);
                $result['script'][$value['id']]['info'] = L($rs);
            }
        }

        //清除游戏服务器
        $where['status'] = '1';
        $gameServerList = M('ServerGame')->where($where)->select();
        $result['game'] = array();
        if (!empty($gameServerList)) {
            foreach ($gameServerList as $value) {
                $result['game'][$value['id']]['name'] = $value['name'];
                $host = $value['url'] . '?c=Index&a=clearApc';
                $post['key'] = $apcKey;
                $post['token'] = md5($apcKey . C('CACHE_VERIFY'));
                $rs = curl_link($host, 'post', $post);
                $result['game'][$value['id']]['info'] = L($rs);
            }
        }

        return $result;

    }

    //清除所有Redis(g_params)
    protected function clearRedis()
    {

        //清除redis中g_params
        $result['redis'] = array();
        if (!empty($this->mServerList)) {

            foreach ($this->mServerList as $key => $value) {
                $rs = 'success';
                if (false === D('Predis')->cli('game', $key)->del('g_params')) {
                    $rs = 'fail';
                }
                $result['redis'][$key]['name'] = 'S' . $key . '-';
                foreach ($value['channel'] as $val) {
                    $result['redis'][$key]['name'] .= $val['name'] . '/';
                }
                $result['redis'][$key]['name'] = substr($result['redis'][$key]['name'], 0, -1);
                $result['redis'][$key]['info'] = L($rs);
            }

        }

        return $result;
    }

    //脚本返回
    protected function scriptReturn($error)
    {
        if (empty($error)) {
            $rs['status'] = 'success';
        } else {
            $rs['status'] = 'fail';
            $rs['error'] = json_encode($error);
        }
        $rs['sid'] = C('G_SID');
        $rs['timestamp'] = time();
//        header_info('json');
        echo json_encode($rs);
        return;
    }

    //获取奖励ID
    protected function getBonusId($type, $id, $config = array())
    {

        switch ($type) {

            case '1':
                if (empty($config)) {
                    $config = D('Static')->access('item');
                }
                return $config[$id]['name'];

            case '2':
                if (empty($config)) {
                    $config = C('FIELD');
                }
                return L($config[$id]);

            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
                if (empty($config)) {
                    $config = D('Static')->access('partner_group', $id);
                }
                $config = current($config);
                return $config['name'];
        }

    }

    //获取服务器名称
    protected function getServerName($sid)
    {
        if ($sid == '0') {
            return L('all_server');
        }
        $name = 'S' . $sid . '-';
        foreach ($this->mServerList[$sid]['channel'] as $val) {
            $name .= $val['name'] . '/';
        }
        $name = substr($name, 0, -1);
        return $name;
    }

    //析构函数
    public function __destruct()
    {

        //记录日志
        $this->log();

    }

}