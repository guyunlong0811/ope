<?php
namespace Home\Controller;

use Think\Controller;

class ScriptController extends BaseController
{

    private $mSid;
    private $mDBConfig;
    private $mDate;
    private $mModel;
    private $mTable = '';
    private $mError = array();
    private $mChannelList = array();

    public function _initialize()
    {
        parent::_initialize();

        //服务器
        $this->mSid = I('get.sid');
        C('G_SID', $this->mSid);
        if (I('get.utime') == 'mid') {
            C('DAILY_UTIME', '00:00:00');
            $this->mTable = 'Mid';
        }
        $this->mDBConfig = change_db_server($this->mSid, 'master');
        $this->mModel = M()->db($this->mSid, $this->mDBConfig);
    }

    public function test()
    {
        $this->mDate = strtotime('2015-02-01');
        $this->mSid = 101;
        C('G_SID', $this->mSid);
        $this->mDBConfig = change_db_server($this->mSid, 'master');
        $this->mModel = M()->db($this->mSid, $this->mDBConfig);
        $this->mChannelList = array();
        foreach ($this->mServerList[$this->mSid]['channel'] as $value) {
            $this->mChannelList[] = $value['channel_id'];
        }

        $rs = $this->monthlyStatistics();

        dump($rs);
        return;

    }

    public function orderError()
    {
        $this->mDBConfig = change_db_server(101, 'master');
        $this->mModel->db(1, $this->mDBConfig);
        $select = $this->mModel->db(1)->table('l_order')->where("`status` > 0")->select();
        $error = array();
        $money = 0;
        foreach ($select as $value) {
            $verify = json_decode($value['verify'], true);
            if ($value['price'] > ($verify['charge_money'] * 100)) {
                $arr['tid'] = $value['tid'];
                $arr['order_id'] = $value['order_id'];
                $arr['price'] = $value['price'] / 100;
                $arr['money'] = $verify['charge_money'];
                $money += ($arr['price'] - $arr['money']);
                $error[] = $arr;
            }
        }

        dump($money);
        dump($error);
    }

    //守护进程
//    public function exec()
//    {
//        $type = I('get.type'];
//        foreach ($this->mServerList as $key => $value) {
//
//            if($type == 'dailyMid'){
//                $exec = "php daily.php Home {$key} mid";
//            }else{
//                $exec = "php {$type}.php Home {$key}";
//            }
////            echo $exec;
//            echo exec($exec);
//            echo "\n";
//        }
//        return true;
//    }

    //全服调用地址
    public function exec()
    {
        if (!I('get.interval')) {
            return false;
        }

        //心跳协议
        if (I('get.interval') == 'Push') {
            $this->execPush();
            return;
        }

        if (!I('get.utime')) {
            $utime = '';
        } else {
            $utime = "&utime=" . I('get.utime');
        }

        //遍历服务器
        $link = array();
        $serverList = S(C('APC_PREFIX') . 'server');
        $serverIdList = array();
        foreach ($serverList as $sid => $value) {
            $serverIdList[] = $sid;
            $link[] = OPE_URL . '?s=/Home/Script/exec' . I('get.interval') . '&sid=' . $sid . $utime;
        }

        //发送请求
        $rs = $this->curl_multi_link($link);

        //解析结果
        foreach ($rs as $key => $value) {
            echo $value;
            echo "\n";
//            $arr = json_decode($value, true);
//            if($arr['status'] == 'fail'){
//                $error[] = $serverIdList[$key];
//            }
        }

        //返回
//        $this->scriptReturn($error);
        return;
    }

    //并发curl链接
    private function curl_multi_link($link)
    {

        //发起并发链接
        $mh = curl_multi_init();

        //循环发送
        $count = count($link);
        for ($i = 0; $i < $count; ++$i) {
            $ch[$i] = curl_init($link[$i]);
            curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch[$i], CURLOPT_TIMEOUT, 15);
            curl_setopt($ch[$i], CURLOPT_CONNECTTIMEOUT, 15);
            curl_multi_add_handle($mh, $ch[$i]);
        }

        //发送连接
        $active = true;
        $mrc = CURLM_OK;
        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) == -1) {
                usleep(100);
            }
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        //获取返回
        $rs = array();
        for ($i = 0; $i < $count; ++$i) {
            $rs[$i] = curl_multi_getcontent($ch[$i]);
            curl_multi_remove_handle($mh, $ch[$i]);
            curl_close($ch[$i]);
        }

        //关闭并发连接
        curl_multi_close($mh);
        return $rs;
    }

    //推送
    public function execPush()
    {

        //一次推送数量
        $pushNum = 100;
        $timeout = 60;

        //查询推送信息
        $list = M('PushIos')->order('`id` asc')->limit($pushNum)->select();

        //没有需要推送
        if(empty($list)){
            return;
        }

        //证书
        $ssl = 'ssl://gateway.push.apple.com:2195';
//        $ssl = 'ssl://gateway.sandbox.push.apple.com:2195';
        $pem = COMMON_PATH . 'Common/Certificate/ifsc_push.pem';

        //apple
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
//        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        //建立连接
        $fp = stream_socket_client($ssl, $err, $errstr, $timeout, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        //验证连接是否成功
        if (!$fp) {
            C('G_ERROR', "Failed to connect $err $errstr");
            return false;
        }

        //遍历消息
        foreach ($list as $value) {

            $body['aps'] = array(
                'alert' => $value['msg'],
                "badge" => 1,
                'sound' => 'default'
            );

            // Encode the payload as JSON
            $payload = json_encode($body);

            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $value['token']) . pack('n', strlen($payload)) . $payload;

            // Send it to the server
            fwrite($fp, $msg, strlen($msg));

            //获取消息最大ID
            $maxId = $value['id'];

        }

        //关闭连接
        fclose($fp);

        //删除推送信息
        M('PushIos')->where("`id`<={$maxId}")->delete();

        return true;

    }

    public function execHourly()
    {
        if (!$this->hourlyOnlineCount()) {
            $this->mError[] = 'hourlyOnlineCount';
        }

        //返回
        $this->scriptReturn($this->mError);
        return;
    }

    public function execDaily()
    {

        $log = array('dailyWarning', 'dailyStatistics', 'dailyPayLevel', 'dailyPayFirst', 'dailyPayRank', 'dailyCurrency', 'dailyPray');
        $game = array('dailyRemain', 'dailyTeamLevel', 'dailyPayRankTotal', 'dailyTopBehave', 'dailyLostBehave', 'monthlyStatistics', 'dailyPayCash',);

        foreach ($this->mServerList[$this->mSid]['channel'] as $value) {
            $this->mChannelList[] = $value['channel_id'];
        }

        //获取昨天日期
        if (!I('get.date')) {
            $this->mDate = time2format(strtotime('yesterday'), 2);
        } else {
            $this->mDate = I('get.date');
        }

        foreach ($log as $value) {
            if (I('get.utime') == 'mid' && ($value == 'dailyWarning')) {
                continue;
            }
            if (!$this->$value()) {
                $this->mError[] = $value;
            }
        }

        if (!I('get.date')) {
            foreach ($game as $value) {
                if (I('get.utime') == 'mid' && ($value == 'dailyLostBehave')) {
                    continue;
                }
                if (!$this->$value()) {
                    $this->mError[] = $value;
                }
            }
        }

        //返回
        $this->scriptReturn($this->mError);
        return;
    }

    //每小时在线玩家数统计
    private function hourlyOnlineCount()
    {

        $flag = true;

        //查询最近的整点
        $date = date('Y-m-d');
        $hour = date('H');
        $hourTime = $date . ' ' . $hour . ':00:00';
        $hourTime = strtotime($hourTime);

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['hour'] = $hourTime;
        $count = D('DataOnlineCount')->where($where)->count();
        if (!($count > 0)) {

            //读取redis数据
            $keyList = D('Predis')->cli('game', $this->mSid)->keys('u:*');
            $add = $where;
            $add['count'] = count($keyList);
            if (false === D('DataOnlineCount')->CreateData($add)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //每日用户留存统计
    private function dailyStatistics()
    {

        $flag = true;

        //时间
        $now = $this->mNow;
        $day32Ago = time2format(strtotime("-32 days {$this->mDate}"), 2);

        //查询数据
        $select = D('DataStatistics' . $this->mTable)->where("`sid`='{$this->mSid}' && `date_time`>='{$day32Ago}'")->order('`date_time` DESC')->select();
        foreach ($select as $value) {
            $data[$value['date_time']][$value['channel_id']] = $value;
        }

        //查询今天注册人数&最大id
        $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
        $endtime = $starttime + 86399;

        //如果没有今天的数据
        if (!isset($data[$this->mDate])) {

            //默认数据
            $add['day2'] = -1;
            $add['day3'] = -1;
            $add['day7'] = -1;
            $add['day15'] = -1;
            $add['day30'] = -1;
            $add['ctime'] = $add['utime'] = $now;
            $add['sid'] = $this->mSid;
            $add['date_time'] = $this->mDate;

            //获取最大战队ID
            $max = $this->mModel->db($this->mSid)->table('g_team')->where("`ctime`<={$endtime} && `ctime`<>'0'")->order('`tid` DESC')->getField('tid');
            $add['max_tid'] = $max ? $max : 0;

            //创建空数据
            $channelData = array();
            foreach ($this->mChannelList as $value) {
                $channelData[$value]['channel_id'] = $value;
                $channelData[$value]['all_count'] = 0;
                $channelData[$value]['create_count'] = 0;
                $channelData[$value]['lost_count'] = 0;
                $channelData[$value]['pay_amount'] = 0;
                $channelData[$value]['pay_count'] = 0;
                $channelData[$value]['pay_member'] = 0;
                $channelData[$value]['first_pay_member'] = 0;
                $channelData[$value]['pay_old_login_member'] = 0;
                $channelData[$value]['login_count'] = 0;
                $channelData[$value]['login_day1_member'] = 0;
                $channelData[$value]['login_day7_member'] = 0;
                $channelData[$value]['login_day30_member'] = 0;
            }

            //查询总帐号数
            $select = $this->mModel->db($this->mSid)->table('g_team')->field('`channel_id`,count(`tid`) as `count`')->where("`ctime` between '1' and '{$endtime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['all_count'] = $value['count'];
            }

            //查询创角数据
            $select = $this->mModel->db($this->mSid)->table('g_team')->field('`channel_id`,count(`tid`) as `count`')->where("`ctime` between '{$starttime}' and '{$endtime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['create_count'] = $value['count'];
            }

            //查询流失数据
            $lostTime = $endtime - (3 * 86400);
            $select = $this->mModel->db($this->mSid)->table('g_team')->field('`channel_id`,count(`tid`) as `count`')->where("`ctime` > 0 && `vality_utime` between '1' and '{$lostTime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['lost_count'] = $value['count'];
            }

            //充值情况
            $select = $this->mModel->db($this->mSid)->table('l_order')->field("`channel_id`,sum(`price`) as `amount`,count(`id`) as `count`,count(distinct(`tid`)) as `member`")->where("`status` > 0 && `endtime` between '{$starttime}' and '{$endtime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['pay_amount'] = $value['amount'];
                $channelData[$value['channel_id']]['pay_count'] = $value['count'];
                $channelData[$value['channel_id']]['pay_member'] = $value['member'];
            }

            //首冲情况
            $select = $this->mModel->db($this->mSid)->table('g_vip')->field("`g_team`.`channel_id`,count(`g_vip`.`tid`) as `count`")->join("`g_team` on `g_team`.`tid`=`g_vip`.`tid`")->where("`g_team`.`ctime` > 0 && `g_vip`.`first_pay_time` between '{$starttime}' and '{$endtime}'")->group('`g_team`.`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['first_pay_member'] = $value['count'];
            }

            //老充值用户当日数量(不重复)
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`g_team`.`channel_id`,count(`g_vip`.`tid`) as `count`")->join("`g_vip` on `g_team`.`tid`=`g_vip`.`tid`")->where("`g_vip`.`first_pay_time` between '1' and '{$starttime}'")->group('`g_team`.`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['pay_old_login_member'] = $value['count'];
            }

            //重复登录情况
            $select = $this->mModel->db($this->mSid)->table('l_login')->field("`g_team`.`channel_id`,count(`l_login`.`id`) as `count`")->join("`g_team` on `g_team`.`tid`=`l_login`.`tid`")->where("`l_login`.`ctime` between '{$starttime}' and '{$endtime}'")->group('`g_team`.`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['login_count'] = $value['count'];
            }

            //当天
//            $select = $this->mModel->db($this->mSid)->table('l_login')->field("`g_team`.`channel_id`,count(distinct(`l_login`.`tid`)) as `count`")->join("`g_team` on `g_team`.`tid`=`l_login`.`tid`")->where("`l_login`.`ctime` between '{$starttime}' and '{$endtime}'")->group('`g_team`.`channel_id`')->select();
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`channel_id`,count(`tid`) as `count`")->where("`vality_utime` between '{$starttime}' and '{$endtime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['login_day1_member'] = $value['count'];
            }

            //7天
            $startDay7 = $starttime - (6 * 86400);
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`channel_id`,count(`tid`) as `count`")->where("`vality_utime` between '{$startDay7}' and '{$endtime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['login_day7_member'] = $value['count'];
            }

            //30天
            $startDay30 = $starttime - (29 * 86400);
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`channel_id`,count(`tid`) as `count`")->where("`vality_utime` between '{$startDay30}' and '{$endtime}'")->group('`channel_id`')->select();
            foreach ($select as $value) {
                $channelData[$value['channel_id']]['login_day30_member'] = $value['count'];
            }

            //全局数据
            $all = array();
            foreach ($channelData as $value) {
                $all[] = array_merge($add, $value);
            }

            //创建数据
            if (!empty($all) && false === D('DataStatistics' . $this->mTable)->db(0)->CreateAllData($all)) {
                $flag = false;
            }

        }

        /****************************** 计算留存 *****************************/

        //时间
        foreach ($this->mRetentionDay as $value) {
            $day = $value - 1;
            $time['day' . $value] = time2format(strtotime("-{$day} days " . $this->mDate), 2);
        }

        //sid
        $updateWhere['sid'] = $this->mSid;

        //遍历
        foreach ($time as $dayKey => $date) {

            //如果没有数据
            if (!isset($data[$date])) {
                continue;
            }

            //遍历
            foreach ($data[$date] as $channel_id => $channelRow) {

                //数据为0
                if ($channelRow['create_count'] == 0) {
                    continue;
                }

                //数据已经查询
                if ($channelRow[$dayKey] >= 0) {
                    continue;
                }

                //条件
                $updateWhere['date_time'] = $date;
                $updateWhere['channel_id'] = $channel_id;

                //计算当天注册玩家的最小/大Tid
                $max = $channelRow['max_tid'];//最大

                //上一天数据
                $lastDay = time2format(strtotime($date) - 86400, 2);
                $maxYesterday = isset($data[$lastDay][$channel_id]) ? $data[$lastDay][$channel_id]['max_tid'] : 1000000;//昨天最大
                if ($maxYesterday >= $max) {
                    continue;
                }

                $min = $maxYesterday + 1;
//                $updateData[$dayKey] = $this->mModel->db($this->mSid)->table('g_team')->where("`tid`>'{$min}' && `tid`<='{$max}' && `channel_id`='{$channel_id}' && `vality_utime` >= '{$starttime}'")->count('tid');
//                $updateData[$dayKey] = $this->mModel->db($this->mSid)->table('l_login')->join('`g_team` ON `g_team`.`tid`=`l_login`.`tid`')->where("`l_login`.`tid`>'{$min}' && `l_login`.`tid`<='{$max}' && `g_team`.`channel_id`='{$channel_id}' && `l_login`.`ctime` between '{$starttime}' and '{$endtime}'")->count('distinct(`l_login`.`tid`)');
                $updateData[$dayKey] = $this->mModel->db($this->mSid)->table('g_team')->where("`tid` between '{$min}' and '{$max}' && `channel_id`='{$channel_id}' && `vality_utime` between '{$starttime}' and '{$endtime}'")->count('tid');

//                $lastStarttime = strtotime($date);
//                $lastEndtime = $lastStarttime + 86399;
//                $uidList = D('UCAccount')->where("`channel_id`='{$channel_id}' && `ctime` between '{$lastStarttime}' and '{$lastEndtime}'")->getField('uid', true);
////                dump(D('UCAccount')->getLastSql());
//                $whereLogin['gid'] = C('GAME_ID');
//                $whereLogin['sid'] = $this->mSid;
//                $whereLogin['uid'] = array('in', $uidList);
//                $whereLogin['ctime'] = array('between', array($starttime, $endtime));
//                $updateData[$dayKey] = D('UCLogin')->where($whereLogin)->count('distinct(`uid`)');

                //修改数据库
                if (false === D('DataStatistics' . $this->mTable)->db(0)->UpdateData($updateData, $updateWhere)) {
                    $flag = false;
                }
                unset($updateData);

            }

        }

        /****************************** 计算留存 *****************************/

        //返回
        return $flag;

    }

    //每日数据统计
    private function dailyRemain()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $count = M('DataRemain' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {
            $add = $where;
            $where = array();
            $where['ctime'] = array('gt', 0);
            //查询
            $field = "count(`tid`) as `team_count`,sum(`diamond_pay`) as `diamond_pay`,sum(`diamond_free`) as `diamond_free`,sum(`gold`) as `gold`";
            $row = $this->mModel->db($this->mSid)->table('g_team')->field($field)->where($where)->find();
            $add['team'] = $row['team_count'] > 0 ? $row['team_count'] : 0;
            $add['diamond_pay'] = $row['diamond_pay'] > 0 ? $row['diamond_pay'] : 0;
            $add['diamond_free'] = $row['diamond_free'] > 0 ? $row['diamond_free'] : 0;
            $add['gold'] = $row['gold'] > 0 ? $row['gold'] : 0;
            //插入数据
            if (false === D('DataRemain' . $this->mTable)->CreateData($add)) {
                $flag = false;
            }
        }

        //返回
        return $flag;

    }

    //每日战队等级统计
    private function dailyTeamLevel()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $count = M('DataTeamLevel' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $addBase = $where;
            $addBase['ctime'] = $this->mNow;

            //创建数据
            $select1 = $this->mModel->db($this->mSid)->table('g_team')->field("`level`,`channel_id`,count(`tid`) as `count`")->where("`ctime`>0")->group('`channel_id`,`level`')->select();
            $createList = array();
            foreach ($select1 as $value) {
                $createList[$value['channel_id']][$value['level']] = $value['count'];
            }

            //流失数据
            $lostTime = strtotime($this->mDate . ' ' . C('DAILY_UTIME')) - (3 * 86400);
            $select2 = $this->mModel->db($this->mSid)->table('g_team')->field("`channel_id`,`level`,count(`tid`) as `count`")->where("`ctime` > 0 && `vality_utime` between '1' and '{$lostTime}'")->group('`channel_id`,`level`')->select();
            $lostList = array();
            foreach ($select2 as $value) {
                $lostList[$value['channel_id']][$value['level']] = $value['count'];
            }

            //获取服务器所有渠道
//            $channelList = $this->mModel->db($this->mSid)->table('g_team')->field("distinct(`channel_id`)")->group('`channel_id`')->select();
            $maxLevel = D('Static')->access('params', 'TEAM_LEVEL_MAX');
            $all = array();
            foreach ($this->mChannelList as $value) {
                for ($i = 1; $i <= $maxLevel; ++$i) {
                    $add = $addBase;
                    $add['channel_id'] = $value;
                    $add['level'] = $i;
                    $add['count'] = isset($createList[$value][$i]) ? $createList[$value][$i] : 0;
                    $add['lost'] = isset($lostList[$value][$i]) ? $lostList[$value][$i] : 0;
                    $all[] = $add;
                }
            }

            //插入数据
            if (!empty($all) && false === D('DataTeamLevel' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //每日充值等级
    private function dailyPayLevel()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;

        $count = M('DataPayLevel' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;

            //查询充值RMB情况
            $sql = "select `gt`.`channel_id`,`gt`.`level`,sum(`lo`.`price`) as `amount`,count(`lo`.`tid`) as `count`,count(distinct(`lo`.`tid`)) as `member` from `g_team` as `gt`,`l_order` as `lo` where `gt`.`tid`=`lo`.`tid` && `gt`.`ctime` > 0 && `lo`.`status` > 0 && `lo`.`endtime` between '{$starttime}' and '{$endtime}' group by `gt`.`channel_id`,`gt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataPrice = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataPrice[$value['channel_id']][$value['level']] = $value;
                }
            }

            //充值水晶产出
            $sql = "select `gt`.`channel_id`,`lt`.`level`,sum(`lt`.`value`) as `count` from `g_team` as `gt`,`l_team` as `lt` where `gt`.`tid`=`lt`.`tid` && `lt`.`attr`='diamond_pay' && `gt`.`ctime` > 0 && `lt`.`ctime` between '{$starttime}' and '{$endtime}' && `lt`.`value` > 0 group by `gt`.`channel_id`,`lt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataDiamondPayProduce = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataDiamondPayProduce[$value['channel_id']][$value['level']] = abs($value['count']);
                }
            }

            //充值水晶消耗
            $sql = "select `gt`.`channel_id`,`lt`.`level`,sum(`lt`.`value`) as `count` from `g_team` as `gt`,`l_team` as `lt` where `gt`.`tid`=`lt`.`tid` && `lt`.`attr`='diamond_pay' && `gt`.`ctime` > 0 && `lt`.`ctime` between '{$starttime}' and '{$endtime}' && `lt`.`value` < 0 group by `gt`.`channel_id`,`lt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataDiamondPayRecover = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataDiamondPayRecover[$value['channel_id']][$value['level']] = abs($value['count']);
                }
            }

            //免费水晶产出
            $sql = "select `gt`.`channel_id`,`lt`.`level`,sum(`lt`.`value`) as `count` from `g_team` as `gt`,`l_team` as `lt` where `gt`.`tid`=`lt`.`tid` && `lt`.`attr`='diamond_free' && `gt`.`ctime` > 0 && `lt`.`ctime` between '{$starttime}' and '{$endtime}' && `lt`.`value` > 0 group by `gt`.`channel_id`,`lt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataDiamondFreeProduce = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataDiamondFreeProduce[$value['channel_id']][$value['level']] = abs($value['count']);
                }
            }

            //免费水晶消耗
            $sql = "select `gt`.`channel_id`,`lt`.`level`,sum(`lt`.`value`) as `count` from `g_team` as `gt`,`l_team` as `lt` where `gt`.`tid`=`lt`.`tid` && `lt`.`attr`='diamond_free' && `gt`.`ctime` > 0 && `lt`.`ctime` between '{$starttime}' and '{$endtime}' && `lt`.`value` < 0 group by `gt`.`channel_id`,`lt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataDiamondFreeRecover = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataDiamondFreeRecover[$value['channel_id']][$value['level']] = abs($value['count']);
                }
            }

            //金币产出
            $sql = "select `gt`.`channel_id`,`lt`.`level`,sum(`lt`.`value`) as `count` from `g_team` as `gt`,`l_team` as `lt` where `gt`.`tid`=`lt`.`tid` && `lt`.`attr`='gold' && `gt`.`ctime` > 0 && `lt`.`ctime` between '{$starttime}' and '{$endtime}' && `lt`.`value` > 0 group by `gt`.`channel_id`,`lt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataGoldProduce = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataGoldProduce[$value['channel_id']][$value['level']] = abs($value['count']);
                }
            }

            //金币消耗
            $sql = "select `gt`.`channel_id`,`lt`.`level`,sum(`lt`.`value`) as `count` from `g_team` as `gt`,`l_team` as `lt` where `gt`.`tid`=`lt`.`tid` && `lt`.`attr`='gold' && `gt`.`ctime` > 0 && `lt`.`ctime` between '{$starttime}' and '{$endtime}' && `lt`.`value` < 0 group by `gt`.`channel_id`,`lt`.`level`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            $dataGoldRecover = array();//现金情况
            if (!empty($select)) {
                foreach ($select as $value) {
                    $dataGoldRecover[$value['channel_id']][$value['level']] = abs($value['count']);
                }
            }

            //获取当前服务器最高等级
            $maxLevel = $this->mModel->db($this->mSid)->table('g_params')->where("`index`='TEAM_MAX_LEVEL'")->getField('value');

            $all = array();
            foreach ($this->mServerList[$this->mSid]['channel'] as $value) {
                for ($i = 1; $i <= $maxLevel; ++$i) {
                    $add = $addBase;
                    $add['channel_id'] = $value['channel_id'];
                    $add['level'] = $i;
                    $add['amount'] = isset($dataPrice[$add['channel_id']][$add['level']]['amount']) ? $dataPrice[$add['channel_id']][$add['level']]['amount'] : 0;
                    $add['member'] = isset($dataPrice[$add['channel_id']][$add['level']]['member']) ? $dataPrice[$add['channel_id']][$add['level']]['member'] : 0;
                    $add['count'] = isset($dataPrice[$add['channel_id']][$add['level']]['count']) ? $dataPrice[$add['channel_id']][$add['level']]['count'] : 0;
                    $add['diamond_pay_produce'] = isset($dataDiamondPayProduce[$add['channel_id']][$add['level']]) ? $dataDiamondPayProduce[$add['channel_id']][$add['level']] : 0;
                    $add['diamond_pay_recover'] = isset($dataDiamondPayRecover[$add['channel_id']][$add['level']]) ? $dataDiamondPayRecover[$add['channel_id']][$add['level']] : 0;
                    $add['diamond_free_produce'] = isset($dataDiamondFreeProduce[$add['channel_id']][$add['level']]) ? $dataDiamondFreeProduce[$add['channel_id']][$add['level']] : 0;
                    $add['diamond_free_recover'] = isset($dataDiamondFreeRecover[$add['channel_id']][$add['level']]) ? $dataDiamondFreeRecover[$add['channel_id']][$add['level']] : 0;
                    $add['gold_produce'] = isset($dataGoldProduce[$add['channel_id']][$add['level']]) ? $dataGoldProduce[$add['channel_id']][$add['level']] : 0;
                    $add['gold_recover'] = isset($dataGoldRecover[$add['channel_id']][$add['level']]) ? $dataGoldRecover[$add['channel_id']][$add['level']] : 0;
                    $all[] = $add;
                }
            }

            //插入数据
            if (!empty($all) && false === D('DataPayLevel' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //每日首次充值情况
    private function dailyPayFirst()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataPayFirstCash' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;
            $sql = "select `gt`.`channel_id`,`gv`.`first_pay` as `cash_id`,count(`gv`.`tid`) as `count` from `g_team` as `gt`,`g_vip` as `gv` where `gt`.`tid`=`gv`.`tid` && `gv`.`first_pay` > 0 && `gt`.`ctime` > 0 && `gv`.`first_pay_time` between '{$starttime}' and '{$endtime}' group by `gt`.`channel_id`,`gv`.`first_pay`";
            $select = $this->mModel->db($this->mSid)->query($sql);

            if (!empty($select)) {
                $all = array();
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['channel_id'] = $value['channel_id'];
                    $add['cash_id'] = $value['cash_id'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
                //插入数据
                if (!empty($all) && false === D('DataPayFirstCash' . $this->mTable)->CreateAllData($all)) {
                    $flag = false;
                }
            }

        }

        //等级对应人数
        $count = M('DataPayFirstLevel' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;
            $sql = "select `gt`.`channel_id`,`gv`.`first_pay_level` as `level`,count(`gv`.`tid`) as `count` from `g_team` as `gt`,`g_vip` as `gv` where `gt`.`tid`=`gv`.`tid` && `gv`.`first_pay_level` > 0 && `gt`.`ctime` > 0 && `gv`.`first_pay_time` between '{$starttime}' and '{$endtime}' group by `gt`.`channel_id`,`gv`.`first_pay_level`";
            $select = $this->mModel->db($this->mSid)->query($sql);

            if (!empty($select)) {
                $all = array();
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['channel_id'] = $value['channel_id'];
                    $add['level'] = $value['level'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
                //插入数据
                if (!empty($all) && false === D('DataPayFirstLevel' . $this->mTable)->CreateAllData($all)) {
                    $flag = false;
                }
            }

        }

        //返回
        return $flag;

    }

    //每日首次充值情况
    private function dailyPayCash()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataPayCash' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;
            $sql = "select `channel_id`,`cash_id`,count(`id`) as `count` from `l_order` where `status` = '1' && `endtime` between '{$starttime}' and '{$endtime}' group by `channel_id`,`cash_id`";
            $select = $this->mModel->db($this->mSid)->query($sql);

            if (!empty($select)) {
                $all = array();
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['channel_id'] = $value['channel_id'];
                    $add['cash_id'] = $value['cash_id'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
                //插入数据
                if (!empty($all) && false === D('DataPayCash' . $this->mTable)->CreateAllData($all)) {
                    $flag = false;
                }
            }

        }

        //返回
        return $flag;

    }

    //每日充值排名
    private function dailyPayRank()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataPayRank' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;
            $all = array();
            foreach ($this->mChannelList as $value) {
                $sql = "select `gt`.`channel_id`,`lo`.`tid`,`gt`.`nickname`,`gt`.`level`,sum(`lo`.`price`) as `pay`,count(`lo`.`tid`) as `count` from `g_team` as `gt`,`l_order` as `lo` where `gt`.`tid`=`lo`.`tid` && `lo`.`status` > 0 && `lo`.`endtime` between '{$starttime}' and '{$endtime}' && `gt`.`channel_id` = '{$value}' group by `gt`.`channel_id`,`lo`.`tid` order by `pay` DESC limit 30";
                $select = $this->mModel->db($this->mSid)->query($sql);
                if (!empty($select)) {
                    foreach ($select as $val) {
                        if ($val['count'] > 0) {
                            $add = $addBase;
                            $add['channel_id'] = $val['channel_id'];
                            $add['tid'] = $val['tid'];
                            $add['nickname'] = $val['nickname'];
                            $add['level'] = $val['level'];
                            $add['pay'] = $val['pay'];
                            $add['count'] = $val['count'];
                            $all[] = $add;
                        }
                    }
                }
            }

            //插入数据
            if (!empty($all) && false === D('DataPayRank' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //每日充值排名
    private function dailyPayRankTotal()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataPayRankTotal' . $this->mTable)->where($where)->count();
        if ($count == 0) {

            $all = array();
            foreach ($this->mChannelList as $value) {
                $sql = "select `gt`.`channel_id`,`gv`.`tid`,`gt`.`nickname`,`gt`.`level`,`gv`.`pay`,`gv`.`pay_count`,`gv`.`utime` from `g_team` as `gt`,`g_vip` as `gv` where `gt`.`tid`=`gv`.`tid` && `gt`.`channel_id` = '{$value}' && `gv`.`pay_count`>0 order by `gv`.`pay` DESC limit 30";
                $select = $this->mModel->db($this->mSid)->query($sql);
                if (!empty($select)) {
                    foreach ($select as $val) {
                        $add = $addBase;
                        $add['channel_id'] = $val['channel_id'];
                        $add['tid'] = $val['tid'];
                        $add['nickname'] = $val['nickname'];
                        $add['level'] = $val['level'];
                        $add['pay'] = $val['pay'];
                        $add['count'] = $val['pay_count'];
                        $add['last_pay_time'] = $val['utime'];
                        $all[] = $add;
                    }
                }
            }

            //插入数据
            if (!empty($all) && false === D('DataPayRankTotal' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //每日等级最高用户行为分析
    private function dailyTopBehave()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataTopBehave' . $this->mTable)->where($where)->count();
        if ($count == 0) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;

            //获取TOP20的战队信息
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`tid`,`nickname`,`level`")->where('`ctime`>0')->order('`level` DESC,`tid` ASC')->limit(20)->select();

            //处理数据
            $tidList = array();
            $list = array();
            foreach ($select as $value) {
                $tidList[] = $value['tid'];
                $list[$value['tid']] = array_merge($addBase, $value);
                $list[$value['tid']]['instance_1'] = 0;
                $list[$value['tid']]['instance_2'] = 0;
                $list[$value['tid']]['pray_1001'] = 0;
                $list[$value['tid']]['pray_1010'] = 0;
                $list[$value['tid']]['pray_2001'] = 0;
                $list[$value['tid']]['pray_2010'] = 0;
                $list[$value['tid']]['buy_vality'] = 0;
                $list[$value['tid']]['buy_gold'] = 0;
                $list[$value['tid']]['partner_count'] = 0;
                $list[$value['tid']]['partner_level'] = 0;
                $list[$value['tid']]['partner_favour'] = 0;
                $list[$value['tid']]['partner_upgrade'] = 0;
                $list[$value['tid']]['skill_level'] = 0;
                $list[$value['tid']]['equip_level'] = 0;
                $list[$value['tid']]['equip_upgrade'] = 0;
                $list[$value['tid']]['emblem_count'] = 0;
                $list[$value['tid']]['emblem_upgrade'] = 0;
                $list[$value['tid']]['star_count'] = 0;
                $list[$value['tid']]['star_level'] = 0;
                $list[$value['tid']]['arena_rank'] = 0;
                $list[$value['tid']]['god_battle_count'] = 0;
                $list[$value['tid']]['lucky_cat_count'] = 0;
                $list[$value['tid']]['abyss_battle_count'] = 0;
                $list[$value['tid']]['life_death_battle_count'] = 0;
            }

            if (empty($tidList)) {
                return true;
            }

            //获取副本进度
            foreach ($tidList as $tid) {
                $num = $tid % 10;
                $table = 'g_instance_' . $num;
                $list[$tid]['instance_1'] = $this->mModel->db($this->mSid)->table($table)->where("`tid`='{$tid}' && `group` between '101' and '199'")->max('instance');
                $list[$tid]['instance_1'] = $list[$tid]['instance_1'] ? $list[$tid]['instance_1'] : 0;
                $list[$tid]['instance_2'] = $this->mModel->db($this->mSid)->table($table)->where("`tid`='{$tid}' && `group` between '201' and '299'")->max('instance');
                $list[$tid]['instance_2'] = $list[$tid]['instance_2'] ? $list[$tid]['instance_2'] : 0;
            }

            //抽卡次数
            $where = array();
            $where['tid'] = array('in', $tidList);
            $where['ctime'] = array('between', array($starttime, $endtime,));
            $select = $this->mModel->db($this->mSid)->table('l_pray')->field("`tid`,`pray_id`,count(`id`) as `count`")->where($where)->group("`tid`,`pray_id`")->select();
            foreach ($select as $value) {
                $list[$value['tid']]['pray_' . $value['pray_id']] += $value['count'];
            }

            //购买次数
            $where = array();
            $where['tid'] = array('in', $tidList);
            $where['behave'] = array('in', array(get_config('behave', array('buy_gold', 'code')), get_config('behave', array('buy_vality', 'code')),));
            $where['ctime'] = array('between', array($starttime, $endtime,));
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,`behave`")->where($where)->select();
            foreach ($select as $value) {
                if ($value['behave'] == get_config('behave', array('buy_gold', 'code'))) {
                    $list[$value['tid']]['buy_gold'] += 1;
                } else if ($value['behave'] == get_config('behave', array('buy_vality', 'code'))) {
                    $list[$value['tid']]['buy_vality'] += 1;
                }
            }

            //伙伴
            $where = array();
            $where['tid'] = array('in', $tidList);
            $where['level'] = array('gt', 0);
            $select = $this->mModel->db($this->mSid)->table('g_partner')->field("`tid`,`index`,`level`,`favour`,`skill_2_level`,`skill_3_level`,`skill_4_level`,`skill_5_level`")->where($where)->select();
            foreach ($select as $value) {
                $list[$value['tid']]['partner_count'] += 1;
                $list[$value['tid']]['partner_level'] += $value['level'];
                $list[$value['tid']]['partner_favour'] += $value['favour'] / 1000;
                $quality = substr($value['index'], -1);
                $list[$value['tid']]['partner_upgrade'] += (int)$quality;
                $list[$value['tid']]['skill_level'] += $value['skill_2_level'];
                $list[$value['tid']]['skill_level'] += $value['skill_3_level'];
                $list[$value['tid']]['skill_level'] += $value['skill_4_level'];
                $list[$value['tid']]['skill_level'] += $value['skill_5_level'];
            }

            //装备
            $where = array();
            $where['tid'] = array('in', $tidList);
            $select = $this->mModel->db($this->mSid)->table('g_equip')->field("`tid`,`index`,`level`")->where($where)->select();
            foreach ($select as $value) {
                $quality = substr($value['index'], -1);
                $list[$value['tid']]['equip_upgrade'] += (int)$quality;
                $list[$value['tid']]['equip_level'] += $value['level'];
            }

            //纹章
            $emblemConfig = D('Static')->access('emblem');
            $where = array();
            $where['tid'] = array('in', $tidList);
            $select = $this->mModel->db($this->mSid)->table('g_emblem')->field("`tid`,`index`")->where($where)->select();
            foreach ($select as $value) {
                $list[$value['tid']]['emblem_count'] += 1;
                $list[$value['tid']]['emblem_upgrade'] += $emblemConfig[$value['index']]['quality'];
            }

            //星灵
            $where = array();
            $where['tid'] = array('in', $tidList);
            $select = $this->mModel->db($this->mSid)->table('g_star')->field("`tid`,`position`,`level`")->where($where)->select();
            foreach ($select as $value) {
                $list[$value['tid']]['star_count'] += 1;
                $list[$value['tid']]['star_level'] += $value['level'];
            }

            //竞技场排名
            $where = array();
            $where['tid'] = array('in', $tidList);
            $select = $this->mModel->db($this->mSid)->table('g_arena')->field("`tid`,`rank`")->where($where)->select();
            foreach ($select as $value) {
                $list[$value['tid']]['arena_rank'] += $value['rank'];
            }

            //活动参加次数
            $where = array();
            $where['tid'] = array('in', $tidList);
            $where['group'] = array('in', array('4', '5', '14', '16', '17', '18', '19', '20',));
            $where['type'] = 1;
            $where['ctime'] = array('between', array($starttime, $endtime,));
            $select = $this->mModel->db($this->mSid)->table('t_daily_event')->field("`tid`,`group`,`count`")->where($where)->select();
            foreach ($select as $value) {
                switch ($value['group']) {
                    case '5':
                    case '16':
                    case '17':
                    case '18':
                        $list[$value['tid']]['god_battle_count'] += $value['count'];//神之试炼
                        break;
                    case '19':
                    case '20':
                        $list[$value['tid']]['lucky_cat_count'] += $value['count'];//神之试炼
                        break;
                    case '4':
                        $list[$value['tid']]['abyss_battle_count'] += $value['count'];//神之试炼
                        break;
                    case '14':
                        $list[$value['tid']]['life_death_battle_count'] += $value['count'];//神之试炼
                        break;
                }
            }

            //插入数据
            $all = array_values($list);
            if (!empty($all) && false === D('DataTopBehave' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //每日游戏币情况
    private function dailyCurrency()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataCurrency' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;

            //充值水晶产出
            $where = "`attr`='diamond_pay' && `value`>0 && `ctime` between '{$starttime}' and '{$endtime}'";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`behave`,sum(`value`) as `count`")->where($where)->group('behave')->select();
            if (!empty($select)) {
                $addBase['type'] = 1;
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['behave'] = $value['behave'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
            }

            //充值水晶回收
            $where = "`attr`='diamond_pay' && `value`<0 && `ctime` between '{$starttime}' and '{$endtime}'";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`behave`,sum(`value`) as `count`")->where($where)->group('behave')->select();
            if (!empty($select)) {
                $addBase['type'] = 2;
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['behave'] = $value['behave'];
                    $add['count'] = -$value['count'];
                    $all[] = $add;
                }
            }

            //免费水晶产出
            $where = "`attr`='diamond_free' && `value`>0 && `ctime` between '{$starttime}' and '{$endtime}'";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`behave`,sum(`value`) as `count`")->where($where)->group('behave')->select();
            if (!empty($select)) {
                $addBase['type'] = 3;
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['behave'] = $value['behave'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
            }

            //免费水晶回收
            $where = "`attr`='diamond_free' && `value`<0 && `ctime` between '{$starttime}' and '{$endtime}'";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`behave`,sum(`value`) as `count`")->where($where)->group('behave')->select();
            if (!empty($select)) {
                $addBase['type'] = 4;
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['behave'] = $value['behave'];
                    $add['count'] = -$value['count'];
                    $all[] = $add;
                }
            }

            //金币产出
            $where = "`attr`='gold' && `value`>0 && `ctime` between '{$starttime}' and '{$endtime}'";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`behave`,sum(`value`) as `count`")->where($where)->group('behave')->select();
            if (!empty($select)) {
                $addBase['type'] = 5;
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['behave'] = $value['behave'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
            }

            //金币回收
            $where = "`attr`='gold' && `value`<0 && `ctime` between '{$starttime}' and '{$endtime}'";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`behave`,sum(`value`) as `count`")->where($where)->group('behave')->select();
            if (!empty($select)) {
                $addBase['type'] = 6;
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['behave'] = $value['behave'];
                    $add['count'] = -$value['count'];
                    $all[] = $add;
                }
            }

            //插入数据
            if (!empty($all) && false === D('DataCurrency' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;
    }

    //1-8级用户数据
    private function dailyLostBehave()
    {

        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataLostBehave' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`tid`,`level`,`ctime`,`last_login_time`,`vality_utime`")->where("`ctime`>0 && `level` between '1' and '8'")->order('`vality_utime` ASC')->select();

            //处理数据
            $tidList = array();
            $list = array();
            foreach ($select as $value) {
                $tidList[] = $value['tid'];
                $list[$value['tid']] = $addBase;
                $list[$value['tid']]['tid'] = $value['tid'];
                $list[$value['tid']]['level'] = $value['level'];
                $list[$value['tid']]['create_time'] = $value['ctime'];
                $list[$value['tid']]['last_login_time'] = $value['last_login_time'];
                $list[$value['tid']]['vality_utime'] = $value['vality_utime'];
                $list[$value['tid']]['partner'] = 0;
                $list[$value['tid']]['diamond'] = 0;
                $list[$value['tid']]['gold'] = 0;
                $list[$value['tid']]['vality'] = 0;
                $list[$value['tid']]['instance'] = 0;
            }

            //没有流失用户直接返回
            if (empty($tidList)) {
                return true;
            }

            //获取副本进度
            foreach ($tidList as $tid) {
                $num = $tid % 10;
                $table = 'g_instance_' . $num;
                $instance = $this->mModel->db($this->mSid)->table($table)->where("`tid`='{$tid}' && `group` between '101' and '199'")->max('instance');
                $list[$tid]['instance'] = $instance ? $instance : 0;
            }

            //伙伴
            $where = array();
            $where['tid'] = array('in', $tidList);
            $where['level'] = array('gt', 0);
            $select = $this->mModel->db($this->mSid)->table('g_partner')->field("`tid`,count(`tid`) as `count`")->where($where)->group('`tid`')->select();
            foreach ($select as $value) {
                $list[$value['tid']]['partner'] += $value['count'];
            }

            //属性使用情况
            $where2 = "(`attr`='diamond_pay' || `attr`='diamond_free' || `attr`='gold' || `attr`='vality') && `value`<0";
            $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,`attr`,`value`")->where($where)->where($where2)->select();
            foreach ($select as $value) {
                if ($value['attr'] == 'diamond_pay' || $value['attr'] == 'diamond_free') {
                    $list[$value['tid']]['diamond'] += abs($value['value']);
                } else {
                    $list[$value['tid']][$value['attr']] += abs($value['value']);
                }
            }

            //插入数据
            $all = array_values($list);
            if (!empty($all) && false === D('DataLostBehave' . $this->mTable)->CreateAllData($all)) {
                $flag = false;
            }

        }

        //返回
        return $flag;

    }

    //抽卡统计
    private function dailyPray()
    {
        $flag = true;

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['date_time'] = $this->mDate;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataPray' . $this->mTable)->where($where)->count();
        if (!($count > 0)) {

            $starttime = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
            $endtime = $starttime + 86399;
            $sql = "select `gt`.`channel_id`,`lp`.`pray_id`,`lp`.`is_free`,count(`lp`.`tid`) as `count` from `g_team` as `gt`,`l_pray` as `lp` where `gt`.`tid`=`lp`.`tid` && `lp`.`ctime` between '{$starttime}' and '{$endtime}' group by `gt`.`channel_id`,`lp`.`pray_id`,`lp`.`is_free`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            if (!empty($select)) {
                $all = array();
                foreach ($select as $value) {
                    $add = $addBase;
                    $add['channel_id'] = $value['channel_id'];
                    $add['pray_id'] = $value['pray_id'];
                    $add['is_free'] = $value['is_free'];
                    $add['count'] = $value['count'];
                    $all[] = $add;
                }
                //插入数据
                if (!empty($all) && false === D('DataPray' . $this->mTable)->CreateAllData($all)) {
                    $flag = false;
                }
            }

        }

        //返回
        return $flag;
    }

    //月付费统计
    private function monthlyStatistics()
    {
        $flag = true;

        //先判断今天是不是1号
        if (date('j') != 1) {
            return true;
        }

        //月份
        $month = date('Y-m', strtotime('last month'));

        //查询是否已经查询过
        $where['sid'] = $this->mSid;
        $where['month'] = $month;
        $addBase = $where;
        $addBase['ctime'] = $this->mNow;
        $count = M('DataStatisticsMonthly')->where($where)->count();
        if (!($count > 0)) {

            //查询上个月的数据
            $history = array();
            $select = M('DataStatisticsMonthly')->field("`channel_id`,sum(`pay_amount`) as `pay_amount`,sum(`pay_count`) as `pay_count`")->where("`sid`='{$this->mSid}'")->select();
            if (!empty($select)) {
                foreach ($select as $value) {
                    $history[$value['channel_id']] = $value;
                }
            }

            //时间
            $lastMonth = strtotime(date('Y-m-01', strtotime('-1 month')));
            $list = array();

            //查询创建人数
            $sql = "select `channel_id`,count(`tid`) as `count` from `g_team` where `ctime` >= '{$lastMonth}' && `channel_id` > 0 group by `channel_id`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            if (!empty($select)) {
                foreach ($select as $value) {
                    $list[$value['channel_id']]['create_count'] = $value['count'];
                }
            }

            //查询活跃人数
            $sql = "select `channel_id`,count(`tid`) as `count` from `g_team` where `vality_utime` >= '{$lastMonth}' && `channel_id` > 0 group by `channel_id`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            if (!empty($select)) {
                foreach ($select as $value) {
                    $list[$value['channel_id']]['activity_count'] = $value['count'];
                }
            }

            //查询充值金额
            $sql = "select `gt`.`channel_id`,sum(`gv`.`pay`) as `count` from `g_team` as `gt`,`g_vip` as `gv` where `gt`.`tid`=`gv`.`tid` && `gt`.`ctime`>0 group by `gt`.`channel_id`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            if (!empty($select)) {
                foreach ($select as $value) {
                    $list[$value['channel_id']]['pay_amount'] = $value['count'] - $history[$value['channel_id']]['pay_amount'];
                }
            }

            //查询重复充值人数
            $sql = "select `gt`.`channel_id`,sum(`gv`.`pay_count`) as `count` from `g_team` as `gt`,`g_vip` as `gv` where `gt`.`tid`=`gv`.`tid` && `gt`.`ctime`>0 group by `gt`.`channel_id`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            if (!empty($select)) {
                foreach ($select as $value) {
                    $list[$value['channel_id']]['pay_count'] = $value['count'] - $history[$value['channel_id']]['pay_count'];
                }
            }

            //查询不重复充值人数
            $sql = "select `gt`.`channel_id`,count(`gv`.`tid`) as `count` from `g_team` as `gt`,`g_vip` as `gv` where `gt`.`tid`=`gv`.`tid` && `gv`.`utime` >= '{$lastMonth}' && `gt`.`ctime`>0 group by `gt`.`channel_id`";
            $select = $this->mModel->db($this->mSid)->query($sql);
            if (!empty($select)) {
                foreach ($select as $value) {
                    $list[$value['channel_id']]['pay_member'] = $value['count'];
                }
            }

            //整理数据
            if (!empty($list)) {

                $all = array();
                $now = time();
                foreach ($list as $channelId => $value) {

                    $add = $addBase;
                    $add['channel_id'] = $channelId;
                    $add['create_count'] = $value['create_count'] ? $value['create_count'] : 0;
                    $add['activity_count'] = $value['activity_count'] ? $value['activity_count'] : 0;
                    $add['pay_amount'] = $value['pay_amount'] ? $value['pay_amount'] : 0;
                    $add['pay_count'] = $value['pay_count'] ? $value['pay_count'] : 0;
                    $add['pay_member'] = $value['pay_member'] ? $value['pay_member'] : 0;
                    $add['ctime'] = $now;
                    $all[] = $add;

                }

                //插入数据
                if (false === D('DataStatisticsMonthly')->CreateAllData($all)) {
                    $flag = false;
                }

            }

            //返回
            return $flag;

        }

    }

    //每日异常数据
    private function dailyWarning()
    {

        $flag = true;
        $addAll = array();

        //当天日期
        $start = strtotime($this->mDate . ' ' . C('DAILY_UTIME'));
        $end = $start + 86399;

        //公共数据
        $add['sid'] = $this->mSid;
        $add['ctime'] = $this->mNow;
        $add['status'] = 0;

        //查询是否有超过战队等级上线的玩家
        $maxLevel = D('RedisGParams')->getValue('TEAM_MAX_LEVEL');
        if (!empty($maxLevel)) {
            $select = $this->mModel->db($this->mSid)->table('g_team')->field("`tid`,`level`")->where("`level`>'{$maxLevel}' && `ctime`>0")->select();
            if (!empty($select)) {
                foreach ($select as $value) {
                    $add['tid'] = $value['tid'];
                    $add['attr'] = 'team_level';
                    $add['params'] = 0;
                    $add['type'] = 1;
                    $add['value'] = $value['level'];
                    $addAll[] = $add;
                }
            }
        }

        //一次获得超过15000水晶
        $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,`value`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='diamond_free' && `value`>=15000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_diamond';
                $add['params'] = 0;
                $add['type'] = 1;
                $add['value'] = $value['value'];
                $addAll[] = $add;
            }
        }

        //一天内累计使用消耗超过10W水晶
        $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,sum(`value`) as `use_diamond`")->where("`ctime` between '{$start}' and '{$end}' && (`attr`='diamond_pay' || `attr`='diamond_free') && `value`<'0'")->group('tid')->having("sum(`value`)<='-100000'")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_diamond';
                $add['params'] = 0;
                $add['type'] = 2;
                $add['value'] = abs($value['use_diamond']);
                $addAll[] = $add;
            }
        }

        //一次获得超过10W金币
        $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,`value`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='gold' && `value`>=500000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_gold';
                $add['params'] = 0;
                $add['type'] = 1;
                $add['value'] = $value['value'];
                $addAll[] = $add;
            }
        }

        //一天内累计使用消耗超过100W金币
        $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,sum(`value`) as `use_gold`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='gold' && `value`<0")->group('tid')->having("sum(`value`)<=-1000000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_gold';
                $add['params'] = 0;
                $add['type'] = 2;
                $add['value'] = abs($value['use_gold']);
                $addAll[] = $add;
            }
        }

        //一次获得超过500点体力
        $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,`value`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='vality' && `value`>=500")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_vality';
                $add['params'] = 0;
                $add['type'] = 1;
                $add['value'] = $value['value'];
                $addAll[] = $add;
            }
        }

        //一天内累计使用消耗超过2500点体力
        $select = $this->mModel->db($this->mSid)->table('l_team')->field("`tid`,sum(`value`) as `use_vality`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='vality' && `value`<0")->group('tid')->having("sum(`value`)<=-2500")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_vality';
                $add['params'] = 0;
                $add['type'] = 2;
                $add['value'] = abs($value['use_vality']);
                $addAll[] = $add;
            }
        }

        //当前持有的体力达到2000
        $select = $this->mModel->db($this->mSid)->table('g_team')->field("`tid`,`vality`")->where("`vality`>=2000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'team_vality';
                $add['type'] = 3;
                $add['value'] = $value['vality'];
                $addAll[] = $add;
            }
        }

        //一天内伙伴累计获得超过300点神力
        $select = $this->mModel->db($this->mSid)->table('l_partner')->field("`tid`,`group`,sum(`value`) as `get_soul`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='soul' && `value`>0")->group("`tid`,`group`")->having("sum(`value`)>=300")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'partner_soul';
                $add['params'] = $value['group'];
                $add['type'] = 1;
                $add['value'] = $value['get_soul'];
                $addAll[] = $add;
            }
        }

        //一次获得超过1000荣誉
        $select = $this->mModel->db($this->mSid)->table('l_arena')->field("`tid`,`value`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='honour' && `value`>=1000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'arena_honour';
                $add['params'] = 0;
                $add['type'] = 1;
                $add['value'] = $value['value'];
                $addAll[] = $add;
            }
        }

        //一天内累计使用消耗超过50000荣誉
        $select = $this->mModel->db($this->mSid)->table('l_arena')->field("`tid`,sum(`value`) as `use_honour`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='honour' && `value`<0")->group("`tid`")->having("sum(`value`)<=-50000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'arena_honour';
                $add['params'] = 0;
                $add['type'] = 2;
                $add['value'] = abs($value['use_honour']);
                $addAll[] = $add;
            }
        }

        //一次获得超过5000公会资金
        $select = $this->mModel->db($this->mSid)->table('l_league')->field("`id`,`value`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='fund' && `value`>=5000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['id'];
                $add['attr'] = 'league_fund';
                $add['params'] = 0;
                $add['type'] = 1;
                $add['value'] = $value['value'];
                $addAll[] = $add;
            }
        }

        //一次获得超过1000公会贡献
        $select = $this->mModel->db($this->mSid)->table('l_league_team')->field("`tid`,`value`")->where("`ctime` between '{$start}' and '{$end}' && `attr`='contribution' && `value`>=1000")->select();
        if (!empty($select)) {
            foreach ($select as $value) {
                $add['tid'] = $value['tid'];
                $add['attr'] = 'league_contribution';
                $add['params'] = 0;
                $add['type'] = 1;
                $add['value'] = $value['value'];
                $addAll[] = $add;
            }
        }

        //返回
        if (!empty($addAll) && false === D('LWarning')->db(0)->CreateAllData($addAll)) {
            $flag = false;
        }

        return $flag;

    }

    //人数监控曲线接口
    public function online()
    {
        header_info('plain', 'gbk');
        $list = array_keys($this->mServerList);
        $str = '';
        foreach ($list as $sid) {
            $keyList = D('Predis')->cli('game', $sid)->keys('u:*');
            $count = count($keyList);
            $str .= "{$sid}服\\{$count};";
        }
        echo iconv('utf-8', 'gbk', $str);
    }

}