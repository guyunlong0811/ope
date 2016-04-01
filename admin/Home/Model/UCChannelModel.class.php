<?php
namespace Home\Model;

use Think\Model;

class UCChannelModel extends BaseModel
{

    protected $connection = 'UC_CONFIG';
    protected $trueTableName = 'channel';

    protected $_validate = array(
        array('name', '', 'channel_name_existed', 0, 'unique', 1),
    );

    //获取数据
    public function getAll()
    {
        $where['gid'] = C('GAME_ID');
        $select = $this->where($where)->select();
        if (empty($select)) {
            return array();
        }
        foreach ($select as $value) {
            $list[$value['channel_id']] = $value['name'];
        }
        return $list;
    }

    //修改状态
    public function changeStatus($id)
    {
        $gid = C('GAME_ID');
        return $this->ExecuteData("update `channel` set `status`=1-`status` where `gid`='{$gid}' && `channel_id`='{$id}'");
    }

}