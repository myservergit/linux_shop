<?php
namespace Admin\Model;


use Admin\Service\NestedSetsService;
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

	/**
	 * 商品分类的添加
	 * @return false|int
	 */
	public function add(){
		//创建能够执行sql的对象
		$dbMysql=new DbMysqlInterfaceImplModel();
		//计算边界
		$nestedSetsService=new NestedSetsService($dbMysql,'goods_category','lft','rgt','parent_id','id','level');
		//执行添加数据,返回对应id
		return $nestedSetsService->insert($this->data['parent_id'],$this->data,'bottom');
	}

	/**
	 * 商品分类的修改
	 * @return bool
	 */
	public function save(){
		//创建能够执行sql的对象
		$dbMysql=new DbMysqlInterfaceImplModel();
		//计算边界
		$nestedSetsService=new NestedSetsService($dbMysql,'goods_category','lft','rgt','parent_id','id','level');
		//节点的移动
		$nestedSetsService->moveUnder($this->data['id'],$this->data['parent_id']);
		return parent::save();
	}
	/**
	 * 根据id更改商品分类状态
	 * @param $id   数据ID
	 * @param int $status 数据状态,默认值为-1(移除)
	 * @return bool
	 */
	public function changeStatus($id, $status = -1) {
		$sql="SELECT child.id FROM goods_category AS child,goods_category AS parent WHERE parent.id=$id AND child.lft>=parent.lft AND child.rgt<=parent.rgt";
		$rows=$this->query($sql);
		$id=array_column($rows,'id');
		$data = array('id' => array('in', $id), 'status' => $status);
		//如果状态改为-1(移除),就将供货商的名称后面加一个'_del'的后缀
		if ($status == -1) {
			$data['name'] = array('exp', "concat(name,'_del')");
		}

		return parent::save($data);
	}

	/**
	 * 根据商品分类id获取叶子节点id
	 * @param $goods_category_id
	 */
	public function getCategoryLeafIds($goods_category_id){
		$sql="SELECT child.id FROM goods_category AS parent,goods_category AS child WHERE parent.id={$goods_category_id} AND parent.`lft`<=child.`lft` AND parent.`rgt`>=child.`rgt` AND child.`lft`+1=child.`rgt`";
		$rows=$this->query($sql);
		$ids=array_column($rows,'id');
		return $ids;
	}
}