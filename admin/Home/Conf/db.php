<?php
return array(

    //数据库部分
    'DB_TYPE' => DB_TYPE,
    'DB_DEPLOY_TYPE' => 0,                //数据库部署方式 0 集中式 1 分布式(主从)
    'DB_RW_SEPARATE' => false,            //是否读写分离
    'DB_HOST' => DB_GM_HOST,
    'DB_USER' => DB_GM_USER,
    'DB_PWD' => DB_GM_PWD,
    'DB_PORT' => DB_GM_PORT,
    'DB_NAME' => DB_GM_NAME,
    'DB_PREFIX' => '',                  //数据库表名前缀
    'DB_CHARSET' => 'utf8',             //数据库字符类型
    'DB_FIELDS_CACHE' => false,            // 禁用字段缓存(不同库中有相同名字的表)

    'ADMIN_CONFIG' => array(
        'db_type' => DB_TYPE,
        'db_host' => DB_GM_HOST,
        'db_user' => DB_GM_USER,
        'db_pwd' => DB_GM_PWD,
        'db_port' => DB_GM_PORT,
        'db_name' => DB_GM_NAME,
        'db_charset' => 'utf8',
    ),

    'STATIC_CONFIG' => array(
        'db_type' => DB_STATIC_TYPE,
        'db_host' => DB_STATIC_HOST,
        'db_user' => DB_STATIC_USER,
        'db_pwd' => DB_STATIC_PWD,
        'db_port' => DB_STATIC_PORT,
        'db_name' => DB_STATIC_NAME,
        'db_charset' => DB_STATIC_CHARSET,
    ),

    'UC_CONFIG' => array(
        'db_type' => DB_UC_TYPE,
        'db_host' => DB_UC_HOST,
        'db_user' => DB_UC_USER,
        'db_pwd' => DB_UC_PWD,
        'db_port' => DB_UC_PORT,
        'db_name' => DB_UC_NAME,
        'db_charset' => DB_UC_CHARSET,
    ),

);