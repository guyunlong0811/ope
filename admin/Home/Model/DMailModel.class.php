<?php
namespace Home\Model;

use Think\Model;

class DMailModel extends BaseModel
{

    const MIN_INDEX = 100000;
    const MAX_INDEX = 200000;

    protected $_auto = array(
        array('type', '2'),
        array('wildcards', ''),
        array('open_script', ''),
        array('expires_type', 1),
        array('behave', 'exchange'),
    );

    protected $connection = array(
        'db_type' => DB_STATIC_TYPE,
        'db_host' => DB_STATIC_HOST,
        'db_user' => DB_STATIC_USER,
        'db_pwd' => DB_STATIC_PWD,
        'db_port' => DB_STATIC_PORT,
        'db_name' => DB_STATIC_NAME,
        'db_charset' => DB_STATIC_CHARSET,
    );

    //获取兑换邮件最新index
    public function getExchangeIncIndex()
    {
        $index = $this->where("`behave`='exchange'")->max('`index`');
        if (!$index) {
            $index = self::MIN_INDEX;
        }
        return $index + 1;
    }

    //获取兑换码邮件列表
    public function getExchangeList()
    {
        $select = $this->field("`index`,`name`")->where("`behave`='exchange'")->select();
        if (empty($select)) {
            return array();
        } else {
            foreach ($select as $value) {
                $list[$value['index']] = $value['name'];
            }
            return $list;
        }
    }

}