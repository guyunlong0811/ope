<?php
namespace Home\Model;

use Think\Model;

class DNoticeModel extends BaseModel
{

    protected $_auto = array(
        array('status', '0'),
        array('ctime', 'time', 1, 'function'),
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
        $row = $this->ExecuteData("update `d_notice` set `status`=1-`status` where `index`='{$id}'");
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