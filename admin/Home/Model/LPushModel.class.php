<?php
namespace Home\Model;

use Think\Model;

class LPushModel extends BaseModel
{
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
    );
}