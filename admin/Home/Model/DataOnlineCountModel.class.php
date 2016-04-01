<?php
namespace Home\Model;

use Think\Model;

class DataOnlineCountModel extends BaseModel
{
    protected $connection = 'ADMIN_CONFIG';
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
    );

    //获取当前在线用户数
    public function getCurrentCount($sid)
    {
        $where['sid'] = $sid;
        $count = $this->where($where)->order('`ctime` DESC')->getField('count');
        return $count ? $count : 0;
    }
}