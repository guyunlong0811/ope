<?php
namespace Home\Controller;

use Think\Controller;

class VerLuaUpdateController extends BaseController
{
    private $mLuaPath;

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'code-fork';
        $this->mLuaPath = dirname(dirname(dirname(dirname(__FILE__)))) . '/' . COMMON_PATH . 'Common/cdn/lua/';
    }

    //显示列表
    public function index()
    {

        if (!empty($_POST)) {

            $ts = time();

            //路径
            if (!is_dir($this->mLuaPath)) {
                if (!mkdir($this->mLuaPath, 0777, true)) {
                    C('G_ERROR', 'mkdir_error');
                    goto end;
                }
            }

            //判断是否为zip
            $uploadfile = $this->mLuaPath . $_FILES['file']['name'];
            $name = explode('.', $_FILES['file']['name']);
            if ($_FILES["file"]["type"] != "application/octet-stream" || ($name[1] != 'lua' && $name[1] != 'luac')) {
                C('G_ERROR', 'file_type_error');
                goto end;
            }

            //检测文件数据
            if (!isset($_FILES) || empty($_FILES['file']) || !($_FILES['file']['size'] > 0)) {
                C('G_ERROR', 'fail');
                goto end;
            }

            //上传
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                C('G_ERROR', 'fail');
                goto end;
            }

            $zipFilename = $name[0] . '_' . $ts . '.zip';

            //zip打包
            $exec = 'cd ' . $this->mLuaPath . '&& zip ' . $zipFilename . ' ' . $_FILES['file']['name'];
            exec($exec);

            if (!file_exists($this->mLuaPath . $zipFilename)) {
                C('G_ERROR', 'zip_create_fail');
                goto end;
            }

            $data = array(
                "file" => $_FILES['file']['name'],
                "size" => filesize($uploadfile),
                "md5" => md5_file($uploadfile),
                "download" => CDN_DOWNLOAD_URL . $zipFilename,
                "dsize" => filesize($this->mLuaPath . $zipFilename)
            );
            $content = D('DParams')->where("`index`='VERLIST_NEW'")->getField('value');
            $content = trim($content);
            $aes = new \Org\Util\CryptAES();
            $aes->set_key(C('AES_KEY'));
            $aes->require_pkcs5();
            $str = $aes->decrypt($content);
            $jsonVer = json_decode($str, true);
            $flag = false;
            foreach ($jsonVer['data'] as $key => $value) {
                if ($value['file'] == $_FILES['file']['name']) {
                    $jsonVer['data'][$key] = $data;
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                $jsonVer['data'][] = $data;
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

            //上传数据包
            if (CDN_SERVER_STATIC_HOST != '') {
                $staticServerList = explode(',', CDN_SERVER_STATIC_HOST);
                foreach ($staticServerList as $server) {
                    $exec = 'scp ' . $this->mLuaPath . $zipFilename . ' ' . CDN_SERVER_STATIC_USER . '@' . $server . ':' . CDN_PATH_LUA;
                    exec($exec);
                }
            }

            C('G_ERROR', 'success');

        }

        //view
        end:
        $this->alert = get_error();
        $this->display();//显示页面

    }

}