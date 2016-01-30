<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/30
 * Time: 14:34
 */

namespace Home\Controller;


use Think\Controller;

class OrderInfoController extends Controller {
    public function _initialize() {
        if (!isLogin()) {
            cookie('__login_return_url__',$_SERVER['REQUEST_URI']);
            $this->error('请先登录', U('Member/login'));
        }
    }

    public function add() {
        $ShoppingCarModel=D('ShoppingCar');
        $rows=$ShoppingCarModel->getList();
        $this->assign('rows',$rows);
        $this->display('order');
    }
}