<?php
namespace Admin\Model;


use Think\Model;

class DeliveryModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '送货方式名字不能为空!'),
		array('price', 'require', '运费不能为空!'),
		array('is_time', 'require', '是否支持送货时间不能为空!'),
		array('is_default', 'require', '是否默认不能为空!'),
		array('status', 'require', '是否显示不能为空!'),
		    );
}