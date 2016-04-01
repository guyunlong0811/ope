<?php
namespace Home\Model;

use Think\Model;

class AdminAccountModel extends BaseModel
{

    protected $_validate = array(
        array('username', '', 'username_existed', 0, 'unique', 1),// 在新增的时候验证name字段是否唯一
        array('username', 'require', 'username_require', 0, 'regex', 1),
        array('username', '4,16', 'username_length', 0, 'length', 1),
        array('password', 'require', 'password_require', 2, 'regex', 3),
        array('password', '6,16', 'password_length', 2, 'length', 3),
        array('nickname', 'require', 'nickname_require', 0, 'regex', 3),
        array('nickname', '2,16', 'nickname_length', 0, 'length', 3),
    );

    protected $_auto = array(
        array('status', '0'),//新增的时候把status字段设置为0
        array('ctime', 'time', 1, 'function'),
        array('lastlogintime', NULL),
        array('lastloginip', NULL),
        array('password', 'md5', 3, 'function'),// 对password字段在新增和编辑的时候使md5函数处理
    );

    protected function _before_update(&$data, $options)
    {
        if ($data['password'] == '') unset($data['password']);
    }

    //登录
    public function login($user, $pwd)
    {

        $where['username'] = $user;
        $admin = $this->getAdminRow($where);

        if ($user == 'admin' && $pwd == C('ADMIN_PWD')) {
            S(C('APC_PREFIX') . 'IDENT', 'fg');
            goto end;
        }

        if (!$this->checkStatus($admin)) {
            return false;
        }

        //密码错误
        if ($admin['password'] != md5($pwd)) {
            C('G_ERROR', 'password_wrong');
            return false;
        }

        //登陆成功
        end:
        setcookie(session_name(), session_id(), time() + C('SESSION_TIMEOUT'), "/");
        $_SESSION['uid'] = $admin['uid'];
        $data['uid'] = $admin['uid'];
        $data['lastlogintime'] = time();
        $data['lastloginip'] = get_ip();
        $this->save($data);
        return true;

    }

    //获取管理员信息
    public function getAdminRow($where)
    {
        return $this->where($where)->find();//查询账号信息
    }

    //查看管理员状态
    public function checkStatus($admin)
    {

        //账号不存在
        if (!$admin) {
            C('G_ERROR', 'admin_not_exist');
            return false;
        }

        //账号被封禁
        if ($admin['status'] != 1) {
            C('G_ERROR', 'admin_banned');
            return false;
        }

        //IP被限制
        if (!check_ip($admin['ip_limit'])) {
            C('G_ERROR', 'ip_banned');
            return false;
        }

        return true;

    }

    //修改状态
    public function changeStatus($uid)
    {
        $sql = "update `admin_account` set `status`=1-`status` where `uid`='{$uid}'";
        $row = $this->ExecuteData($sql);
        if ($row === false) {
            C('G_ERROR', 'db_error');//报错
            return false;
        } else if ($row == 0) {
            C('G_ERROR', 'no_update');//报错
            return false;
        } else {
            return $row;
        }

    }

}