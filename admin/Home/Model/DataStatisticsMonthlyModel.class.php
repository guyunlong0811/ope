<?php
namespace Home\Model;

use Think\Model;

class DataStatisticsMonthlyModel extends BaseModel
{
    protected $connection = 'ADMIN_CONFIG';

    //获取全部数据
    public function getAll($where)
    {
        return $this->where($where)->order('`month` ASC, `sid` ASC, `channel_id` ASC')->select();
    }

}