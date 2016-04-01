<?php
namespace Home\Model;

use Think\Model;

class LMailSendModel extends BaseModel
{
    protected $_auto = array(
        array('send_time', 'time', 1, 'function'),
    );
}