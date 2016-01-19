<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 12:51
 */

namespace Home\Model;


use Think\Model;

class ArticleModel extends Model{
    public function getList(){
        $rows=S('ARTICLES');
        if(empty($rows)){
            $rows=$this->field('id,name,article_category_id')->where(array('status'=>1))->select();
            S('ARTICLES',$rows);
        }
        return $rows;
    }
}