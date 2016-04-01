<?php
namespace Home\Model;

use Think\Model;

class UCServerModel extends BaseModel
{

    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'server';

    //获取数据
    public function getAll()
    {
        $order['channel_id'] = 'asc';
        $select = $this->order($order)->select();
        if (empty($select)) {
            return array();
        }
        return $select;
    }

    //获取单条数据
    public function getRow($id)
    {
        $where['id'] = $id;
        return $this->getRowCondition($where);
    }

    //修改状态
    public function changeStatus($id)
    {
        $row = $this->ExecuteData("update `server` set `status`=1-`status` where `id`='{$id}'");
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

    public function changeType($id, $type)
    {
        $where['id'] = $id;
        $data['type'] = $type;
        return $this->UpdateData($data, $where);
    }

    //修改维护状态
    public function changeTypeAll($list, $type, $channelId)
    {
        $where['logic_id'] = array('in', $list);
        $where['status'] = 1;
        if ($channelId > 0) {
            $where['channel_id'] = $channelId;
        }
        $data['type'] = $type;
        return $this->UpdateData($data, $where);
    }

    //获取渠道的所有服务器ID
    public function getLogicId($where)
    {
        return $this->where($where)->group('`logic_id`')->getField('logic_id', true);
    }

}