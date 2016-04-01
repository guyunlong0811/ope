<?php
namespace Home\Model;

use Think\Model;

class UCExchangeLogModel extends BaseModel
{
    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'exchange_log';
}