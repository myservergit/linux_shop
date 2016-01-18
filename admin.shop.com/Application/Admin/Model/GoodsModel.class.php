<?php
namespace Admin\Model;


use Think\Model;

class GoodsModel extends BaseModel {
    protected $_validate_ = array(
        array('name', 'require', '商品名称不能为空!'),
        array('short_name', 'require', '简称不能为空!'),
        array('sn', 'require', '货号不能为空!'),
        array('goods_category_id', 'require', '商品分类不能为空!'),
        array('brand_id', 'require', '商品品牌不能为空!'),
        array('supplier_id', 'require', '供货商不能为空!'),
        array('shop_price', 'require', '本店价格不能为空!'),
        array('market_price', 'require', '市场价格不能为空!'),
        array('logo', 'require', '商品LOGO不能为空!'),
        array('stock', 'require', '库存不能为空!'),
        array('goods_status', 'require', '商品状态不能为空!'),
        array('status', 'require', '是否显示不能为空!'),
    );
    protected function _handleRows(&$rows){
        foreach($rows as &$row){
            $row['is_best']=($row['goods_status']&1);
            $row['is_new']=(($row['goods_status']&2)>>1);
            $row['is_hot']=(($row['goods_status']&4)>>2);
        }
        unset($row);
    }

    /**
     * 覆盖父类钩子方法
     */
    protected function _setModel() {
        $this->join('__GOODS_CATEGORY__ AS gc ON obj.`goods_category_id`=gc.`id`');
        $this->join('__BRAND__ AS b ON obj.`brand_id`=b.`id`');
        $this->join('__SUPPLIER__ AS s ON obj.`supplier_id`=s.`id`');
        $this->field('obj.*,gc.name AS goods_category_name,b.name AS goods_brand_name,s.`name` AS goods_supplier_name');
    }

    /**
     * 添加商品信息
     * @param mixed|string $requestData
     * @return bool
     */
    public function add($requestData) {
        $this->startTrans();
        $this->handleGoodsStatus();
        $id = parent::add();
        if ($id === false) {
            $this->rollback();
            return false;
        }

        //添加商品货号
        $sn = date('Ymd') . str_pad($id, 9, "0", STR_PAD_LEFT);
        $rst = parent::save(array('id' => $id, 'sn' => $sn));
        if ($rst === false) {
            $this->rollback();
            $this->error = '添加商品货号失败';
            return false;
        }

        //添加商品详细描述
        $goodsIntroModel = M('GoodsIntro');
        $rst = $goodsIntroModel->add(array('goods_id' => $id, 'intro' => $requestData['intro']));
        if ($rst === false) {
            $this->rollback();
            $this->error = '添加商品详细描述失败';
            return false;
        }

        //添加会员价格
        $rst = $this->handleGoodsMemberPrice($id, $requestData['goods_member_prices']);
        if ($rst === false) {
            return false;
        }

        //添加商品相册图片
        $rst = $this->handleGoodsPhoto($id, $requestData['goods_photo_paths']);
        if ($rst === false) {
            return false;
        }

        //添加相关文章
        $rst = $this->handleGoodsArticle($id, $requestData['article_ids']);
        if ($rst === false) {
            return false;
        }

        //事务的提交
        $this->commit();
        return $id;
    }

    /**
     * 添加相关文章
     * @param $goods_id
     * @param $path
     */
    public function handleGoodsArticle($goods_id, $article_ids) {
        $goodsArticleModel = M('GoodsArticle');
        //添加相关文章之前先删除
        $rst = $goodsArticleModel->where(array('goods_id' => $goods_id))->delete();
        if ($rst === false) {
            $this->error = '删除相关文章失败';
            $this->rollback();
            return false;
        }

        //添加相关文章
        $rows = array();
        foreach ($article_ids as $article_id) {
            $rows[] = array('goods_id' => $goods_id, 'article_id' => $article_id);
        }
        if (!empty($rows)) {
            $rst = $goodsArticleModel->addAll($rows);
            if ($rst === false) {
                $this->error = '添加相关文章失败';
                $this->rollback();
                return false;
            }
        }
    }

    /**
     * 添加商品相册图片
     * @param $goods_id
     * @param $path
     */
    public function handleGoodsPhoto($goods_id, $paths) {
        $rows = array();
        foreach ($paths as $path) {
            $rows[] = array('goods_id' => $goods_id, 'path' => $path);
        }
        if (!empty($rows)) {
            $goodsPhotoModel = M('GoodsPhoto');
            $rst = $goodsPhotoModel->addAll($rows);
            if ($rst === false) {
                $this->error = '添加商品相册图片失败';
                $this->rollback();
                return false;
            }
        }
    }

    /**
     * 根据商品id保存会员价格和修改会员价格
     * @param $goods_id
     * @param $goods_member_prices
     * @return bool
     */
    private function handleGoodsMemberPrice($goods_id, $goods_member_prices) {
        $goodsMemberPriceModel = M('GoodsMemberPrice');
        //添加会员价格之前先删除
        $rst = $goodsMemberPriceModel->where(array('goods_id' => $goods_id))->delete();
        if ($rst === false) {
            $this->error = '删除会员价格失败';
            $this->rollback();
            return false;
        }

        //添加会员价格
        $rows = array();
        foreach ($goods_member_prices as $member_level_id => $price) {
            $rows[] = array('goods_id' => $goods_id, 'member_level_id' => $member_level_id, 'price' => $price);
        }
        if (!empty($rows)) {
            $rst = $goodsMemberPriceModel->addAll($rows);
            if ($rst === false) {
                $this->error = '添加会员价格失败';
                $this->rollback();
                return false;
            }
        }
    }

    /**
     * 计算商品状态
     */
    private function handleGoodsStatus() {
        $goods_status = 0;
        foreach ($this->data['goods_status'] as $v) {
            $goods_status = $goods_status | $v;
        }
        $this->data['goods_status'] = $goods_status;
    }

    /**
     * 商品信息的修改
     * @param mixed|string $requestData
     * @return bool
     */
    public function save($requestData) {
        $goods_id = $this->data['id'];

        $this->startTrans();

        $this->handleGoodsStatus();
        $rst = parent::save();
        if ($rst === false) {
            $this->rollback();
            return false;
        }
        //修改商品详细描述
        $goodsIntroModel = M('GoodsIntro');
        $rst = $goodsIntroModel->save(array('goods_id' => $goods_id, 'intro' => $requestData['intro']));
        if ($rst === false) {
            $this->rollback();
            $this->error = '修改商品详细描述失败';
            return false;
        }

        //商品会员价格的修改
        $rst = $this->handleGoodsMemberPrice($goods_id, $requestData['goods_member_prices']);
        if ($rst === false) {
            return false;
        }

        //修改商品相册图片
        $rst = $this->handleGoodsPhoto($goods_id, $requestData['goods_photo_paths']);
        if ($rst === false) {
            return false;
        }

        //修改相关文章
        $rst = $this->handleGoodsArticle($goods_id, $requestData['article_ids']);
        if ($rst === false) {
            return false;
        }
        //事务的提交
        $this->commit();
        return $rst;
    }
}