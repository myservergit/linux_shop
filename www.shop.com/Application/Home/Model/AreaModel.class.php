<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/31
 * Time: 17:18
 */

namespace Home\Model;


use Think\Model;

class AreaModel extends Model{
    public function getChildByParentId($parent_id=0){
        return $this->where(array('parent_id'=>$parent_id))->select();
    }
}