<?php
namespace Admin\Model;


use Think\Model;

class ArticleModel extends BaseModel {
    protected $_validate = array(
        array('name', 'require', '文章名称不能为空!'),
		array('article_category_id', 'require', '文章分类ID不能为空!'),
		array('inputtime', 'require', '录入时间不能为空!'),
		array('status', 'require', '是否显示不能为空!'),
	);
	/**
	 * 自动加载
	 * @var array
	 */
	protected $_auto = array(
		array('inputtime',NOW_TIME),
	);

	/**
	 * 覆盖父类钩子方法
	 */
	protected function _setModel(){
		$this->join('__ARTICLE_CATEGORY__ AS ac ON obj.`article_category_id`=ac.`id`');
		$this->field('obj.*,ac.name as article_category_name');
	}
	/**
	 * 文章的添加
	 * @param mixed|string $requestData
	 * @return bool|mixed
	 */
	public function add($requestData){
		$id=parent::add();
		if($id===false){
			$this->error="添加文章失败";
			return false;
		}
		$articleContentModel=M('ArticleContent');
		$rst=$articleContentModel->where(array('article_id'=>$id))->add(array('article_id'=>$id,'content'=>$requestData['content']));
		if($rst===false){
			$this->error="添加文章失败";
			return false;
		}
		return $id;
	}

	/**
	 * 文章的修改
	 * @param mixed|string $requestData
	 * @return bool|mixed
	 */
	public function save($requestData){
		$rst=parent::save();
		if($rst===false){
			$this->error="修改文章失败";
			return false;
		}
		$articleContentModel=M('ArticleContent');
		$rst=$articleContentModel->where(array('article_id'=>$requestData['id']))->save(array('article_id'=>$requestData['id'],'content'=>$requestData['content']));
		if($rst===false){
			$this->error="修改文章失败";
			return false;
		}
	}
}