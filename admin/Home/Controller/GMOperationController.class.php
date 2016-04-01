<?php
namespace Home\Controller;

use Think\Controller;

class GMOperationController extends BInitController
{

    private $vModule = array(
        //收益增加

        //PVE
        '1001' => 'module_profit_instance_normal',
        '1002' => 'module_profit_instance_advance',
        '1003' => 'module_profit_god',
        '1004' => 'module_profit_abyss',
        '1005' => 'module_profit_cat',
        '1006' => 'module_profit_babel',
        //PVP
        '1101' => 'module_profit_arena',
        '1102' => 'module_profit_life_death',
        //Other
        '1201' => 'module_profit_pray',

        //付出减少
        '2001' => 'module_discount_pray',
        '2002' => 'module_discount_vality',
        '2003' => 'module_discount_shop_arena',
        '2004' => 'module_discount_shop_mystery',

    );

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_operation';
        $this->vChannel = D('UCChannel')->getAll();//查询渠道信息
        $this->selectChannel();
    }

    //显示列表
    public function index()
    {

        //服务器
        $serverId  = I('get.server_id');
        if (!empty($serverId) && $serverId >= 0) {
            $where['server'] = $serverId;
        }
        //渠道
        $channelId = I('get.channel_id');
        if (!empty($channelId) && $channelId >= 0) {
            $where['channel'] = $channelId;
        }
        //模块
        $module = I('get.module');
        if (!empty($module) && $module != '0') {
            $where['module'] = $module;
        }

        $model = D('DOperation');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model, 'sql', $where);
        $list = $model->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
        foreach ($list as $key => $value) {

            //服务器
            $list[$key]['server'] = $value['server'];
            if ($value['server'] == '0') {
                $list[$key]['server_name'] = L('all_server');
            } else {
                $arrServer = explode('#', $value['server']);
                $list[$key]['server_name'] = '';
                foreach ($arrServer as $sval) {
                    if (!empty($sval)) {
                        $list[$key]['server_name'] .= 'S' . $sval . '&';
                    }
                }
                $list[$key]['server_name'] = substr($list[$key]['server_name'], 0, -1);
            }

            //渠道
            $list[$key]['channel'] = $value['channel'];
            if ($value['channel'] == '0') {
                $list[$key]['channel_name'] = L('all_channel');
            } else {
                $arrChannel = explode('#', $value['channel']);
                $list[$key]['channel_name'] = '';
                foreach ($arrChannel as $cval) {
                    if (!empty($cval)) {
                        $list[$key]['channel_name'] .= $this->vChannel[$cval] . '&';
                    }
                }
                $list[$key]['channel_name'] = substr($list[$key]['channel_name'], 0, -1);
            }

            $list[$key]['module_name'] = $this->vModule[$value['module']];
            $list[$key]['rate'] = $this->vModule[$value['rate']] . '%';
            $list[$key]['starttime'] = time2format($value['starttime']);
            $list[$key]['endtime'] = time2format($value['endtime']);
            $list[$key]['status'] = $value['status'] == '1' ? L('status_active') : L('status_banned');
        }

        //显示
        $this->vTable = $list;
        $this->assign('vModule', $this->vModule);
        $this->display();//显示页面
    }

    //修改状态
    public function status()
    {
        if (false === D('DOperation')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_operation');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&channel_id=" . I('get.channel_id') . "&module=" . I('get.module') . "&p={$this->pg}";
        $this->display("Public:jump");//显示页面
    }

    //删除
    public function delete()
    {
        if (false === D('DOperation')->DeleteData(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_operation');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&channel_id=" . I('get.channel_id') . "&module=" . I('get.module') . "&p={$this->pg}";
        $this->display("Public:jump");//显示页面
    }

    //删除
    public function deleteAll()
    {
        //服务器
        $serverId  = I('get.server_id');
        if (!empty($serverId) && $serverId >= 0) {
            $where['server'] = $serverId;
        }
        //渠道
        $channelId = I('get.channel_id');
        if (!empty($channelId) && $channelId >= 0) {
            $where['channel'] = $channelId;
        }
        //模块
        $module = I('get.module');
        if (!empty($module) && $module != '0') {
            $where['module'] = $module;
        }

        if (!isset($where)) {
            if (false === D('DOperation')->TruncateTable('d_operation')) {
                C('G_ERROR', 'fail');
            } else {
                $this->clearApc('d_operation');
                C('G_ERROR', 'success');
            }
        } else {
            if (false === D('DOperation')->DeleteList($where)) {
                C('G_ERROR', 'fail');
            } else {
                $this->clearApc('d_operation');
                C('G_ERROR', 'success');
            }
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index&server_id=" . I('get.server_id') . "&channel_id=" . I('get.channel_id') . "&module=" . I('get.module') . "&p={$this->pg}";
        $this->display("Public:jump");//显示页面
    }

    //新增
    public function add()
    {

        if (!empty($_POST)) {

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

            $add['module'] = I('post.module');
            $add['rate'] = I('post.rate');
            $add['starttime'] = strtotime(I('post.starttime'));
            $add['endtime'] = strtotime(I('post.endtime'));
            $add['ctime'] = time();

            if (false === D('DOperation')->CreateData($add)) {
                C('G_ERROR', D('DOperation')->getError());
            } else {
                C('G_ERROR', 'success');
            }
        }
        //显示
        $time['starttime'] = time2format();
        $time['endtime'] = time2format(strtotime('+15 days'));
        $this->vTime = $time;
        $this->assign('vModule', $this->vModule);
        $this->alert = get_error();
        $this->display();//显示页面
    }

}