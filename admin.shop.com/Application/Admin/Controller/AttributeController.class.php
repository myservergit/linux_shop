<?php
namespace Admin\Controller;


use Think\Controller;

class AttributeController extends BaseController {
    protected $meta_title = '属性';

    protected function _setWheres(&$wheres) {
        $goods_type_id = I('get.goods_type_id');
        if (!empty($goods_type_id)) {
            $wheres['obj.goods_type_id'] = $goods_type_id;
        }

    }

    /**
     * 编辑页面的钩子方法
     */
    protected function _edit_view_before() {
        //查询商品类型
        $goodsTypeModel = D('GoodsType');
        $goodsTypes = $goodsTypeModel->getList('id,name');
        $this->assign('goodsTypes', $goodsTypes);
    }

    /**
     * 根据商品类型id查询出该类型下面的属性
     * @param $goods_type_id
     */
    public function getByGoodsTypeId($goods_type_id) {
        $rows = $this->model->getList('id,name,type,input_type,option_values', array('goods_type_id' => $goods_type_id));
        $this->ajaxReturn($rows);
    }
}