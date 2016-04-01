<?php
namespace Home\Model;

use Think\Model;

class DataStatisticsMidModel extends BaseModel
{
    protected $connection = 'ADMIN_CONFIG';
    protected $_auto = array(
        array('utime', 'time', 3, 'function'),
    );

    //获取昨天新增用户数
    public function getYesterdayCount($sid)
    {
        $where['sid'] = $sid;
        $where['date_time'] = time2format(strtotime('yesterday'), 2);
        $count = $this->where($where)->sum('create_count');
        return $count ? $count : 0;
    }

    //获取昨天新增用户数
    public function getYesterdayActiveCount($sid)
    {
        $where['sid'] = $sid;
        $where['date_time'] = time2format(strtotime('yesterday'), 2);
        $count = $this->where($where)->sum('login_day1_member');
        return $count ? $count : 0;
    }

    //获取充值用户数
    public function getYesterdayPayUserCount($sid)
    {
        $where['sid'] = $sid;
        $where['date_time'] = time2format(strtotime('yesterday'), 2);
        $count = $this->where($where)->sum('pay_member');
        return $count ? $count : 0;
    }

    //获取新注册用户数据
    public function getNewUser($where)
    {
        $select = $this->field('`date_time`,sum(`create_count`) as `create_count`')->where($where)->group('`date_time`')->order('`date_time` ASC')->select();
        $data = array();
        if (!empty($select)) {
            foreach ($select as $value) {
                $data[$value['date_time']] = $value['create_count'];
            }
        }
        return $data;
    }

    //获取充值数据
    public function getPay($where)
    {
        return $this->field('`date_time`,sum(`pay_amount`) as `pay_amount`,sum(`pay_count`) as `pay_count`,sum(`pay_member`) as `pay_member`')->where($where)->group('`date_time`')->order('`date_time` ASC')->select();
    }

    //获取留存数据
    public function getRetention($where)
    {
        return $this->field('`date_time`,`sid`,`channel_id`,`create_count`,`day2`,`day3`,`day7`,`day15`,`day30`')->where($where)->order('`date_time` ASC, `channel_id` ASC, `sid` ASC')->select();
    }

    //获取登录数据
    public function getLogin($where)
    {
        return $this->field('`date_time`,`sid`,`channel_id`,`login_count`,`login_day1_member`,`login_day7_member`,`login_day30_member`')->where($where)->order('`date_time` ASC, `channel_id` ASC, `sid` ASC')->select();
    }

    //获取全部数据
    public function getAll($where)
    {
        return $this->where($where)->order('`date_time` ASC, `channel_id` ASC, `sid` ASC')->select();
    }

}