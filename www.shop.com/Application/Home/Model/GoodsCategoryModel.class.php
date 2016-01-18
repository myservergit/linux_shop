<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/18
 * Time: 22:46
 */

namespace Home\Model;


use Think\Model;

class GoodsCategoryModel extends Model {
    public function getTreeList() {
        $rows = $this->field('id,name,parent_id,level')->where(array('status' => 1))->order('lft')->select();
        return $rows;
    }
}