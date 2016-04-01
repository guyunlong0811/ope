<?php
namespace Home\Controller;

use Think\Controller;

class VerReleaseController extends BaseController
{
    private $mZipPath;
    private $mVersionPath;
    private $mPkg = array();

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'code-fork';

        $content = D('DParams')->where("`index`='VERLIST_NEW'")->getField('`value`');
        $content = trim($content);
        $aes = new \Org\Util\CryptAES();
        $aes->set_key(C('AES_KEY'));
        $aes->require_pkcs5();
        $str = $aes->decrypt($content);
        $this->vVersion = json_decode($str, true);
        $this->mZipPath = dirname(dirname(dirname(dirname(__FILE__)))) . '/' . COMMON_PATH . 'Common/cdn/zip/';
    }

    //显示列表
    public function index()
    {

        if (!empty($_POST)) {

            //判断版本号是否符合规范
            if (!isVersion(I('post.ver'))) {
                C('G_ERROR', 'version_number_error');
                goto end;
            }

            $versions = explode(".", I('post.ver'));
            $nowVersions = explode(".", $this->vVersion['currentver']);
            //检查版本是否比现在小
            for ($i = 0; $i < count($versions); $i++) {
                if ($versions[$i] > $nowVersions[$i]) {
                    break;
                } else {
                    if ($i == count($versions) - 1) {
                        C('G_ERROR', 'version_number_too_low');
                        goto end;
                    }
                }
            }

            //路径
            $this->mVersionPath = $this->mZipPath . I('post.ver') . "/";

            //判断是否存在路径
            if (!is_dir($this->mVersionPath)) {
                C('G_ERROR', 'version_not_exist');
                goto end;
            }

            //遍历文件
            if (!$this->traverse($this->mVersionPath)) {
                goto end;
            }

            if (empty($this->mPkg)) {
                C('G_ERROR', 'version_file_empty');
                goto end;
            }

            //创建新版本配置
            $newVer = array(
                'ver' => I('post.ver'),
                'basever' => $this->vVersion['currentbase'],
                'cdnroot' => '',
                'pkg' => $this->mPkg
            );

            //写入verlist
            $newVerList = $this->vVersion;
            $newVerList['verlist'][] = $newVer;
            $newVerList['currentver'] = I('post.ver');

            //生成文件
            $str = str_replace("\\/", "/", json_encode($newVerList));
            $aes = new \Org\Util\CryptAES();
            $aes->set_key(C('AES_KEY'));
            $aes->require_pkcs5();
            $str = $aes->encrypt($str);
            $str = strtoupper($str);

            //修改Static
            D('DParams')->where("`index`='VERLIST_NEW'")->setField('value', $str);

            //修改小游戏版本号
            $arrVersion = explode('.', I('post.ver'));
            $data['value'] = $arrVersion[2];
            $where['index'] = 'MINI_GAME_COUNT';
            D('DParams')->UpdateData($data, $where);

            C('G_ERROR', 'success');

            //获取最新版本号
            $this->vVersion = $newVerList;

        }

        //view
        end:
        $this->alert = get_error();
        $this->display();//显示页面

    }

    private function traverse($path)
    {

        //opendir()返回一个目录句柄,失败返回false
        $current_dir = opendir($path);

        //readdir()返回打开目录句柄中的一个条目
        while (($file = readdir($current_dir)) !== false) {

            //构建子目录路径
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;

            //文件内容
            if ($file == '.' || $file == '..') {
                continue;
            } else if (is_dir($sub_dir)) {
                //如果是目录,进行递归
                $this->traverse($sub_dir);
            } else {
                //如果是文件,直接输出
                $path_parts = pathinfo($file);
                if ($path_parts['extension'] == 'zip') {

                    $newFileName = md5_file($this->mVersionPath . $file);
                    $exec = 'cd ' . $this->mVersionPath . ' && mv ' . $file . ' ' . $newFileName;
                    exec($exec);

                    //云分发
                    if (!$ret = D('SndaCdn')->upload($this->mVersionPath . $newFileName, 'ver/' . I('post.ver') . '/')) {
                        return false;
                    }

                    $arr['file'] = $ret['url'];
                    $arr['size'] = filesize($this->mVersionPath . $newFileName);
                    $arr['md5'] = md5_file($this->mVersionPath . $newFileName);
                    $this->mPkg[] = $arr;
                }
            }
        }

        return true;
    }

}