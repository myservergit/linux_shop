<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
    /**
     * 数据初始化
     */
    public function _initialize() {
        //准备导航页的展示数据
        $goodsCategoryModel = D('GoodsCategory');
        $goods_categorys = $goodsCategoryModel->getTreeList();
        $this->assign('goods_categorys', $goods_categorys);

        //准备底部导航的展示数据
        $articleCategoryModel = D('ArticleCategory');
        $article_categorys = $articleCategoryModel->getList();
        $this->assign('article_categorys', $article_categorys);

        //准备底部导航的文章的数据
        $articleModel = D('Article');
        $articles = $articleModel->getList();
        $this->assign('articles', $articles);
    }

    /**
     * 商城首页
     */
    public function index() {

        //准备导购左边区域的商品信息数据
        $goodsModel = D('Goods');
        $bestGoods = $goodsModel->getGoodsByGoodsStatus(1);
        $newsGoods = $goodsModel->getGoodsByGoodsStatus(2);
        $hotGoods = $goodsModel->getGoodsByGoodsStatus(4);
        $crazyGoods = $goodsModel->getGoodsByGoodsStatus(8);
        $likeGoods = $goodsModel->getGoodsByGoodsStatus(16);
        $goodsStatus = array(
            'bestGoods' => $bestGoods,
            'newsGoods' => $newsGoods,
            'hotGoods' => $hotGoods,
            'crazyGoods' => $crazyGoods,
            'likeGoods' => $likeGoods,
        );
        $this->assign('goodsStatus', $goodsStatus);

        $this->assign('meta_title', '首页');
        $this->display('index');
    }

    /**
     * 商品列表页面
     */
    public function lst() {
        $this->assign('meta_title', '商品列表');
        $this->assign('is_hide', true);
        $this->display('lst');
    }

    /**
     * 商品信息页面
     * @param $id
     */
    public function goods($id) {
        //查询商品的详细信息
        $goodsModel = D('Goods');
        $goods = $goodsModel->getGoodsById($id);
        $this->assign($goods);

        //查询商品相册
        $goodsPhotoModel=D('GoodsPhoto');
        $photo_paths=$goodsPhotoModel->getPhotoPathById($id);
        array_unshift($photo_paths,$goods['logo']);
        $this->assign('photo_paths', $photo_paths);

        //查询面包屑导航数据
        $goodsCategoryModel=D('GoodsCategory');
        $parents=$goodsCategoryModel->getParents($goods['goods_category_id']);
        $this->assign('parents', $parents);

        //记录最近浏览过得商品
        $historys=cookie('historys');
        if(empty($historys)||unserialize($historys)===false){
            $historys=array();
        }else{
            $historys = unserialize($historys);
        }
        $this->assign('historys',$historys);
        //判断之前是否浏览过,重复则删除之前的浏览记录
        foreach($historys as $key=>$history){
            if($history['id']==$id){
                unset($historys[$key]);
                break;
            }
        }
        //保存记录
        $history=array('id'=>$id,'name'=>$goods['name'],'logo'=>$goods['logo']);
        array_unshift($historys,$history);
        cookie('historys',serialize($historys));

        $this->assign('meta_title', $goods['name']);
        $this->assign('is_hide', true);
        $this->display('goods');
    }
}