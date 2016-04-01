<?php
namespace Home\Controller;

use Think\Controller;

class VerResourceUploadController extends BaseController
{
    private $mZipPath;

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

            //路径
            $uploadfolder = $this->mZipPath . I('post.ver') . "/";
            if (!is_dir($uploadfolder)) {
                if (!mkdir($uploadfolder, 0777, true)) {
                    C('G_ERROR', 'mkdir_error');
                    goto end;
                }
            }

            //判断是否为zip
            $uploadfile = $uploadfolder . $_FILES['file']['name'];
            if (($_FILES["file"]["type"] != "application/x-zip-compressed") && ($_FILES["file"]["type"] != "application/zip")) {
                C('G_ERROR', 'file_type_error');
                goto end;
            }

            //上传
            if (isset($_FILES) && !empty($_FILES['file']) && $_FILES['file']['size'] > 0) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                    C('G_ERROR', 'success');
                    goto end;
                } else {
                    C('G_ERROR', 'fail');
                    goto end;
                }
            } else {
                C('G_ERROR', 'fail');
                goto end;
            }

        }

        //view
        end:
        $this->alert = get_error();
        $this->display();//显示页面

    }

}