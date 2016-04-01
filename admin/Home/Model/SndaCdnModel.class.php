<?php
namespace Home\Model;

use Think\Model;

class SndaCdnModel
{
    //上传文件
    public function upload($file, $path = 'test/')
    {
        $arr = explode('/', $file);
        $fileName = end($arr);
        $data['apiKey'] = SNDA_CDN_ID;
        $data['path'] = $path . $fileName;
        $data['md5'] = md5_file($file);
        $data['project'] = SNDA_CDN_PROJECT;
        $data['timestamp'] = time();
        $data['signature'] = self::sign($data);
        $query_str = http_build_query($data);
        $data = array();
        $data['file'] = '@' . $file;
        $host = SNDA_CDN_URL . '/upload' . '?' . $query_str;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($ret, true);
        if($arr['code'] != '0'){
            C('G_ERROR', 'snda_upload_fail');
            return false;
        }
        return $arr;
    }

    //计算sign
    static public function sign($data)
    {
        ksort($data);
        $str = array();
        foreach ($data as $k => $v) {
            $str[] = "{$k}={$v}";
        }
        $str = implode('&', $str);
        $hash = hash_hmac('sha1', $str, SNDA_CDN_KEY);
        return $hash;
    }

}