<?php
namespace Admin\Model;


use Think\Model;

class PayTypeModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '支付方式名称不能为空!'),
		array('is_default', 'require', '是否默认不能为空!'),
		array('status', 'require', '是否显示不能为空!'),
		    );
}