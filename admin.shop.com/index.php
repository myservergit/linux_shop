<?php
//检测版本
version_compare(PHP_VERSION,'5.3','>') or exit('版本太低!');
//定义项目的根目录常量
define('ROOT_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
//定义应用目录常量
define('APP_PATH',ROOT_PATH.'Application/');
//定义应用运行时目录常量
define('RUNTIME_PATH',ROOT_PATH.'Runtime/');
//定义框架目录常量
define('THINKPHP_PATH',dirname(ROOT_PATH).'/ThinkPHP/');
//Uploads文件夹地址所对应的常量
define('UPLOAD_PATH',ROOT_PATH.'Uploads/');
//绑定Admin模块
//define('BIND_MODULE','Admin');
//开启调试模式
define('APP_DEBUG',true);
//载入框架入口文件
require THINKPHP_PATH.'ThinkPHP.php';