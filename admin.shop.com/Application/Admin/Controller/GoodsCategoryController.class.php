<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsCategoryController extends BaseController {
    protected $meta_title = '商品分类';

    /**
     * 商品分类展示
     */
    public function index() {
        $rows = $this->model->getTreeList();
        $this->assign('rows', $rows);

        $this->assign('meta_title', $this->meta_title);
        cookie('__FORWORD__', $_SERVER['REQUEST_URI']);
        $this->display('index');
    }

    /**
     * 实现父类的钩子方法,为编辑页面展示之前准备数据
     */
    protected function _edit_view_before() {
        //准备页面中ztree需要的数据
        $rows = $this->model->getTreeList(true, 'id,name,parent_id');
        $this->assign('zNodes', $rows);
    }

    /**
     * 根据id更改商品分类状态
     * @param $id   数据ID
     * @param int $status 数据状态,默认值为-1(移除)
     */
    public function changeStatus($id, $status = -1) {
        if ($this->model->changeStatus($id, $status) !== false) {
            $this->success('操作成功', U('index'));
        } else {
            $this->error('操作失败' . get_model_error($this->model));
        }
    }
}