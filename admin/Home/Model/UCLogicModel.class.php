<?php
namespace Home\Model;

use Think\Model;

class UCLogicModel extends BaseModel
{

    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'logic';

    //获取数据
    public function getAll($gid, $where = array())
    {
        $where['gid'] = $gid;
        $order['sid'] = 'asc';
        $select = $this->where($where)->order($order)->select();
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

    //获取单条数据
    public function getRowBySid($sid)
    {
        $where['sid'] = $sid;
        return $this->getRowCondition($where);
    }

    //修改状态
    public function changeStatus($id)
    {
        $row = $this->ExecuteData("update `logic` set `status`=1-`status` where `id`='{$id}'");
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

    //修改状态
    public function changeActivation($id)
    {
        $row = $this->ExecuteData("update `logic` set `activation`=1-`activation` where `id`='{$id}'");
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

    //全部关闭
    public function getOpenServer($where = array())
    {
        $where['gid'] = C('GAME_ID');
        $where['status'] = 1;
        return $this->field('`id`,`sid`')->where($where)->select();
    }

}