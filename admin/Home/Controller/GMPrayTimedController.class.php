<?php
namespace Home\Controller;

use Think\Controller;

class GMPrayTimedController extends BInitController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_pray_timed';
    }

    //显示列表
    public function index()
    {
        $model = D('DPray');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {

            if ($value['server'] == '0') {
                $list[$key]['server_name'] = L('all_server');
            } else {
                $arrChannel = explode('#', $value['server']);
                $list[$key]['server_name'] = '';
                foreach ($arrChannel as $sval) {
                    if (!empty($sval)) {
                        $list[$key]['server_name'] .= 'S'.$sval . '&';
                    }
                }
                $list[$key]['server_name'] = substr($list[$key]['server_name'], 0, -1);
            }

            $list[$key]['starttime'] = time2format($value['starttime']);
            $list[$key]['endtime'] = $value['endtime'] == 0 ? L('none') : time2format($value['endtime']);
            $list[$key]['ctime'] = time2format($value['ctime']);
            $field = $value['status'] == 1 ? 'active' : 'banned';
            $list[$key]['status'] = L('status_' . $field);
        }
        //显示
        $this->vTable = $list;
        $this->display();//显示页面
    }

    //修改状态
    public function status()
    {
        if (false === D('DPray')->changeStatus(I('get.id'))) {
            C('G_ERROR', 'fail');
        } else {
            $this->clearApc('d_pray');
            C('G_ERROR', 'success');
        }
        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
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

            $add['partner'] = I('post.partner');
            $add['rank_type'] = I('post.rank_type');
            $add['des'] = I('post.des');
            $add['point_1'] = I('post.point_1');
            $add['point_10'] = I('post.point_10');

            //时间
            $add['starttime'] = strtotime(I('post.starttime'));
            $add['endtime'] = strtotime(I('post.endtime'));

            //新增数据
            if (false === D('DPray')->CreateData($add)) {
                C('G_ERROR', D('DPray')->getError());
            } else {
                C('G_ERROR', 'success');
            }
        }

        //获取伙伴组列表
        $partner = D('Static')->access('partner_group');
        $vPartnerGroupList = '';
        foreach ($partner as $key => $value) {
            $arr = current($value);
            $vPartnerGroupList .= "{$key} - {$arr['name']}\\n";
        }
        $this->vPartnerGroupList = $vPartnerGroupList;

        //显示
        $time['starttime'] = time2format(strtotime('today'));
        $time['endtime'] = time2format(strtotime('+3 days', strtotime($time['starttime'])));
        $this->vTime = $time;
        $this->alert = get_error();
        $this->display();//显示页面
    }

    //编辑
    public function edit()
    {
        if (!empty($_POST)) {

            $where['index'] = I('post.index');

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

            $save['partner'] = I('post.partner');
            $save['rank_type'] = I('post.rank_type');
            $save['des'] = I('post.des');
            $save['point_1'] = I('post.point_1');
            $save['point_10'] = I('post.point_10');

            //时间
            $save['starttime'] = strtotime(I('post.starttime'));
            $save['endtime'] = strtotime(I('post.endtime'));

            //新增数据
            if (false === D('DPray')->UpdateData($save, $where)) {
                C('G_ERROR', D('DPray')->getError());
            } else {
                $this->clearApc('d_pray');
                C('G_ERROR', 'success');
            }

        }

        //获取伙伴组列表
        $partner = D('Static')->access('partner_group');
        $vPartnerGroupList = '';
        foreach ($partner as $key => $value) {
            $arr = current($value);
            $vPartnerGroupList .= "{$key} - {$arr['name']}\\n";
        }
        $this->vPartnerGroupList = $vPartnerGroupList;

        //处理当前数据
        $data = D('DPray')->where("`index`='" . I('get.id') . "'")->find();
        $data['starttime'] = time2format($data['starttime']);
        $data['endtime'] = time2format($data['endtime']);

        //显示
        $this->vData = $data;
        $this->alert = get_error();
        $this->display();//显示页面
    }

}