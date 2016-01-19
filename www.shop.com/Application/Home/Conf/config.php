<?php
define('WEB_URL') or define('WEB_URL', 'http://www.shop.com/');
return array(
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => WEB_URL . 'Public/Home/css',
        '__IMG__' => WEB_URL . 'Public/Home/images',
        '__JS__' => WEB_URL . 'Public/Home/js',
        '__BRAND__' => 'http://brand-logo.b0.upaiyun.com',
        '__GOODS__' => 'http://itsource-goods.b0.upaiyun.com',
        '__UEDIT__' => WEB_URL . 'Public/Home/uedit',
    ),
);