<?php
namespace Home\Model;

use Think\Model;

class DataRemainMidModel extends BaseModel
{
    protected $connection = 'ADMIN_CONFIG';
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
    );
}