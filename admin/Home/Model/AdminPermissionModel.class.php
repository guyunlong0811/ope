<?php
namespace Home\Model;

use Think\Model;

class AdminPermissionModel extends BaseModel
{

    //登录
    public function isPermission($uid, $c, $a)
    {
        $where['uid'] = $uid;
        $where['controller'] = $c;
        $where['action'] = $a;
        $count = $this->where($where)->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    //获取管理员所有的权限
    public function getList($uid)
    {
        $where['uid'] = $uid;
        $select = $this->where($where)->select();
        if (empty($select))
            return array();

        foreach ($select as $value) {
            $data[$value['controller']][] = $value['action'];
        }
        return $data;
    }

}