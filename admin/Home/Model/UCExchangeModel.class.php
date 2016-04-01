<?php
namespace Home\Model;

use Think\Model;

class UCExchangeModel extends BaseModel
{
    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'exchange';
}