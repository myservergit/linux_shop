<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 22:14
 */

namespace Home\Model;


use Think\Model;

class GoodsPhotoModel extends Model {
    /**
     * 根据商品id查询相册图片路径
     * @param $goods_id
     * @return mixed
     */
    public function getPhotoPathById($goods_id) {
        $rows=$this->field('path')->where(array('goods_id' => $goods_id))->select();
        return array_column($rows,'path');
    }
}