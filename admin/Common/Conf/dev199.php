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
define('DB_STATIC_HOST', '192.168.10.199');
define('DB_STATIC_USER', 'root');
define('DB_STATIC_PWD', 'root');
define('DB_STATIC_PORT', '3306');
define('DB_STATIC_NAME', 'zettaiStatic');
define('DB_STATIC_CHARSET', 'utf8');

//DB CONFIG
define('DB_TYPE', 'mysql');

//DB OPE
define('DB_GM_HOST', '192.168.10.199');
define('DB_GM_USER', 'root');
define('DB_GM_PWD', 'root');
define('DB_GM_PORT', '3306');
define('DB_GM_NAME', 'zettaiope');

//URL
define('UC_URL', 'http://sbuc.forevergame.com/user.php');
define('OPE_URL', 'http://sbope7.forevergame.com/admin.php');

//盛大特殊配置
define('SNDA_APP_ID', 791000183);
define('SNDA_APP_KEY', '8810c07a5524cce637716a033c1ac008');
define('SNDA_LOGIN_URL', 'http://api.mygm.sdo.com/v1/open/ticket');
define('SNDA_HPS_USER', 'MEIYU_791000183');

return array(
    //'配置项'=>'配置值'
    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST' => 'zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE' => 'l', // 默认语言切换变量
);