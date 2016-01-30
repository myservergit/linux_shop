<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/22
 * Time: 13:15
 */

namespace Home\Model;


use Think\Model;

class ShoppingCarModel extends Model {
    /*
    * 两种情况删除购物车中的商品
    */
    public function remove($requestData) {
        $userinfo = session('USERINFO');
        if (!empty($userinfo)) {
            $this->removeDb($requestData);
        } else {  //未登录的购物车数据保存
            $this->removeCookie($requestData);
        }
    }

    /**
     * 登录下的购物车商品删除
     * @param $requestData
     */
    public function removeDb($goods_id) {
        return $this->where(array('goods_id' => $goods_id))->delete();
    }

    /**
     * 未登陆下的购物车中商品的删除
     * @param $requestData
     */
    public function removeCookie($goods_id) {
        $shopping_cars = unserialize(cookie('SHOPPING_CAR'));
        foreach ($shopping_cars as $key => $value) {
            if ($value['goods_id'] == $goods_id) {
                unset($shopping_cars[$key]);
            }
        }
        cookie('SHOPPING_CAR', serialize($shopping_cars), 2592000);
    }

    /*
     * 两种情况将商品添加到购物车中
     */
    public function add($requestData) {
        if (isLogin()) {
            $this->addDb($requestData);
        } else {  //未登录的购物车数据保存
            $this->addCookie($requestData);
        }
    }

    /**
     * 登录情况下的商品添加到数据库中
     * @param $requestData
     */
    public function addDb($requestData) {
        $row = $this->where(array('member_id' => UID, 'goods_id' => $requestData['goods_id']))->find();
        if ($row) {
            return $this->where(array('member_id' => UID, 'goods_id' => $requestData['goods_id']))->setInc('num', $requestData['num']);
        } else {
            $data = array('member_id' => UID, 'goods_id' => $requestData['goods_id'], 'num' => $requestData['num']);
            return parent::add($data);
        }
    }

    /**
     * 未登录添加商品到购物车
     * @param $requestData
     */
    public function addCookie($requestData) {
        $shopping_cars = cookie('SHOPPING_CAR');
        if (empty($shopping_cars)) {    //cooike中没有数据
            $shopping_cars = array();
        } else {  //cookie中有数据
            $shopping_cars = unserialize($shopping_cars);   //数据反序列化
        }

        //判断是否向cookie中添加商品,true添加,false不添加
        $flag = true;

        //判断新添加的商品是否已添加
        foreach ($shopping_cars as &$shopping_car) {
            if ($shopping_car['goods_id'] == $requestData['goods_id']) {
                $shopping_car['num'] += $requestData['num'];
                $flag = false;
                break;
            }
        }
        unset($shopping_car);

        if ($flag) {
            $shopping_cars[] = $requestData;
        }

        //二维数组添加到cookie中无法保存,需先序列化
        cookie('SHOPPING_CAR', serialize($shopping_cars), 2592000);

    }

    /**
     * 获取购物车上的商品
     * @return mixed
     */
    public function getList() {
        if (isLogin()) {
            $row = $this->field('obj.*,g.`shop_price` as price,g.`logo`,g.`name`')->alias('obj')->join('goods AS g ON obj.`goods_id`=g.`id`')->order('id')->select();
            return $row;
        } else {  //未登录的情况
            $shopping_cars = unserialize(cookie('SHOPPING_CAR'));
            $this->rebuild($shopping_cars);
            return $shopping_cars;
        }
    }

    /**
     * 将购物车中的商品信息重构
     * @param $shopping_cars
     */
    public function rebuild(&$shopping_cars) {
        $goodsModel = D('Goods');
        foreach ($shopping_cars as &$shopping_car) {
            $row = $goodsModel->field('name,logo,shop_price')->find($shopping_car['goods_id']);
            $shopping_car['name'] = $row['name'];
            $shopping_car['logo'] = $row['logo'];
            $shopping_car['price'] = $row['shop_price'];
        }
        unset($shopping_car);
    }

    /**
     * 等登录后,将cookie中的商品数据保存到数据库中,并清空cookie('SHOPPING_CAR')中的数据
     */
    public function cookie2db() {
        if (cookie('SHOPPING_CAR')) {
            foreach (unserialize(cookie('SHOPPING_CAR')) as $item) {
                $this->addDb($item);
            }
        }
        cookie('SHOPPING_CAR', null);
    }

    /*
     * 两种情况修改购物车中商品数量
     */
    public function update($requestData) {
        if (isLogin()) {
           return $this->updateDb($requestData);
        } else {  //未登录的购物车数据保存
            $this->updateCookie($requestData);
        }
    }

    /**
     * 登录下的购物车中商品的数量改变
     * @param $requestData
     * @return bool
     */
    public function updateDb($requestData) {
       return $this->where(array('member_id' => UID, 'goods_id' => $requestData['goods_id']))->setField('num',$requestData['num']);

    }

    /**
     * 未登录下的购物车中商品的数量改变
     * @param $requestData
     */
    public function updateCookie($requestData) {
        $shopping_cars = unserialize(cookie('SHOPPING_CAR'));
        foreach ($shopping_cars as &$shopping_car) {
            if ($shopping_car['goods_id'] == $requestData['goods_id']) {
                $shopping_car['num'] = $requestData['num'];
                break;
            }
        }
        unset($shopping_car);
        cookie('SHOPPING_CAR', serialize($shopping_cars), 2592000);
    }

}