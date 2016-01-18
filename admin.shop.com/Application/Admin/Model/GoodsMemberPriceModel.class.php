<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/17
 * Time: 14:55
 */

namespace Admin\Model;


use Think\Model;

class GoodsMemberPriceModel extends Model{
    public function getMemberPrice($goods_id){
        $rows=$this->field('member_level_id,price')->where(array('goods_id'=>$goods_id))->select();
        $member_level_ids=array_column($rows,'member_level_id');
        $prices=array_column($rows,'price');
        $goods_member_prices=array_combine($member_level_ids,$prices);
        return $goods_member_prices;
    }
}