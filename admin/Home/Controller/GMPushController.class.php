<?php
namespace Home\Controller;

use Think\Controller;

class GMPushController extends BaseController
{

    private $sqlIOS = '';
    private $sqlAndriod = '';
    private $sqlWP = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_push';
        $this->vOS = array(
            0 => L('os_all'),
            1 => L('os_1'),
            2 => L('os_2'),
            3 => L('os_3'),
        );
    }

    //显示列表
    public function index()
    {

        //查询所有信息
        $model = M('LPush');
        $order = array('ctime' => 'desc',);
        $page = $this->page($model);
        $list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
        foreach ($list as $key => $value) {
            $list[$key]['sid'] = $this->getServerName($value['sid']);
            $list[$key]['tid'] = $value['tid'] == 0 ? L('all_user') : $value['tid'];
            $list[$key]['ctime'] = time2format($value['ctime']);
        }
//        dump($list);
        //显示
        $this->list = $list;
        $this->display();//显示页面

    }

    //显示列表
    public function send()
    {

        if (!empty($_POST)) {

            if (mb_strlen(I('post.msg'), 'gb2312') > 70) {
                C('G_ERROR', 'push_msg_too_long');
                goto end;
            }

            //处理数据
            $push['sid'] = I('post.sid');
            $push['os'] = I('post.os');
            $push['msg'] = I('post.msg');

            //全服
            if (I('post.sid') == 0) {
                $push['tid'] = 0;
            } else {
                if (I('post.user') == 0) {
                    $push['tid'] = 0;
                } else {
                    $push['tid'] = I('post.user_list');
                }
            }

            //拼sql
            if ($push['sid'] == 0) {
                foreach ($this->mServerList as $key => $value) {
                    $this->pushSql($key, $push['tid'], $push['os'], $push['msg']);
                }
            } else {
                $this->pushSql($push['sid'], $push['tid'], $push['os'], $push['msg']);
            }

            //运行sql
            $flag = true;//运行结果
            if ($this->sqlIOS != '') {
                $this->sqlIOS = "insert into `push_ios` (`token`,`msg`) value " . substr($this->sqlIOS, 0, -1);
                if (false === M()->db(0)->execute($this->sqlIOS)) {
                    $flag = false;
                }
            }

            if ($this->sqlAndriod != '') {
                $this->sqlAndriod = "insert into `push_andriod` (`token`,`msg`) value " . substr($this->sqlAndriod, 0, -1);
                if (false === M()->db(0)->execute($this->sqlAndriod)) {
                    $flag = false;
                }
            }

            if ($this->sqlWP != '') {
                $this->sqlWP = "insert into `push_wp` (`token`,`msg`) value " . substr($this->sqlWP, 0, -1);
                if (false === M()->db(0)->execute($this->sqlWP)) {
                    $flag = false;
                }
            }
//            dump($this->sqlIOS);
//            dump($this->sqlIOS);
            //运行结果
            if (!$flag) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

            //记录日志
            D('LPush')->CreateData($push);

            //运行
//            $result = $this->push();
//            $this->result = json_encode($result);

        }

        end:
        $this->alert = get_error();
        $this->display();//显示页面

    }

    //拼sql
    private function pushSql($sid, $tid, $os, $msg)
    {

        //字段
        $field = array('system', 'token');

        //条件
        $where = "`token` != ''";


        //是否是部分用户推送
        if ($tid != '0') {
            $tidList = explode('#', $tid);
            $in = sql_in_condition($tidList);
            $where .= " && `tid` in {$in}";
        }

        //是否特定系统
        if ($os != '0') {
            $where .= " && `system`='{$os}'";
        } else {
            $where .= " && `system`<>'0'";
        }

        //查询数据
        $select = M()->db($sid, change_db_server($sid, 'master'))->table('g_device')->field($field)->where($where)->select();
        if (empty($select)) {
            return true;
        }

        foreach ($select as $value) {
            switch ($value['system']) {
                case '1':
                    $this->sqlIOS .= "('{$value['token']}','{$msg}'),";
                    break;
                case '2':
                    $this->sqlAndriod .= "('{$value['token']}','{$msg}'),";
                    break;
                case '3':
                    $this->sqlWP .= "('{$value['token']}','{$msg}'),";
                    break;
            }
        }

        return true;

    }

    //发送推送
    public function push()
    {

        //查询推送信息
        $count = M('PushIos')->count();
        if ($count == 0) {
            echo "No Data.";
            return;
        }

        //逐条查询
//        $list = array();
//        for ($i = 1; $i <= $count; ++$i) {
//            $sql = "select * from `push_ios` where queue_wait(\"push_ios\")";
//            $select = M('PushIos')->query($sql);
//            if (empty($select)) {
//                break;
//            }
//            $list[] = $select[0];
//            $sql = "select queue_end()";
//            M('PushIos')->query($sql);
//        }

        $list = M('PushIos')->select();
        M()->execute('truncate table `push_ios`');

        //证书
        $pem = COMMON_PATH . 'Common/Certificate/ifsc_push.pem';

        //apple
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
//        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        //推送地址
//        if (APP_DEBUG === true) {
//            $ssl = 'ssl://gateway.sandbox.push.apple.com:2195';
//        } else {
            $ssl = 'ssl://gateway.push.apple.com:2195';
//        }

        //建立连接
        $fp = stream_socket_client($ssl, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            C('G_ERROR', "Failed to connect $err $errstr");
            return;
        } else {
//            echo "Connection OK";
//            echo "<br />";
        }

        $all = 0;
        $success = 0;
        $fail = 0;
        foreach ($list as $value) {

            ++$all;

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
            $result = fwrite($fp, $msg, strlen($msg));
            if (!$result) {
                ++$fail;
            } else {
                ++$success;
            }

        }

        // Close the connection to the server
        fclose($fp);

        $return['all'] = $all;
        $return['success'] = $success;
        $return['fail'] = $fail;
        return $return;

    }

    public function getQueueList()
    {
//        $sql = "select * from `push_ios` where queue_wait(\"push_ios\")";
        $sql = "select * from `push_ios`";
        for ($i = 1; $i <= 100; ++$i) {
            M('PushIos')->query($sql);
        }
//        $select = M('PushIos')->query($sql);
//        dump($select);
        $sql = "select queue_end()";
        M('PushIos')->query($sql);
//        $select = M('PushIos')->select();
//        dump($select);
        return true;
    }

}