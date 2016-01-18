<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/18
 * Time: 9:51
 */

namespace Admin\Model;


use Think\Model;

class GoodsArticleModel extends Model {
    public function getArticle($id) {
        $this->alias('ga');
        $this->join('__ARTICLE__ AS a ON ga.`article_id`=a.`id`');
        $goods_articles = $this->field('article_id,a.name as article_name')->where(array('goods_id' => $id))->select();
        return $goods_articles;
    }
}