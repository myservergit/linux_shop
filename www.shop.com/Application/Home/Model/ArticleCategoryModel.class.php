<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 11:07
 */

namespace Home\Model;


use Think\Model;

class ArticleCategoryModel extends Model{
    public function getList(){
        $rows=S('ARTICLE_CATEGORYS');
        if(empty($rows)){
            $rows=$this->field('id,name')->where(array('is_help'=>1,'status'=>1))->select();
            S('ARTICLE_CATEGORYS',$rows);
        }
        return $rows;
    }
}