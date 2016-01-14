<?php
define('WEB_URL') or define('WEB_URL', 'http://admin.shop.com/');
return array(
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => WEB_URL . 'Public/Admin/css',
        '__IMG__' => WEB_URL . 'Public/Admin/images',
        '__JS__' => WEB_URL . 'Public/Admin/js',
        '__LAYER__' => WEB_URL . 'Public/Admin/layer/layer.js',
        '__UPLOADIFY__' => WEB_URL .'Public/Admin/uploadify',
        '__TREEGRID__' => WEB_URL .'Public/Admin/treegrid',
        '__ZTREE__' => WEB_URL .'Public/Admin/zTree',
        '__BRAND__'=>'http://brand-logo.b0.upaiyun.com',
    ),
);