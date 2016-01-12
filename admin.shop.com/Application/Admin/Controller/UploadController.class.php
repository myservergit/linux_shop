<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/12
 * Time: 22:33
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Upload;

class UploadController extends Controller {
    public function index() {
        $dir = I('post.dir');
        $config = array(
            'rootPath' => './Uploads/', //保存根路径
            'savePath' => $dir.'/', //保存路径
//            /*'rootPath' => './', //保存根路径
//            'driver' => 'Upyun', // 文件上传驱动
//            'driverConfig' => array(
//                'host'     => 'v0.api.upyun.com', //又拍云服务器
//                'username' => 'itsource', //又拍云用户
//                'password' => 'itsource', //又拍云密码
//                'bucket'   => $dir, //空间名称
//                'timeout'  => 90, //超时时间
//            ), // 上传驱动配置
        );
        $uploader = new Upload($config);
        $rst = $uploader->uploadOne($_FILES['Filedata']);
        if ($rst !== false) {
            echo $rst['savepath'].$rst['savename'];
        }else{
            echo $uploader->getError();
        }
    }
}