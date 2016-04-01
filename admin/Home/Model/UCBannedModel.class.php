<?php
namespace Home\Model;

use Think\Model;

class UCBannedModel extends BaseModel
{

    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'banned';

    //解封
    public function open($id)
    {
        $data['id'] = $id;
        $data['endtime'] = time();
        return $this->UpdateData($data);
    }

}