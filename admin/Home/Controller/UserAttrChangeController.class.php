<?php
namespace Home\Controller;

use Think\Controller;

class UserAttrChangeController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'group';
        $this->vTitle = 'user_attr_change';

        $config = C('BEHAVE');
        foreach ($config as $value) {
            $behave[$value['code']] = $value['message'];
        }
        $this->vBehave = $behave;
        $this->vAttr = array('diamond_pay', 'diamond_free', 'gold', 'exp', 'vality', 'level', 'skill_point',);
    }

    public function index()
    {

        $this->isSubmit = 0;

        if (!empty($_GET)) {

            $this->isSubmit = 1;
            $this->server_id = I('get.server_id');
            if (!I('get.server_id') || !I('get.team_id')) {
                $this->accountInfo = array();
            } else {
                $this->server_id = I('get.server_id');
                $this->team_id = $where['tid'] = I('get.team_id');
                if (I('get.type') != 'all') {
                    $where['attr'] = I('get.type');
                }

                $dbConfig = change_db_server($this->server_id, 'master');
                $field = array(
                    'tid' => 'user_tid',
                    'attr' => 'user_attr',
                    'value' => 'user_value',
                    'before' => 'user_before',
                    'after' => 'user_after',
                    'behave' => 'user_behave',
                    'ctime' => 'user_account_ctime',
                );

                $model = M()->db($this->server_id, $dbConfig)->table('l_team');
                $order = array('user_account_ctime' => 'desc',);
                $page = $this->page($model, 'sql', $where);
                $row = $model->page($this->pg . ',' . $page->listRows)->table('l_team')->field($field)->where($where)->order($order)->select();
                $data = array();
                if (!empty($row)) {
                    foreach ($row as $key => $value) {
                        $row[$key]['user_attr'] = L($value['user_attr']);
                        $row[$key]['user_behave'] = $this->vBehave[$value['user_behave']];
                        $row[$key]['user_account_ctime'] = time2format($value['user_account_ctime']);
                    }
                    $data = $row;
                }

            }

            $this->vTable = $data;

        }


        //显示
        $this->alert = get_error();
        $this->display();//显示页面
    }

}