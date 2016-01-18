<?php
namespace Admin\Model;


use Think\Model;

class ArticleCategoryModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '分类名称不能为空!'),
        array('is_help', 'require', '帮助分类不能为空!'),
        array('status', 'require', '是否显示不能为空!'),
    );
}