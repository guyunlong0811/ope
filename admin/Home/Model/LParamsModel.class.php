<?php
namespace Home\Model;

use Think\Model;

class LParamsModel extends BaseModel
{
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
    );
}