<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/17
 * Time: 17:40
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsPhotoController extends Controller{
    public function remove($id){
        $model=M('GoodsPhoto');
        $rst=$model->delete($id);
        if($rst!==false){
            $this->success('删除图片成功');
        }else{
            $this->error('删除图片失败');
        }
    }
}