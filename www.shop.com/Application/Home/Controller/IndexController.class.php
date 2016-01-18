<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
    public function index() {

        //准备导航页的展示数据
        $goodsCategoryModel = D('GoodsCategory');
        $goods_categorys = $goodsCategoryModel->getTreeList();
        $this->assign('goods_categorys', $goods_categorys);

        $this->assign('meta_title', '首页');
        $this->display('index');
    }

    public function lst() {

        $this->assign('meta_title', '商品列表');
        $this->assign('is_hide', true);
        $this->display('lst');
    }

    public function goods() {
        $this->assign('meta_title', 'XX商品');
        $this->assign('is_hide', true);
        $this->display('goods');
    }
}