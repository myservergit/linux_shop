<?php
return array(
    'DB_TYPE'                => 'mysql', // 数据库类型
    'DB_HOST'                => '127.0.0.1', // 服务器地址
    'DB_NAME'                => 'shop', // 数据库名
    'DB_USER'                => 'root', // 用户名
    'DB_PWD'                 => '123456', // 密码
    'DB_PORT'                => '', // 端口
    'DB_PREFIX'              => '', // 数据库表前缀
    'DB_PARAMS'              => array(), // 数据库连接参数

    'DATA_CACHE_TYPE'        => 'Redis',

    //短信应用的配置
    'SMS_CONFIG'             =>array(
        'appkey' => '23302770',
        'secretKey' => 'fb8a8777132bbd851e1a87b8e8a06f2e'
    ),

    //发送邮件的配置
    'MAIL_CONFIG'            =>array(
        'Host'=>            'smtp.126.com', //126服务器的IP地址(域名)
        'Username'=>        'itsource520@126.com',
        'Password'=>        'qqitsource520',
        'From'=>            'itsource520@126.com'   //发件人
    )
);