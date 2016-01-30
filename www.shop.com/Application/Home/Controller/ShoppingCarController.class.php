<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/22
 * Time: 12:54
 */

namespace Home\Controller;


use Think\Controller;

class ShoppingCarController extends Controller {
    /**
     * 购物车页面的数据展示页面
     */
    public function index() {
        $ShoppingCarModel=D('ShoppingCar');
        $rows=$ShoppingCarModel->getList();
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     * 添加商品到购物车中
     */
    public function add() {
        $ShoppingCarModel = D('ShoppingCar');
        if ($ShoppingCarModel->add(I('post.')) !== false) {
            $this->success('添加成功', U('index'));
        } else {
            $this->error('添加失败');
        }
    }
    /**
     * 删除购物车中的商品
     */
    public function remove() {
        $shoppingCarModel = D('ShoppingCar');
        if ($shoppingCarModel->remove(I('get.id')) !== false) {
            $this->success('删除成功', U('index'));
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 购物车中商品的数量改变
     * @return bool
     */
    public function update(){
        $shoppingCarModel = D('ShoppingCar');
        if($shoppingCarModel->update(I('post.'))===false){
            return false;
        }
    }
}