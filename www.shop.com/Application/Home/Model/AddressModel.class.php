<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/1
 * Time: 15:18
 */

namespace Home\Model;


class AddressModel extends BaseModel {
    protected $_auto = array(
        array('member_id', UID)
    );
    /**
     * 获取状态大于-1的地址数据
     * @return mixed
     */
    public function getList($field = '*', $wheres = array()) {
        $wheres['status'] = array('gt', -1);
        return $this->field($field)->where($wheres)->order('id')->select();
    }

    /**
     * 添加地址
     * @return bool|mixed
     */
    public function add() {
        if (!empty($this->data['is_default'])) {
            $result = $this->where(array('member_id' => UID, 'status' => 1))->setField('is_default', 0);
            if ($result === false) {
                return false;
            }
        }
        return parent::add();
    }

    /**
     * 修改默认地址
     * @param $id
     */
    public function setDefault($id) {
        //先把当前用户的所有地址设置为非默认
        $rst=$this->where(array('member_id'=>UID,'status'=>1))->setField('is_default',0);
        if($rst===false){
            return false;
        }
        //再将用户选定的设为默认
        return $this->where(array('id'=>$id))->setField('is_default',1);
    }

    /**
     * 获取默认地址,如果没有默认就返回当前用户的第一条地址
     */
    public function getAddress($field="*"){
        $row=$this->field($field)->where(array('member_id'=>UID,'is_default'=>1,'status'=>1))->find();
        if($row===null){
            $row=$this->field($field)->where(array('member_id'=>UID,'status'=>1))->order('id')->limit(0,1)->find();
//            echo $this->_sql();
        }
        return $row;
    }

}