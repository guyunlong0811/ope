<?php
namespace Home\Model;

use Think\Model;

class UCExchangeTypeModel extends BaseModel
{

    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'exchange_type';

    //获取数据
    public function getAll()
    {
        $select = $this->select();
        if (empty($select)) {
            return array();
        }
        foreach ($select as $value) {
            $list[$value['id']] = $value;
        }
        return $list;
    }

    //修改状态
    public function changeStatus($id)
    {

        $row = $this->ExecuteData("update `exchange_type` set `status`=1-`status` where `id`='{$id}'");
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