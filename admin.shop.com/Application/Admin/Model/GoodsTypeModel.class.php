<?php
namespace Admin\Model;


use Think\Model;

class GoodsTypeModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '类型名称不能为空!'),
		array('sort', 'require', '排序不能为空!'),
		array('status', 'require', '是否显示不能为空!'),
		    );
}