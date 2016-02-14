<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/31
 * Time: 16:09
 */

namespace Home\Controller;


use Think\Controller;

class AddressController extends BaseController {
    public function _initialize() {
        parent::_initialize();
        if (!isLogin()) {
            cookie('__login_return_url__', $_SERVER['REQUEST_URI']);
            $this->error('请登录', U('Member/login'));
        }
    }

    /**
     * 收货地址页面的数据准备
     */
    public function index() {
        //准备导航页的展示数据
        $goodsCategoryModel = D('GoodsCategory');
        $goods_categorys = $goodsCategoryModel->getTreeList();
        $this->assign('goods_categorys', $goods_categorys);

        //准备底部导航的展示数据
        $articleCategoryModel = D('ArticleCategory');
        $article_categorys = $articleCategoryModel->getList();
        $this->assign('article_categorys', $article_categorys);

        //准备底部导航的文章的数据
        $articleModel = D('Article');
        $articles = $articleModel->getList();
        $this->assign('articles', $articles);

        //查询省份
        $areaModel = D('Area');
        $provinces = $areaModel->getChildByParentId();
        $this->assign('provinces', $provinces);

        //查询收货地址信息
        $addressModel = D('Address');
        $addresses = $addressModel->getList();
        $this->assign('addresses', $addresses);

        cookie('__FORWORD__',$_SERVER['REQUEST_URI']);
        $this->assign('is_hide', true);
        $this->assign('meta_title', '收货地址');
        $this->display('index');
    }


    /**
     * 地址的添加
     */
    public function add() {
        if (IS_POST) {
            if (($data=$this->model->create()) !== false) {
                if(empty($data['id'])){
                    if (($id = $this->model->add($this->usePostParams ? I('post.') : '')) !== false) {
                        $this->success($id, cookie('__FORWORD__'));
                        return;
                    }
                }else{  //有id的值,则为编辑
                    if ($this->model->save() !== false) {
                        $this->success($data['id'], cookie('__FORWORD__'));
                        return;
                    }
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
     * 修改默认地址
     * @param $id
     */
    public function setDefault($id) {
        $rst=$this->model->setDefault($id);
        if($rst!==false){
            $this->success('设置成功!',U('index'));
        }else{
            $this->error('设置失败!');
        }
    }

    /**
     * 修改地址的回显数据
     * @param 数据ID $id
     */
    public function edit($id){
        $row=$this->model->find($id);
        $this->ajaxReturn($row);
    }

    /**
     * 通过id获取用户地址
     * @param $id
     */
    public function getAddressById($id){
        $row=$this->model->find($id);
        $this->ajaxReturn($row);
    }
}