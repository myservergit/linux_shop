<?php
namespace Admin\Controller;


use Think\Controller;

class ArticleController extends BaseController {
    protected $meta_title = '文章';

    protected $usePostParams=true;

    /**
     * 实现父类的钩子方法,为编辑页面展示之前准备数据
     */
    protected function _edit_view_before() {
        $articleCategoryModel=D('ArticleCategory');
        $article_categorys=$articleCategoryModel->getList('id,name');
        $this->assign('article_categorys',$article_categorys);

        //编辑页面回显的数据准备
        $id=I('get.id');
        if($id){
            //文章内容
            $articleContentModel=M('ArticleContent');
            $content=$articleContentModel->getFieldByArticle_id($id,'content');
            $this->assign('content',$content);
        }
    }
    public function search($keyword){
        $wheres=array();
        if(!empty($keyword)){
            $wheres['name']=array('like',"%$keyword%");
        }
        $rows=$this->model->getList('id,name',$wheres);
        $this->ajaxReturn($rows);
    }
}