<?php
namespace Home\Model;

use Think\Model;

class UCLoginModel extends BaseModel
{
    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'login';
}