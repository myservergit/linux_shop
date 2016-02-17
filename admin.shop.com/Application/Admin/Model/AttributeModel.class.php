<?php
namespace Admin\Model;


use Think\Model;

class AttributeModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '属性名称不能为空!'),
		array('goods_type_id', 'require', '商品类型不能为空!'),
		array('type', 'require', '属性类型不能为空!'),
		array('input_type', 'require', '属性类型不能为空!'),
		array('option_values', 'require', '可选值不能为空!'),
		array('sort', 'require', '排序不能为空!'),
		array('status', 'require', '是否显示不能为空!'),
	);

	public function getList($field = "*",$wheres=array()){
		$rows = parent::getList($field,$wheres);
		foreach($rows as &$row){     //对可选值 通过 回车换行进行分割
			if(!empty($row['option_values'])){
				$row['option_values']  = preg_split("/\n/",$row['option_values']);
			}
		}
		return $rows;
	}
}