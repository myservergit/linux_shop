<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 14:54
 */

namespace Admin\Controller;


use Think\Controller;

class BaseController extends Controller {

    protected $model;
    protected $usePostParams=false;

    public function _initialize() {
        $this->model = D(CONTROLLER_NAME);
    }

    /**
     * 供货商展示
     */
    public function index() {
        //获取关键字
        $keyword = I('get.keyword', '');
        $pageResult = $this->model->getPageResult($keyword);

        //将当前访问的url地址保存到cookie中
        cookie('__FORWORD__', $_SERVER['REQUEST_URI']);

        $this->assign($pageResult);
        $this->assign('meta_title', $this->meta_title);
        $this->display('index');
    }

    /**
     * 根据id更改供货商状态
     * @param $id   数据ID
     * @param int $status 数据状态,默认值为-1(移除)
     */
    public function changeStatus($id, $status = -1) {
        if ($this->model->changeStatus($id, $status) !== false) {
            $this->success('操作成功', cookie('__FORWORD__'));
        } else {
            $this->error('操作失败' . get_model_error($this->model));
        }
    }

    /**
     * 供货商添加
     */
    public function add() {
        if (IS_POST) {
            if ($this->model->create() !== false) {
                if ($this->model->add($this->usePostParams?I('post.'):'') !== false) {
                    $this->success('添加成功', U('index'));
                    return;
                }
            }
            $this->error('操作失败' . get_model_error($this->model));
        } else {
            $this->_edit_view_before();
            $this->assign('meta_title', '添加' . $this->meta_title);
            $this->display('edit');
        }
    }

    /**
     * 该方法主要是被子类覆盖, 为编辑页面展示之前准备数据.
     */
    protected function _edit_view_before() {

    }

    /**
     * 供应商的编辑
     * @param $id   数据ID
     */
    public function edit($id) {
        if (IS_POST) {
            if ($this->model->create() !== false) {
                if ($this->model->save($this->usePostParams?I('post.'):'') !== false) {
                    $this->success('修改成功', cookie('__FORWORD__'));
                    return;
                }
            }
            $this->error('操作失败' . get_model_error($this->model));
        } else {
            $this->_edit_view_before();
            $row = $this->model->find($id);
            $this->assign($row);
            $this->assign('meta_title', '编辑' . $this->meta_title);
            $this->display('edit');
        }
    }
}