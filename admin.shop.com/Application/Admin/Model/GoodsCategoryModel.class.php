<?php
namespace Admin\Model;


use Think\Model;

class GoodsCategoryModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '分类名称不能为空!'),
		array('parent_id', 'require', '父分类不能为空!'),
		array('level', 'require', '层级不能为空!'),
		array('lft', 'require', '左边界不能为空!'),
		array('rgt', 'require', '右边界不能为空!'),
		array('status', 'require', '是否显示不能为空!'),
		    );
}