<?php
namespace Home\Controller;

use Think\Controller;

class VerListReleaseController extends BaseController
{

    private $mFilename = "monster_egg_";
    private $mDbNum = 3;
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
        for($i=0;$i<$this->mDbNum;++$i){

            //该文件名
            $fileName = $this->mFilename . $i . '.png';
            $zipName = $this->mFilename . $i . '.zip';

            //sqlite信息
            $data[$i]['sqliteSize'] = filesize($this->mDbPath . $fileName);
//            $data[$i]['sqliteZipSize'] = filesize($this->mDbPath . $zipName);
            $data[$i]['sqliteZipMd5'] = md5_file($this->mDbPath . $fileName);

        }

        //verlist
        $content = D('DParams')->where("`index`='VERLIST_NEW'")->getField('value');
        $content = trim($content);

        //解密
        $aes = new \Org\Util\CryptAES();
        $aes->set_key(C('AES_KEY'));
        $aes->require_pkcs5();
        $str = $aes->decrypt($content);

        //json
        $this->vData = $data;
        $this->vJson = trim($str);
        $this->display();//显示页面

    }

    //保存文件
    public function release()
    {
        if(IS_AJAX){
            $contentBK = D('DParams')->where("`index`='VERLIST'")->getField('value');
            $content = D('DParams')->where("`index`='VERLIST_NEW'")->getField('value');
            if(!empty($content)){
                $rs = D('DParams')->where("`index`='VERLIST'")->setField('value', $content);
                D('DParams')->where("`index`='VERLIST_BK'")->setField('value', $contentBK);
                if (false === $rs) {
                    echo L('fail');
                } else {
                    //清除APC
                    $this->clearApc();
                    echo L('success');
                }
            }
        }

    }

    //保存文件
    public function edit()
    {
        $content = trim($_POST['content']);
        $content = json_decode($content);
        if (is_null($content)) {
            C('G_ERROR', 'not_json');
        } else {
            $content = json_encode($content);
            $aes = new \Org\Util\CryptAES();
            $aes->set_key(C('AES_KEY'));
            $aes->require_pkcs5();
            $content = $aes->encrypt($content);
            $content = strtoupper($content);
            $rs = D('DParams')->where("`index`='VERLIST_NEW'")->setField('value', $content);
            if (false === $rs) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
            }
        }

        $this->alert = get_error();
        $this->jump = __CONTROLLER__ . "/index";
        $this->display("Public:jump");//显示页面
    }

}