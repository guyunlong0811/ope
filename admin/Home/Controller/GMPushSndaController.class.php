<?php
namespace Home\Controller;

use Think\Controller;

class GMPushSndaController extends BaseController
{

    private $sqlIOS = '';
    private $sqlAndriod = '';
    private $sqlWP = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_push_snda';
        $this->vOS = array(
            1 => L('os_2'),
            2 => L('os_1'),
        );
        $this->vStyle = array(
            1 => L('push_style_1'),
            2 => L('push_style_2'),
            3 => L('push_style_3'),
            4 => L('push_style_4'),
            5 => L('push_style_5'),
        );
        $this->vReceiverType = array(
            101 => L('receiver_type_101'),
            102 => L('receiver_type_102'),
            103 => L('receiver_type_103'),
            104 => L('receiver_type_104'),
            105 => L('receiver_type_105'),
        );
        $this->vSchedule = array(
            1 => L('schedule_1'),
            2 => L('schedule_2'),
        );
    }

    //显示列表
    public function index()
    {

        //查询所有信息
        $model = M('LPushSnda');
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

            //执行编号
            $push['trackNo'] = create_uuid();

            //游戏编号
            $push['appId'] = SNDA_APP_ID;

            //发送平台
            $push['toPlatform'] = $_POST['toPlatform'];

            //消息类型
            $push['messageType'] = 2;

            //推送标题
            if (mb_strlen($_POST['title'], 'gb2312') > 72) {
                C('G_ERROR', 'push_title_too_long');
                goto end;
            }
            $push['title'] = $_POST['title'];

            //推送内容
            $push['content'] = $_POST['content'];

            //消息类型
            switch($push['toPlatform']){
                case '2':
                    $push['styleId'] = 1;
                    break;
                default:
                    $push['styleId'] = $_POST['styleId'];
            }

            //数据
            switch($push['styleId']){
                case '2':
                case '4':
                case '5':
                    $arr['link'] = $_POST['url'];
                    $push['data'] = json_encode($arr);
                    break;
                case '3':
                    $arr['packageName'] = $_POST['package_name'];
                    $push['data'] = json_encode($arr);
                    break;
                default:
                    $push['data'] = '';
            }

            //任务备注字段
            $push['memo'] = $_POST['memo'];

            //目标类别
            $push['receiverType'] = $_POST['receiverType'];

            //目标&区服
            $push['receivers'] = '';
            $push['areaId'] = '';
            switch($push['receiverType']){
                case '102':
                    $push['areaId'] = $_POST['areaId'];
                    break;
                case '103':
                    $push['receivers'] = str_replace('#', "\t", $_POST['receivers']);
                    break;
                case '104':
                case '105':
                    $push['receivers'] = str_replace('#', "\t", $_POST['receivers']);
                    $push['areaId'] = $_POST['areaId'];
                    break;
            }

            //发送时间
            $push['schedule'] = $_POST['schedule'];

            //指定时间
            $push['scheduleTime'] = "";
            if($push['schedule'] == '2'){
                $push['scheduleTime'] = substr($_POST['scheduleTime'], 0 -3);
            }

            //离线保留时间
            $push['keepSeconds'] = $_POST['keepSeconds'];

            dump(111);
            dump($push);
            //获取配置文件
            require_once(COMMON_PATH . 'Common/hps_php_sdk/push.php');
            $ret = \AccountInfoInterface::getInstance()->push($push);
            dump($ret);
            //运行结果
            if ($ret['return_code'] != 0) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }

            //记录日志
//            D('LPushSnda')->CreateData($push);

        }


        end:
        $this->time = date("Y-m-d H:00:00", strtotime('+1 hours'));
        $this->alert = get_error();
        $this->display();//显示页面

    }

}