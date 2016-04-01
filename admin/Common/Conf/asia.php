<?php
//UC
define('DB_UC_TYPE', 'mysql');
define('DB_UC_HOST', 'fg-asia-mysql.ceyzfdezdden.ap-southeast-1.rds.amazonaws.com');
define('DB_UC_USER', 'forever_game');
define('DB_UC_PWD', 'mbxv678wetlkjc');
define('DB_UC_PORT', '3306');
define('DB_UC_NAME', 'forevergame');
define('DB_UC_CHARSET', 'utf8');

//STATIC
define('DB_STATIC_TYPE', 'mysql');
define('DB_STATIC_HOST', 'fg-asia-mysql.ceyzfdezdden.ap-southeast-1.rds.amazonaws.com');
define('DB_STATIC_USER', 'forever_game');
define('DB_STATIC_PWD', 'mbxv678wetlkjc');
define('DB_STATIC_PORT', '3306');
define('DB_STATIC_NAME', 'zettaiStatic');
define('DB_STATIC_CHARSET', 'utf8');

//DB CONFIG
define('DB_TYPE', 'mysql');

//DB OPE
define('DB_GM_HOST', '127.0.0.1');
define('DB_GM_USER', 'root');
define('DB_GM_PWD', 'root!23');
define('DB_GM_PORT', '3306');
define('DB_GM_NAME', 'zettaiOpe');

//CDN
define('CDN_SERVER_VERSION_HOST', '172.31.12.221');//多服务器用逗号','分割
define('CDN_SERVER_VERSION_USER', 'yhzj');
define('CDN_SERVER_ZIP_HOST', '172.31.12.221');//多服务器用逗号','分割
define('CDN_SERVER_ZIP_USER', 'yhzj');
define('CDN_SERVER_STATIC_HOST', '172.31.12.221');//多服务器用逗号','分割
define('CDN_SERVER_STATIC_USER', 'yhzj');
define('CDN_PATH_VERSION', '/home/yhzj/server/html/cdn/');
define('CDN_PATH_ZIP', '/home/yhzj/server/html/cdn/');
define('CDN_PATH_STATIC', '/home/yhzj/server/html/cdn/');
define('CDN_PATH_LUA', '/home/yhzj/server/html/cdn/');
define('CDN_DOWNLOAD_URL', 'http://da45obp4uqk39.cloudfront.net/');

//URL
define('UC_URL', 'http://172.31.12.221:24602/user.php');
define('SCRIPT_URL', 'http://172.31.26.75:24602/index.php');
define('OPE_URL', 'http://127.0.0.1:24601/admin.php');

return array(
    //'配置项'=>'配置值'
    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST' => 'zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE' => 'l', // 默认语言切换变量
);