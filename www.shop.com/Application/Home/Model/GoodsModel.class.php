<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 17:53
 */

namespace Home\Model;


use Think\Model;

class GoodsModel extends Model{
    /**
     * 通过商品状态和显示数量来查询商品信息
     * @param $goods_status
     * @param int $num
     * @return mixed
     */
    public function getGoodsByGoodsStatus($goods_status,$num=5){
        return $this->field('id,name,logo,shop_price')->where(array('status'=>1))->where("goods_status&{$goods_status}={$goods_status}")->limit($num)->select();
    }

    /**
     * 通过id查询商品的信息
     * @param $id
     */
    public function getGoodsById($id){
        $this->alias('obj')->field('obj.*,b.name as brand_name,gi.intro');
        $this->join('__GOODS_INTRO__ as gi on obj.id=gi.goods_id');
        $rows=$this->join('__BRAND__ as b on obj.brand_id=b.id')->where(array('obj.status'=>1,'obj.id'=>$id))->find();
        return $rows;
    }
}