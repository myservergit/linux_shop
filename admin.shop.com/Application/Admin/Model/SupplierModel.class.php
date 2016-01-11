<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/6
 * Time: 21:36
 */

namespace Admin\Model;


use Think\Model;

class SupplierModel extends BaseModel {
    // 自动验证定义
    protected $_validate = array(
        array('name', 'require', '名称不能为空!'),
        array('name', '', '名称已存在!请重新填写!', '', 'unique'),
        array('intro', 'require', '简介不能为空!'),
    );
}