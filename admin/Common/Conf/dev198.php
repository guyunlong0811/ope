<?php
//UC
define('DB_UC_TYPE', 'mysql');
define('DB_UC_HOST', '192.168.10.200');
define('DB_UC_USER', 'root');
define('DB_UC_PWD', 'root');
define('DB_UC_PORT', '3306');
define('DB_UC_NAME', 'forevergame');
define('DB_UC_CHARSET', 'utf8');

//STATIC
define('DB_STATIC_TYPE', 'mysql');
define('DB_STATIC_HOST', '192.168.10.198');
define('DB_STATIC_USER', 'root');
define('DB_STATIC_PWD', 'root');
define('DB_STATIC_PORT', '3306');
define('DB_STATIC_NAME', 'ssgStatic');
define('DB_STATIC_CHARSET', 'utf8');

//DB CONFIG
define('DB_TYPE', 'mysql');

//DB OPE
define('DB_GM_HOST', '192.168.10.198');
define('DB_GM_USER', 'root');
define('DB_GM_PWD', 'root');
define('DB_GM_PORT', '3306');
define('DB_GM_NAME', 'ssgOpe');

//CDN
define('CDN_SERVER_VERSION_HOST', '127.0.0.1');//多服务器用逗号','分割
define('CDN_SERVER_VERSION_USER', 'game');
define('CDN_SERVER_ZIP_HOST', '127.0.0.1');//多服务器用逗号','分割
define('CDN_SERVER_ZIP_USER', 'game');
define('CDN_SERVER_STATIC_HOST', '127.0.0.1');//多服务器用逗号','分割
define('CDN_SERVER_STATIC_USER', 'game');
define('CDN_PATH_VERSION', '/home/game/server/html/cdn/');
define('CDN_PATH_ZIP', '/home/game/server/html/cdn/');
define('CDN_PATH_STATIC', '/home/game/server/html/cdn/');
define('CDN_PATH_LUA', '/home/game/server/html/cdn/');
define('CDN_DOWNLOAD_URL', 'http://sbssgapi.forevergame.com/cdn/');

//URL
define('UC_URL', 'http://sbuc.forevergame.com/user.php');
define('OPE_URL', 'http://sbssgope.forevergame.com/admin.php');

return array(
    //'配置项'=>'配置值'
    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST' => 'zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE' => 'l', // 默认语言切换变量
);