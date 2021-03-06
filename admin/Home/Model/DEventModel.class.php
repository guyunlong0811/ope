<?php
namespace Home\Model;

use Think\Model;

class DEventModel extends BaseModel
{

    protected $_auto = array(
        array('status', '0'),
    );

    protected $connection = array(
        'db_type' => DB_STATIC_TYPE,
        'db_host' => DB_STATIC_HOST,
        'db_user' => DB_STATIC_USER,
        'db_pwd' => DB_STATIC_PWD,
        'db_port' => DB_STATIC_PORT,
        'db_name' => DB_STATIC_NAME,
        'db_charset' => DB_STATIC_CHARSET,
    );

    //修改状态
    public function changeStatus($id)
    {
        return $this->ExecuteData("update `d_event` set `status`=1-`status` where `index`='{$id}'");
    }

}