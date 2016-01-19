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
    /**
     * 查询导航数据
     * @return mixed
     */
    public function getTreeList() {
        $rows=S('GOODS_CATEGORYS');
        if(empty($rows)){
            $rows = $this->field('id,name,parent_id,level')->where(array('status' => 1))->order('lft')->select();
            S('GOODS_CATEGORYS',$rows);
        }
        return $rows;
    }
    public function getParents($goods_category_id){
        $sql="SELECT child.* FROM goods_category AS parent,goods_category AS child WHERE parent.`lft`>=child.`lft` AND parent.rgt<=child.`rgt` AND parent.status=1 AND parent.id=$goods_category_id ORDER BY child.`lft`";
        return $this->query($sql);
    }
}