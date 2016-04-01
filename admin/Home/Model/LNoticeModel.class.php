<?php
namespace Home\Model;

use Think\Model;

class LNoticeModel extends BaseModel
{
    protected $_auto = array(
        array('ctime', 'time', 1, 'function'),
        array('utime', 'time', 3, 'function'),
    );
}