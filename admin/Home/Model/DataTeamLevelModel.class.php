<?php
namespace Home\Model;

use Think\Model;

class DataTeamLevelModel extends BaseModel
{
    protected $connection = 'ADMIN_CONFIG';

    public function getAll($where)
    {
        $data = array();
        $select = $this->field("`date_time`,`level`,sum(`count`) as `count`")->where($where)->group('`date_time`,`level`')->order('`date_time` ASC,`level` ASC')->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $data[$value['date_time']][$value['level']] = $value['count'];
            }
        }
        return $data;
    }

}