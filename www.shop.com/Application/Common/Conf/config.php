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
    ),


    'ALIPAY_CONFIG'            =>array(
        //合作身份者id，以2088开头的16位纯数字
        'partner'=>            '2088002155956432',
        //收款支付宝账号，一般情况下收款账号就是签约账号
        'seller_email'=>       'guoguanzhao520@163.com',
        //安全检验码，以数字和字母组成的32位字符
        'key'=> 			   'a0csaesgzhpmiiguif2j6elkyhlvf4t9',
        //签名方式 不需修改
        'sign_type'=>          strtoupper('MD5'),
        //字符编码格式 目前支持 gbk 或 utf-8
        'input_charset'=>      strtolower('utf-8'),
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        'cacert'=>              getcwd().'\\cacert.pem',
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'transport'=>           'http'
    )
);