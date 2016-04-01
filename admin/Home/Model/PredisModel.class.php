<?php
namespace Home\Model;

use Think\Model;
//use Think;
use Predis;

class PredisModel
{

    private $mSid;
    private $mClient;
    private $mList = array();

    public function __construct()
    {
        $this->mSid = C('G_SID');
        $this->mClient = $this->get_predis($this->mSid);
        $this->mList = C('REDIS_DB');
    }

    //获取实例
    public function cli($dbName, $sid = null)
    {

        if (!is_null($sid) && $this->mSid != $sid) {
            $this->mSid = $sid;
            $this->mClient = $this->get_predis($sid);
            $this->mList = C('REDIS_DB');
        }

        $this->mClient->select($this->mList[$dbName]);
        return $this->mClient;
    }

    //获取Predis客户端对象
    private function get_predis($sid = null)
    {
        $list = S(C('APC_PREFIX') . 'server');
        $sid = $sid == null ? C('G_SID') : $sid;
        if (empty($sid)) {
            return false;
        }
        $redis = $list[$sid]['redis'];
        C('REDIS_DB', $redis);
        $server = array('host' => $redis['host'], 'port' => $redis['port'], 'database' => $redis['game']);
//        Think\Log::record(json_encode($list), 'DEBUG');
//        Think\Log::record(json_encode($server), 'DEBUG');
        require_once(APP_PATH . '../Predis/Autoloader.php');
        Predis\Autoloader::register();
        $client = new Predis\Client($server);
        return $client;
    }

}