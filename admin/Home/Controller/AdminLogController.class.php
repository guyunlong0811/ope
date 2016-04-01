<?php
namespace Home\Controller;

use Think\Controller;

class AdminLogController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'user';
        $this->vTitle = 'admin_log';
    }

    //显示列表
    public function index()
    {
        //查询所有信息
        $model = M('AdminLog');
        $field = array('`admin_log`.`id`,`admin_log`.`uid`,`admin_log`.`controller`,`admin_log`.`action`,`admin_log`.`server`,`admin_log`.`sql`,`admin_log`.`ip`,`admin_log`.`ctime`,`admin_account`.`username`');
        $order = array('`admin_log`.`ctime`' => 'desc',);
        $page = $this->page($model);
        $list = $model->field($field)->join('LEFT JOIN `admin_account` ON `admin_account`.`uid` = `admin_log`.`uid`')->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['username'] = $value['username'];
            $data[$key]['controller'] = $value['controller'];
            $data[$key]['action'] = $value['action'];
            if ($value['server'] == 0) {
                $data[$key]['server'] = 'admin';
            } else {
                $data[$key]['server'] = $this->mServerList[$value['server']]['dbname'];
            }
            $data[$key]['ip'] = $value['ip'];
            $data[$key]['ctime'] = time2format($value['ctime']);
            if ($value['sql'] != '') {
                $data[$key]['sql'] = explode(';', $value['sql']);
            }
        }

        //显示
        $this->vTable = $data;
        $this->display();//显示页面
    }

}