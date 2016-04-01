<?php
namespace Home\Model;

use Think\Model;

class DataConsumeModel extends BaseModel
{
    protected $connection = 'ADMIN_CONFIG';
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
    );

    //获取昨天充值用户数
    public function getYesterdayCount($sid)
    {
        $where['sid'] = $sid;
        $where['date_time'] = time2format(strtotime('yesterday'), 2);
        $count = $this->where($where)->getField('pay_user');
        return $count ? $count : 0;
    }

}