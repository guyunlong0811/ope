<?php
namespace Home\Controller;

use Think\Controller;

class LeagueInfoController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'group';
        $this->vTitle = 'league_info';
    }

    public function index()
    {

        if (!empty($_GET)) {

            $this->server_id = I('get.server_id');
            $where = array();
            if (I('get.league_id')) {
                $where['id'] = I('get.league_id');
            }else{
                if (I('get.league_name')) {
                    $where['league_name'] = array('LIKE', '%' . I('get.league_name') . '%');
                }
            }
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $model = M()->db(I('get.server_id'), $dbConfig)->table('g_league');
            $page = $this->page($model, 'sql', $where);
            $list = $model->db(I('get.server_id'))->table('g_league')->page($this->pg . ',' . $page->listRows)->where($where)->order("`center_level` DESC")->select();
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $list[$key]['ctime'] = time2format($value['ctime']);
                }
            }else{
                $list = array();
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
            $where['id'] = I('get.id');
            $row = M()->db(I('get.server_id'), $dbConfig)->table('g_league')->where($where)->find();
            if (!empty($row)) {
                //时间格式
                $row['recommend'] = $row['recommend'] == 0 ? '/' : time2format($row['recommend']);
                $row['ctime'] = time2format($row['ctime']);
                $this->vTable = $row;
            } else {
                $this->redirect('Home/LeagueInfo/index');
            }

            //显示
            $this->alert = get_error();
            $this->display();//显示页面

        } else {
            $this->redirect('Home/LeagueInfo/index');
        }

    }

    public function edit()
    {
        if (IS_AJAX) {
            $dbConfig = change_db_server(I('get.server_id'), 'master');
            $where['id'] = I('get.id');
            if(false === M()->db(1, $dbConfig)->table('g_league')->where($where)->setField(I('get.attr'), I('get.value'))){
                $alert = L('fail');
            }else{
                $alert = L('success');
            }
            echo $alert;
        }
    }


}