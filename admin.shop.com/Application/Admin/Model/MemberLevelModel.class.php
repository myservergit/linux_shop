<?php
namespace Admin\Model;


use Think\Model;

class MemberLevelModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '会员级别名称不能为空!'),
		array('low', 'require', '最低积分不能为空!'),
		array('high', 'require', '最高积分不能为空!'),
		array('discount', 'require', '折扣不能为空!'),
		array('status', 'require', '状态不能为空!'),
		    );
}