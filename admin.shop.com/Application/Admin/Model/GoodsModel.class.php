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

    protected function _handleRows(&$rows) {
        foreach ($rows as &$row) {
            $row['is_best'] = ($row['goods_status'] & 1);
            $row['is_new'] = (($row['goods_status'] & 2) >> 1);
            $row['is_hot'] = (($row['goods_status'] & 4) >> 2);
            $row['is_crazy'] = (($row['goods_status'] & 8) >> 3);
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
        $this->data['add_time'] = NOW_TIME;
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

        //处理商品属性
        $result = $this->addGoodsAttribute($id, $requestData['goodsAttribute']);
        if ($result === false) {
            return false;
        }

        //事务的提交
        $this->commit();
        return $id;
    }

    /**
     * 将商品属性的值保持到goods_attribute表中
     * @param $goods_id
     * @param $goodsAttributes
     */
    private function addGoodsAttribute($goods_id, $goodsAttributes) {
        $rows = array();

        foreach ($goodsAttributes as $attribute_id => $value) {
            if (is_array($value)) {  //该属性是一个多值属性,对应的值是多个值
                foreach ($value as $v) {
                    $rows[] = array('goods_id' => $goods_id, 'attribute_id' => $attribute_id, 'value' => $v);
                }
            } else {  //单值属性
                $rows[] = array('goods_id' => $goods_id, 'attribute_id' => $attribute_id, 'value' => $value);
            }
        }

        if (!empty($rows)) {
            $goodsAttributeModel = M('GoodsAttribute');
            $result = $goodsAttributeModel->addAll($rows);
            if ($result === false) {
                $this->error = '添加属性的值失败!';
                return false;
            }
        }

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

        //更新商品属性
        $result = $this->updateGoodsAttribute($goods_id, $requestData['goodsAttribute']);
        if ($result === false) {
            return false;
        }

        //事务的提交
        $this->commit();
        return $rst;
    }

    /**
     * 更新商品属性
     * @param $goods_id
     * @param $goodsAttributes
     */
    private function updateGoodsAttribute($goods_id, $goodsAttributes) {
        $goodsAttributeModel = D('GoodsAttribute');
        //查询出数据库中当前商品的所有多值属性
        $sql = "SELECT ga.* FROM `goods_attribute` as ga  join attribute as a on ga.attribute_id=a.id   where ga.goods_id = $goods_id and a.type=1";
        $multGoodsAttributeinDB = array();
        $rows = $this->query($sql);
        foreach ($rows as $row) {
            $multGoodsAttributeinDB[$row['attribute_id']][] = $row['value'];    //一个属性的id==>多个值(属性的形式表)
        }
        //循环请求中的每个属性, 和数据库中的每个属性进行对比
        foreach ($goodsAttributes as $attribute_id => $values) {
            if (!is_array($values)) {   //单值属性,直接将属性的值更新到数据库表中
                $result = $goodsAttributeModel->where(array('goods_id' => $goods_id, 'attribute_id' => $attribute_id))->save(array('value' => $values));
                if ($result === false) {
                    $this->error = '更新单值属性失败!';
                    return false;
                }
            } else {
                //多值属性的更新..
                /**
                 *  请求中没有,数据库中有, 删除请求中没有数据库中有的数据
                 *  请求中没有,数据库中没有,   不用处理
                 *  请求中有,数据库中没有,   将请求中有的数据添加数据库中
                 *  请求中有,数据库中有,       不用处理
                 */
                $requestValues = $values;   //起一个别名. 该$requestValues代表其中一个多值属性的值
                $dbValues = $multGoodsAttributeinDB[$attribute_id];  //根据attribute_id得到数据库中属性对应的值
                //先检查请求中的数据是否在数据库中,如果不在, 说明请求中有,数据库中没有, 需要将数据添加到数据库中
                foreach ($requestValues as $requestValue) {
                    if (!in_array($requestValue, $dbValues)) {  //检查请求中的值,是否在数据库中
                        $result = $goodsAttributeModel->add(array('goods_id' => $goods_id, 'attribute_id' => $attribute_id, 'value' => $requestValue));
                        if ($result === false) {
                            $this->error = '更新多值属性失败!';
                            return false;
                        }
                    }
                }
            }
        }
        //循环数据库中的每个属性, 和请求中的每个属性进行对比
        //数据库中有,请求中没有的.     需要拿着数据库中的每个属性对应的值, 对应标签中属性对应的值.
        foreach ($multGoodsAttributeinDB as $attribute_id => $valuesInDB) {
            foreach ($valuesInDB as $valueInDB) {
                //检查数据库中的值,不在请求中,需要删除数据库中值
                if (!in_array($valueInDB, $goodsAttributes[$attribute_id])) {
                    $result = $goodsAttributeModel->where(array('goods_id' => $goods_id, 'attribute_id' => $attribute_id, 'value' => $valueInDB))->delete();
                    if ($result === false) {
                        $this->error = '更新多值属性失败!';
                        return false;
                    }
                }
            }
        }
    }
}