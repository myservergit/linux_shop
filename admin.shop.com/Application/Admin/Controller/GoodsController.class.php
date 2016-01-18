<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsController extends BaseController {
    protected $meta_title = '商品';
    //是否使用I('post.')收集数据
    protected $usePostParams = true;

    /**
     * 实现该钩子方法,收集高级查询的条件
     */
    protected function _setWheres(&$wheres) {
        //获取商品品牌
        $brand_id = I('get.brand_id', '');
        if (!empty($brand_id)) {
            $wheres['obj.brand_id'] = $brand_id;
        }

        //获取商品供货商
        $supplier_id = I('get.supplier_id', '');
        if (!empty($supplier_id)) {
            $wheres['obj.supplier_id'] = $supplier_id;
        }

        //获取商品分类
        $goods_category_id=I('get.goods_category_id', '');
        if (!empty($goods_category_id)) {
            $goodsCategoryModel=D('GoodsCategory');
            $CategoryLeafIds=$goodsCategoryModel->getCategoryLeafIds($goods_category_id);
            $wheres['obj.goods_category_id'] = array('in',$CategoryLeafIds);
        }
    }

    /**
     * 实现该钩子方法,为搜索品牌和供货商的下拉列表准备数据
     */
    protected function _index_view_before() {
        //品牌下拉列表数据
        $brandModel = D('Brand');
        $brands = $brandModel->getList('id,name');
        $this->assign('brands', $brands);

        //供货商下拉列表数据
        $supplierModel = D('Supplier');
        $suppliers = $supplierModel->getList('id,name');
        $this->assign('suppliers', $suppliers);

        //分类树的数据
        $goodsCategoryModel = D('GoodsCategory');
        $goods_categorys = $goodsCategoryModel->getTreeList(true, 'id,name,parent_id');
        $this->assign('zNodes', $goods_categorys);
    }

    /**
     * 实现父类的钩子方法,为编辑页面展示之前准备数据
     */
    protected function _edit_view_before() {
        //准备页面中ztree需要的商品分类的数据
        $goodsCategoryModel = D('GoodsCategory');
        $rows = $goodsCategoryModel->getTreeList(true, 'id,name,parent_id');
        $this->assign('zNodes', $rows);

        //准备商品品牌展示数据
        $brandModel = D('Brand');
        $brands = $brandModel->getList('id,name');
        $this->assign('brands', $brands);

        //准备供货商展示数据
        $supplierModel = D('Supplier');
        $suppliers = $supplierModel->getList('id,name');
        $this->assign('suppliers', $suppliers);

        //准备会员展示数据
        $memberLevelModel = M('MemberLevel');
        $members = $memberLevelModel->select();
        $this->assign('members', $members);

        //编辑页面回显的数据准备
        $id = I('get.id');
        if (!empty($id)) {
            //商品详细描述数据准备
            $goodsIntroModel = M('GoodsIntro');
            $intro = $goodsIntroModel->getFieldByGoods_id($id, 'intro');
            $this->assign('intro', $intro);

            //商品会员价格数据准备
            $goodsMemberPriceModel = D('GoodsMemberPrice');
            $goods_member_prices = $goodsMemberPriceModel->getMemberPrice($id);
            $this->assign('goods_member_prices', $goods_member_prices);

            //商品相册图片
            $goodsPhotoModel = D('GoodsPhoto');
            $goods_photos = $goodsPhotoModel->field('id,path')->where(array('goods_id' => $id))->select();
            $this->assign('goods_photos', $goods_photos);

            //商品相关文章
            $goodsArticleModel = D('GoodsArticle');
            $goods_articles = $goodsArticleModel->getArticle($id);
            $this->assign('goods_articles', $goods_articles);
        }
    }
}