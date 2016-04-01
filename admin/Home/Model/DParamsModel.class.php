<?php
namespace Home\Model;

use Think\Model;

class DParamsModel extends BaseModel
{

    protected $connection = array(
        'db_type' => DB_STATIC_TYPE,
        'db_host' => DB_STATIC_HOST,
        'db_user' => DB_STATIC_USER,
        'db_pwd' => DB_STATIC_PWD,
        'db_port' => DB_STATIC_PORT,
        'db_name' => DB_STATIC_NAME,
        'db_charset' => DB_STATIC_CHARSET,
    );

}