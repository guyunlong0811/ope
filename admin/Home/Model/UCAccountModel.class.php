<?php
namespace Home\Model;

use Think\Model;

class UCAccountModel extends BaseModel
{
    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'account';
}