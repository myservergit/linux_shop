<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsCategoryController extends BaseController {
    protected $meta_title='商品分类';
    /**
     * 供货商展示
     */
    public function index() {

        $rows = $this->model->getTreeList();
        $this->assign('rows',$rows);
        $this->assign('meta_title',$this->meta_title);
        $this->display('index');
    }
}