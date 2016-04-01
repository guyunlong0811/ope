<?php
namespace Home\Controller;

use Think\Controller;

class VerDbUpdateController extends BaseController
{

    private $mFilename = "monster_egg_";
    private $mDbPath;

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'code-fork';
        $this->mDbPath = dirname(dirname(dirname(dirname(__FILE__)))) . '/' . COMMON_PATH . 'Common/cdn/db/';
    }

    //显示列表
    public function index()
    {

        if (!empty($_POST)) {

            //获取客户端用表列表
            $table = M('ClientStatic')->order("`group` ASC")->select();

            //分组
            $group = array();
            foreach ($table as $value) {
                $group[$value['group']][] = $value['table'];
            }

            //数据处理
            if (!empty($group)) {

                $ts = time();

                //获取verlist
                $content = D('DParams')->where("`index`='VERLIST_NEW'")->getField('value');
                $content = trim($content);
                $aes = new \Org\Util\CryptAES();
                $aes->set_key(C('AES_KEY'));
                $aes->require_pkcs5();
                $str = $aes->decrypt($content);
                $jsonVer = json_decode($str, true);

                foreach ($group as $key => $value) {

                    //该文件名
                    $fileName = $this->mFilename . $key . '.png';

                    //删除原文件
                    unlink($this->mDbPath . $fileName);

                    //生成新文件
                    $exec = $this->mDbPath . "mysql2sqlite.sh " . DB_STATIC_NAME . " ";
                    foreach ($value as $val) {
                        $exec .= $val . ' ';
                    }
                    $exec .= "-h" . DB_STATIC_HOST . " -u" . DB_STATIC_USER . " -p'" . DB_STATIC_PWD . "'";
                    exec($exec, $output, $status);

                    //检查是否生成成功
                    if ($status == 1) {
                        C('G_ERROR', 'fail');
                        goto end;
                    }

                    //文件重命名
                    if (!rename($this->mDbPath . 'db.sqlite', $this->mDbPath . $fileName)) {
                        C('G_ERROR', 'fail');
                        goto end;
                    }

                    //zip文件名
                    $zipName = md5_file($this->mDbPath . $fileName);

                    //打zip包
                    $exec = 'cd ' . $this->mDbPath . ' && zip ' . $zipName . ' ' . $fileName . ' && mv ' . $zipName . '.zip' . ' ' . $zipName;
                    exec($exec);

                    //检查zip包情况
                    if (!file_exists($this->mDbPath . $zipName)) {
                        C('G_ERROR', 'zip_create_fail');
                        goto end;
                    }

                    //云分发
                    if (!$ret = D('SndaCdn')->upload($this->mDbPath . $zipName, 'img/')) {
                        goto end;
                    }

                    //生成数据
                    $data = array(
                        "file" => $fileName,
                        "size" => filesize($this->mDbPath . $fileName),
                        "md5" => md5_file($this->mDbPath . $fileName),
                        "download" => $ret['url'],
                        "dsize" => filesize($this->mDbPath . $zipName)
                    );

                    //数据写入
                    $flag = 0;
                    foreach ($jsonVer['data'] as $k => $val) {
                        if ($val['file'] == $fileName) {
                            $jsonVer['data'][$k] = $data;
                            $flag = 1;
                        }
                    }

                    if ($flag == 0) {
                        $jsonVer['data'][] = $data;
                    }


                }

            }

            //生成文件
            $str = str_replace("\\/", "/", json_encode($jsonVer));
            $aes = new \Org\Util\CryptAES();
            $aes->set_key(C('AES_KEY'));
            $aes->require_pkcs5();
            $str = $aes->encrypt($str);
            $str = strtoupper($str);

            //修改Static
            D('DParams')->where("`index`='VERLIST_NEW'")->setField('value', $str);

            //记录修改日志
            $comment[] = !I('post.comment') ? null : I('post.comment');
            C('G_SQL', $comment);

            C('G_ERROR', 'success');

        }

        //view
        end:
        $this->alert = get_error();
        $this->display();//显示页面

    }

}