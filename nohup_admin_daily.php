<?php
set_time_limit(0);
$flag = 0;//标识
$timeZone = (int)date('O') / 100;//当前时区
$hour = 3;//需要执行的小时数
$second = 86400;//间隔时间
while(1){
    //获取当前时间戳
    $now = time();
    //计算剩余时间
    $remain = $second - (($now + (($timeZone - $hour) * 3600)) % $second);
    if($remain == $second && $flag == 0) {
        echo exec("php exec.php Home Daily");
        $flag = 1;
        usleep(1100000);
    }else{
        $flag = 0;
        //如果
        if($remain > 5){
            $sleep = ($remain - 3) * 1000000;
            usleep($sleep);
        }else{
            usleep(300000);
        }
    }
}