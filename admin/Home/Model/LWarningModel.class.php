<?php
namespace Home\Model;

use Think\Model;

class LWarningModel extends BaseModel
{
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
        array('status', 0),
    );

    public function cLog($sid, $tid, $attr, $type, $value)
    {
        $add['sid'] = $sid;
        $add['tid'] = $tid;
        $add['attr'] = $attr;
        $add['type'] = $type;
        $add['value'] = $value;
        return $this->CreateData($add);
    }

    //修改状态
    public function changeStatus($id)
    {
        $data['status'] = 1;
        $where['id'] = $id;
        if (false === $this->UpdateData($data, $where)) {
            return false;
        }
        return true;
    }

}