<?php
namespace Home\Model;

use Think\Model;

class AdminServerModel extends BaseModel
{

    //登录
    public function isPermission($uid, $sid)
    {
        $where['uid'] = $uid;
        $list = $this->where($where)->getField('server', true);
        if (in_array('0', $list) || in_array($sid, $list)) {
            return true;
        } else {
            return false;
        }
    }

    //获取管理员所有的权限
    public function getList($uid)
    {
        $where['uid'] = $uid;
        $select = $this->where($where)->getField('server', true);
        return $select;
    }

}