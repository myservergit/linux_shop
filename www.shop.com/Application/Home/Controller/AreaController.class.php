<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/31
 * Time: 17:58
 */

namespace Home\Controller;


use Think\Controller;

class AreaController extends Controller{
    public function getChildByParentId($parent_id){
        $areaModel = D('Area');
        $rows = $areaModel->getChildByParentId($parent_id);
        $this->ajaxReturn($rows);
    }
}