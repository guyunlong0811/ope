<?php
namespace Home\Model;

use Think\Model;

class ServerScriptModel extends BaseModel
{

    protected $_validate = array(
        array('name', '', 'server_existed', 0, 'unique', 1),// 在新增的时候验证name字段是否唯一
        array('url', '', 'server_existed', 0, 'unique', 1),// 在新增的时候验证name字段是否唯一
    );

    protected $_auto = array(
        array('status', '0'),//新增的时候把status字段设置为0
    );

    //修改状态
    public function changeStatus($id)
    {
        $row = $this->ExecuteData("update `server_script` set `status`=1-`status` where `id`='{$id}'");
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

    //获取全部信息
    public function getALl()
    {
        $select = $this->select();
        $list = array();
        foreach($select as $value){
            $list[$value['id']] = $value['url'];
        }
        return $list;
    }

}