<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/17
 * Time: 16:20
 */

namespace Admin\Model;


use Think\Model;

class GoodsAttributeModel extends Model
{
    /**
     * 返回属性对应的值
     * array(
         'attribute_id'=>array(value),
         'attribute_id'=>array(value),
         'attribute_id'=>array(value),
     * )
     * @param $goods_id
     * @return array
     */
    public function getList($goods_id){
        $rows = $this->where(array('goods_id'=>$goods_id))->field('attribute_id,value')->select();
        //将上面的数据变成:   使用属性id作为键, 使用value作为值
        $temp = array();
        foreach($rows as $row){
            $temp[$row['attribute_id']][] = $row['value'];
        }
        return $temp;
    }
}