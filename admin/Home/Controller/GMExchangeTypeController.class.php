<?php
namespace Home\Controller;

use Think\Controller;

class GMExchangeTypeController extends BInitController
{

    const EXCHANGE_PATH = 'exchange/';

    private $type = array(
        1 => 'exchange_type_1',
        2 => 'exchange_type_2',
    );

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_exchange_type';
        $this->selectChannel();
    }

    //显示列表
    public function index()
    {

        //生成总量
        $select = D('UCExchange')->field('`type`,count(`id`) as `count`')->group('type')->select();
        $total = array();
        if (!empty($select)) {
            foreach ($select as $value) {
                $total[$value['type']] = $value['count'];
            }
        }

        //获取激活情况
        $select = D('UCExchange')->field('`type`,count(`id`) as `count`')->where("`status`='1'")->group('type')->select();
        $act = array();
        if (!empty($select)) {
            foreach ($select as $value) {
                $act[$value['type']] = $value['count'];
            }
        }

        //查询所有信息
        $model = D('UCExchangeType');
        $where['gid'] = C('GAME_ID');
        $where['status'] = array('egt', 0);
        $page = $this->page($model, 'sql', $where);
        $list = $model->page($this->pg . ',' . $page->listRows)->where($where)->order("`id` DESC")->select();
        foreach ($list as $key => $value) {
            if ($value['server'] == '0') {
                $list[$key]['server'] = L('all_server');
            } else {
                $arrServer = explode('#', $value['server']);
                $list[$key]['server'] = '';
                foreach ($arrServer as $sval) {
                    $list[$key]['server'] .= $this->getServerName($sval) . '&';
                }
                $list[$key]['server'] = substr($list[$key]['server'], 0, -1);
            }

            if ($value['channel'] == '0') {
                $list[$key]['channel'] = L('all_channel');
            } else {
                $arrChannel = explode('#', $value['channel']);
                $list[$key]['channel'] = '';
                foreach ($arrChannel as $cval) {
                    $list[$key]['channel'] .= $this->vChannel[$cval] . '&';
                }
                $list[$key]['channel'] = substr($list[$key]['channel'], 0, -1);
            }

            $list[$key]['type'] = L($this->type[$value['type']]);
            $list[$key]['use_count'] = $value['use_count'] == '0' ? L('no_limit') : $value['use_count'];
            $list[$key]['use_count_diff'] = $value['use_count_diff'] == '0' ? L('no_limit') : $value['use_count_diff'];
            $list[$key]['lifetime'] = $value['lifetime'] == '0' ? L('no_limit') : $value['lifetime'];
            $list[$key]['starttime'] = $list[$key]['starttime'] == '0' ? L('no_limit') : time2format($value['starttime']);
            $list[$key]['endtime'] = $list[$key]['endtime'] == '0' ? L('no_limit') : time2format($value['endtime']);
            $field = $value['status'] == 1 ? 'active' : 'banned';
            $list[$key]['status'] = L('status_' . $field);
            $list[$key]['total'] = $total[$value['id']] > 0 ? $total[$value['id']] : 0;
            $list[$key]['act'] = $act[$value['id']] > 0 ? $act[$value['id']] : 0;
        }

        //遍历兑换码日志文件
        $dir = LOG_PATH . self::EXCHANGE_PATH;
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $this->download = $this->traverse($dir);

        //显示
//        dump($list);
        $this->list = $list;
        $this->display();//显示页面
    }

    private function traverse($path = '.')
    {
        $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
        while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
            if ($file == '.' || $file == '..' || is_dir($sub_dir)) {
                continue;
            } else {    //如果是文件,直接输出
                $arr = explode('_', $file);
                $download['name'] = $file;
                $download['url'] = $path . $file;
                $downloadList[$arr[0]][] = $download;
            }
        }
        foreach ($downloadList as $key => $value) {
            $list[$key] = array_reverse($value);
        }
        return $list;
    }

    //修改状态
    public function status()
    {

        if (false === D('UCExchangeType')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            C('G_ERROR', 'success');
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&p=" . $this->pg;
        $this->display("Public:jump");//显示页面

    }

    //生成兑换码
    public function create()
    {

        //类型是否存在
        $where['id'] = I('get.id');
        $type = D('UCExchangeType')->where($where)->find();
        if (empty($type)) {
            echo 'fail';
            return;
        }

        //兑换码集合
        $codeList = array();

        //基础数据
        $add['type'] = I('get.id');
        $add['ctime'] = time();
        $add['status'] = 0;

        //插入数据库数据
        $all = array();

        //判断用户是否需要自己指定的兑换码
        $code = I('get.code');
        if(!empty($code)){

            $count = D('UCExchange')->where("`code`='{$code}' && `type`<>{$type['id']}")->count();
            if($count > 0){
                echo L('exchange_code_exist');
                return;
            }

            $codeList[] = $add['code'] = $code;
            for ($j = 1; $j <= $type['repeat']; ++$j) {
                $all[] = $add;
            }

            //文件名
            $filename = I('get.id') . '_' . date('YmdHis') . '_' . $code;

        }else {

            //生成数量是否正确
            if (!(I('get.count') > 0)) {
                echo 'fail';
                return;
            }

            //前缀位数
            $prefixCount = I('get.prefix') >= 2 ? I('get.prefix') : 3;

            //基础数据
            $add['type'] = I('get.id');
            $add['ctime'] = time();
            $add['status'] = 0;

            //生成前缀
            $prefix = $this->createPrefix($prefixCount);

            //循环生成
            for ($i = 1; $i <= I('get.count'); ++$i) {
                $codeList[] = $add['code'] = $this->createCode($prefix, $codeList);//生成兑换码
                for ($j = 1; $j <= $type['repeat']; ++$j) {
                    $all[] = $add;
                }
            }

            //文件名
            $filename = I('get.id') . '_' . date('YmdHis') . '_' . $prefix . '_' . I('get.count');

        }

        //插入数据库
        if (false === D('UCExchange')->CreateAllData($all)) {
            echo 'fail';
            return;
        }

        //增加一个批次
        D('UCExchangeType')->IncreaseData($where, 'create_count');
        D('UCExchangeType')->IncreaseData($where, 'create_total', I('get.count'));

        //写文件
        $str = '';
        foreach ($codeList as $value) {
            $str .= $value . "\r\n";
        }

        //写入失败打印兑换码
        if (!write_exchange_log($str, self::EXCHANGE_PATH, $filename, 'txt')) {
            foreach ($codeList as $value) {
                echo $value . "<br />";
            }
        } else {//成功则跳转下载文件
            $url = LOG_PATH . self::EXCHANGE_PATH . $filename . '.txt';
            $file = $filename . '.txt';
            $this->download($url, $file);
        }

        return true;

    }

    //生成不重复code
    private function createPrefix($count)
    {
        $prefix = str_rand($count);
        $count = D('UCExchange')->where("`code` like '{$prefix}%'")->count();
        if ($count > 0) {
            $prefix = $this->createPrefix($count);
        }
        return $prefix;
    }

    //生成不重复code
    private function createCode($prefix, $codeList)
    {
        $str = $prefix . str_rand(4);
        if (in_array($str, $codeList)) {
            $str = $this->createCode($prefix, $codeList);
        }
        return $str;
    }

    //新增
    public function add()
    {

        if (!empty($_POST)) {

            //默认数据
            $add['gid'] = C('GAME_ID');
            $add['create_count'] = 0;
            $add['create_total'] = 0;
            $add['status'] = 1;

            //普通数据
            $add['name'] = I('post.name');
            $add['type'] = I('post.type');
            if ($add['type'] == 2) {
                $add['goods'] = 0;
                $add['level'] = 0;
            } else {
                $add['goods'] = I('post.goods');
                $add['level'] = I('post.level');
            }
            $add['repeat'] = I('post.repeat');
            $add['use_count'] = I('post.use_count');
            $add['use_count_diff'] = I('post.use_count_diff');
            $add['lifetime'] = I('post.lifetime');

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

            //时间
            if (!I('post.time_box')) {
                $add['starttime'] = strtotime(I('post.starttime'));
                $add['endtime'] = strtotime(I('post.endtime'));
            } else {
                if (in_array('starttime', I('post.time_box'))) {
                    $add['starttime'] = 0;
                } else {
                    $add['starttime'] = strtotime(I('post.starttime'));
                }
                if (in_array('endtime', I('post.time_box'))) {
                    $add['endtime'] = 0;
                } else {
                    $add['endtime'] = strtotime(I('post.endtime'));
                }
            }
            if (false === D('UCExchangeType')->CreateData($add)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

        }

        //显示
        $this->vMailList = D('DMail')->getExchangeList();
        $time['starttime'] = time2format(strtotime('today'));
        $time['endtime'] = time2format(strtotime('tomorrow') - 1);
        $this->time = $time;
        $this->assign('exchange_type_type', $this->type);
        $this->alert = get_error();
        $this->display();//显示页面

    }

    //编辑
    public function edit()
    {

        if (!empty($_POST)) {

            //默认数据
            $edit['id'] = I('post.id');

            //普通数据
            $edit['name'] = I('post.name');
            $edit['type'] = I('post.type');
            if ($edit['type'] == 2) {
                $edit['goods'] = 0;
                $edit['level'] = 0;
            } else {
                $edit['goods'] = I('post.goods');
                $edit['level'] = I('post.level');
            }
            $edit['repeat'] = I('post.repeat');
            $edit['use_count'] = I('post.use_count');
            $edit['use_count_diff'] = I('post.use_count_diff');
            $edit['lifetime'] = I('post.lifetime');

            //服务器
            if (I('post.server_type') == 'all') {
                $edit['server'] = 0;
            } else {
                $server = '';
                foreach (I('post.server') as $value) {
                    $server .= $value . '#';
                }
                $edit['server'] = substr($server, 0, -1);
            }

            //渠道
            if (I('post.channel_type') == 'all') {
                $edit['channel'] = 0;
            } else {
                $channel = '';
                foreach (I('post.channel') as $value) {
                    $channel .= $value . '#';
                }
                $edit['channel'] = substr($channel, 0, -1);
            }

            //时间
            if (!I('post.time_box')) {
                $edit['starttime'] = strtotime(I('post.starttime'));
                $edit['endtime'] = strtotime(I('post.endtime'));
            } else {
                if (in_array('starttime', I('post.time_box'))) {
                    $edit['starttime'] = 0;
                } else {
                    $edit['starttime'] = strtotime(I('post.starttime'));
                }
                if (in_array('endtime', I('post.time_box'))) {
                    $edit['endtime'] = 0;
                } else {
                    $edit['endtime'] = strtotime(I('post.endtime'));
                }
            }

            if (false === D('UCExchangeType')->UpdateData($edit)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

        }

        //显示
        $where['id'] = I('get.id');
        $info = D('UCExchangeType')->where($where)->find();
//        dump($info);

        //时间
        if ($info['starttime'] == 0) {
            $time['starttime'] = time2format(strtotime('today'));
        } else {
            $time['starttime'] = time2format($info['starttime']);
        }
        if ($info['starttime'] == 0) {
            if ($info['starttime'] > 0) {
                $time['endtime'] = time2format($info['starttime'] + 86399);
            } else {
                $time['endtime'] = time2format(strtotime('tomorrow') - 1);
            }
        } else {
            $time['endtime'] = time2format($info['endtime']);
        }

        $this->vMailList = D('DMail')->getExchangeList();
        $this->info = $info;
        $this->time = $time;
        $this->assign('exchange_type_type', $this->type);
        $this->alert = get_error();
        $this->display();//显示页面

    }

    //删除类型
    public function remove()
    {
        //查询已经生成并被使用的兑换码
        $where['type'] = I('get.id');
        $where['status'] = 1;
        $select = D('UCExchange')->field('id', true)->where($where)->select();
        if (!empty($select)) {
            //将已经生成并兑换成功的code放入log日志
            if (false === D('UCExchangeLog')->CreateAllData($select)) {
                C('G_ERROR', 'fail');
                goto end;
            } else {
                //删除code
                $where = array();
                $where['type'] = I('get.id');
                D('UCExchange')->DeleteList($where);
            }
        }

        //将类型设置为删除状态
        $where = array();
        $where['id'] = I('get.id');
        $data['create_total'] = D('UCExchange')->where("`type`='" . I('get.id') . "'")->count();
        $data['status'] = -1;
        if (false === D('UCExchangeType')->UpdateData($data, $where)) {
            C('G_ERROR', 'fail');
            goto end;
        }
        C('G_ERROR', 'success');

        end:
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&p={$this->pg}";
        $this->display("Public:jump");//显示页面

    }

}