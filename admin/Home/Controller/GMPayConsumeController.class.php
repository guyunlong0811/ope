<?php
namespace Home\Controller;

use Think\Controller;

class GMPayConsumeController extends BInitController
{

    private $type1 = array(
        1 => 'activity_pay',
        2 => 'activity_consume',
    );

    private $type2 = array(
        1 => 'activity_total',
        2 => 'activity_daily',
    );

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_pay_consume';
        $this->vChannel = D('UCChannel')->getAll();//查询渠道信息

        $this->vFieldConfig = C('FIELD');
        $this->vItemConfig = D('Static')->access('item');
        $partner = D('Static')->access('partner_group');
        foreach ($partner as $key => $value) {
            $arr = current($value);
            $config[$key] = $arr['name'];
        }
        $this->vPartnerConfig = $config;
        $this->vEmblemConfig = D('Static')->access('emblem');
        $this->assign('bonus_type', $this->mBonusType);
    }

    //显示列表
    public function index()
    {
        //查询信息
//        $order = array('group' => 'desc', 'index' => 'asc',);
        $order = array('starttime' => 'desc', 'group' => 'desc');
        $select = D('DEvent')->order($order)->select();

        //遍历信息
        $list = array();
        foreach ($select as $key => $value) {

            if (!isset($list[$value['group']])) {

                $list[$value['group']]['group'] = $value['group'];

                //服务器
                $list[$value['group']]['server'] = $value['server'];
                if ($value['server'] == '0') {
                    $list[$value['group']]['server_name'] = L('all_server');
                } else {
                    $arrServer = explode('#', $value['server']);
                    $list[$value['group']]['server_name'] = '';
                    foreach ($arrServer as $sval) {
                        if (!empty($sval)) {
                            $list[$value['group']]['server_name'] .= 'S' . $sval . '&';
                        }
                    }
                    $list[$value['group']]['server_name'] = substr($list[$value['group']]['server_name'], 0, -1);
                }

                //渠道
                $list[$value['group']]['channel'] = $value['channel'];
                if ($value['channel'] == '0') {
                    $list[$value['group']]['channel_name'] = L('all_channel');
                } else {
                    $arrChannel = explode('#', $value['channel']);
                    $list[$value['group']]['channel_name'] = '';
                    foreach ($arrChannel as $cval) {
                        if (!empty($cval)) {
                            $list[$value['group']]['channel_name'] .= $this->vChannel[$cval] . '&';
                        }
                    }
                    $list[$value['group']]['channel_name'] = substr($list[$value['group']]['channel_name'], 0, -1);
                }

                $list[$value['group']]['type1'] = $value['type1'];
                $list[$value['group']]['type1_name'] = $this->type1[$value['type1']];
                $list[$value['group']]['type2'] = $value['type2'];
                $list[$value['group']]['type2_name'] = $this->type2[$value['type2']];
                $list[$value['group']]['name'] = $value['name'];
                $list[$value['group']]['icon'] = $value['icon'];
                $list[$value['group']]['des'] = $value['des'];
                $list[$value['group']]['starttime'] = time2format($value['starttime']);
                $list[$value['group']]['endtime'] = $value['endtime'] == 0 ? L('none') : time2format($value['endtime']);

            }

        }

        //显示
        $this->vTable = $list;
        $this->display();//显示页面
    }

    //显示子活动列表
    public function groupIndex()
    {
        //查询信息
        $where['group'] = I('get.group');
        $order = array('index' => 'asc',);
        $select = D('DEvent')->where($where)->order($order)->select();

        //遍历信息
        $list = array();
        foreach ($select as $key => $value) {

            $arr['group'] = $value['group'];
            $arr['index'] = $value['index'];
            $arr['name'] = $value['name'];
            $arr['value'] = $value['value'];
            $arr['receive_max'] = $value['receive_max'];
            $arr['pt_activity_id'] = $value['pt_activity_id'];
            $arr['status'] = $value['status'] == 1 ? L('status_active') : L('status_banned');

            for ($i = 1; $i <= 8; ++$i) {
                $arr['bonus_' . $i . '_type'] = $value['bonus_' . $i . '_type'];
                $arr['bonus_' . $i . '_value_1'] = $value['bonus_' . $i . '_value_1'];
                $arr['bonus_' . $i . '_value_2'] = $value['bonus_' . $i . '_value_2'];
                if ($arr['bonus_' . $i . '_type'] == '0') {
                    $arr['bonus_' . $i] = L($this->mBonusType[$value['bonus_' . $i . '_type']]);
                } else {
                    $arr['bonus_' . $i] = L($this->mBonusType[$value['bonus_' . $i . '_type']]) . '-' . $this->getBonusId($value['bonus_' . $i . '_type'], $value['bonus_' . $i . '_value_1']) . ':' . $value['bonus_' . $i . '_value_2'] . ';';
                }
            }
            $list[] = $arr;

        }

        //显示
        $this->vTable = $list;
        $this->display('group_index');//显示页面
    }

    //编辑
    public function groupEdit()
    {
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

            //时间
            $save['name'] = I('post.name');
            $save['type1'] = I('post.type1');
            $save['type2'] = I('post.type2');
            $save['icon'] = I('post.icon');
            $save['des'] = I('post.des');
            $save['starttime'] = strtotime(I('post.starttime'));
            $save['endtime'] = strtotime(I('post.endtime'));

            $where['group'] = I('post.group');

            if (false === D('DEvent')->UpdateData($save, $where)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_event');
            }
        }

        //处理当前数据
        $data = D('DEvent')->where("`group`='" . I('get.group') . "'")->find();
        $data['starttime'] = time2format($data['starttime']);
        $data['endtime'] = time2format($data['endtime']);
        $data['des'] = str_replace(array("\r\n", "\r", "\n"), '\n', $data['des']);

        //显示
        $this->vData = $data;
        $this->vType1 = $this->type1;
        $this->vType2 = $this->type2;
        $this->alert = get_error();
        $this->display('group_edit');//显示页面
    }

    //编辑
    public function edit()
    {
        if (!empty($_POST)) {

            $save['value'] = I('post.value');
            $save['receive_max'] = I('post.receive_max');
            $save['pt_activity_id'] = I('post.pt_activity_id');
            for ($i = 1; $i <= 8; ++$i) {
                $save['bonus_' . $i . '_type'] = I('post.bonus_' . $i . '_type');
                $save['bonus_' . $i . '_value_1'] = I('post.bonus_' . $i . '_value_1');
                $save['bonus_' . $i . '_value_2'] = I('post.bonus_' . $i . '_value_2');
            }

            $where['index'] = I('post.index');

            if (false === D('DEvent')->UpdateData($save, $where)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_event');
            }
        }

        //处理当前数据
        $data = D('DEvent')->where("`index`='" . I('get.id') . "'")->find();

        //显示
        $this->vData = $data;
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //新增活动组
    public function groupAdd()
    {
        if (!empty($_POST)) {

            //构造group
            $groupBaseStart = I('post.type1') * 100000 + I('post.type2') * 1000 + 1;
            $groupBaseEnd = I('post.type1') * 100000 + I('post.type2') * 1000 + 999;
            $where['group'] = array('between', array($groupBaseStart, $groupBaseEnd));
            $group = D('DEvent')->where($where)->max('`group`');
            $add['group'] = $group > 0 ? $group + 1 : $groupBaseStart;

            //构造index
            $add['index'] = $add['group'] * 100 + 1;

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
            $add['name'] = I('post.name');
            $add['type1'] = I('post.type1');
            $add['type2'] = I('post.type2');
            $add['icon'] = I('post.icon');
            $add['des'] = I('post.des');
            $add['starttime'] = strtotime(I('post.starttime'));
            $add['endtime'] = strtotime(I('post.endtime'));

            $add['value'] = I('post.value');
            $add['receive_max'] = I('post.receive_max');
            $add['pt_activity_id'] = I('post.pt_activity_id');
            for ($i = 1; $i <= 8; ++$i) {
                $add['bonus_' . $i . '_type'] = I('post.bonus_' . $i . '_type');
                $add['bonus_' . $i . '_value_1'] = I('post.bonus_' . $i . '_value_1') ? I('post.bonus_' . $i . '_value_1') : 0;
                $add['bonus_' . $i . '_value_2'] = I('post.bonus_' . $i . '_value_2') ? I('post.bonus_' . $i . '_value_2') : 0;
            }

            if (false === D('DEvent')->CreateData($add)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_event');
            }
        }

        //时间
        $start = strtotime('tomorrow');
        $time['start'] = time2format($start);
        $end = $start + 7 * 86400 - 1;
        $time['end'] = time2format($end);

        //显示
        $this->vType1 = $this->type1;
        $this->vType2 = $this->type2;
        $this->vTime = $time;
        $this->alert = get_error();
        $this->display('group_add');//显示页面
    }

    //新增活动
    public function add()
    {
        //处理当前数据
        $data = D('DEvent')->where("`group`='" . I('get.group') . "'")->order("`index` DESC")->find();

        if (!empty($_POST)) {

            $add = $data;

            $add['value'] = I('post.value');
            $add['receive_max'] = I('post.receive_max');
            $add['pt_activity_id'] = I('post.pt_activity_id');
            for ($i = 1; $i <= 8; ++$i) {
                $add['bonus_' . $i . '_type'] = I('post.bonus_' . $i . '_type');
                $add['bonus_' . $i . '_value_1'] = I('post.bonus_' . $i . '_value_1') ? I('post.bonus_' . $i . '_value_1') : 0;
                $add['bonus_' . $i . '_value_2'] = I('post.bonus_' . $i . '_value_2') ? I('post.bonus_' . $i . '_value_2') : 0;
            }

            $add['index'] = $data['index'] + 1;
            $add['status'] = 0;

            if (false === D('DEvent')->CreateData($add)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_event');
            }
        }

        //显示
        $this->vData = $data;
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //修改状态
    public function status()
    {
        if (false === D('DEvent')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_event');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/groupIndex&group=" . I('get.group');
        $this->display("Public:jump");//显示页面
    }

    //修改状态
    public function groupOpen()
    {
        $data['status'] = 1;
        $where['group'] = I('get.group');
        if (false === D('DEvent')->UpdateData($data, $where)) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_event');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面
    }

    //修改状态
    public function groupClose()
    {
        $data['status'] = 0;
        $where['group'] = I('get.group');
        if (false === D('DEvent')->UpdateData($data, $where)) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_event');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面
    }

    //删除
    public function groupDelete()
    {
        $where['group'] = I('get.group');
        if (false === D('DEvent')->DeleteList($where)) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_event');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面
    }

    //删除
    public function delete()
    {
        if (false === D('DEvent')->DeleteData(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_event');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/groupIndex&group=" . I('get.group');
        $this->display("Public:jump");//显示页面
    }

    //复制活动组&子活动
    public function groupCopy()
    {
        if (!empty($_GET)) {

            //读取需要复制的组信息
            $where['group'] = I('get.group');
            $list = D('DEvent')->where($where)->order('`index` asc')->select();

            //取出一条
            $info = $list[0];

            //构造group
            $groupBaseStart = $info['type1'] * 100000 + $info['type2'] * 1000 + 1;
            $groupBaseEnd = $info['type1'] * 100000 + $info['type2'] * 1000 + 999;
            $where['group'] = array('between', array($groupBaseStart, $groupBaseEnd));
            $group = D('DEvent')->where($where)->max('`group`');
            $newGroup = $group > 0 ? $group + 1 : $groupBaseStart;

            //更新group&&index
            foreach($list as $key => $value){
                $list[$key]['index'] = $newGroup . substr($value['index'], -2, 2);
                $list[$key]['group'] = $newGroup;
                $list[$key]['starttime'] = 0;
                $list[$key]['endtime'] = 0;
                $list[$key]['status'] = 0;
            }

            //插入数据库
            if (false === D('DEvent')->CreateAllData($list)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_event');
            }

        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面
    }

}