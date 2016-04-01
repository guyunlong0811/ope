<?php
//导入配置文件
require_once('config.php');

//入口
$_GET['m'] = 'Home';
$_GET['c'] = 'Home';
$_GET['a'] = 'nohup';
$_GET['type'] = $argv[2];

//导入TP框架
require_once('ThinkPHP/ThinkPHP.php');