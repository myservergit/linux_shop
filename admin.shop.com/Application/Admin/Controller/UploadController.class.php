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
        $config = C('UPLOAD_CONFIG');
        $config['driverConfig']['bucket']=$dir;
        $uploader = new Upload($config);
        $rst = $uploader->uploadOne($_FILES['Filedata']);
        if ($rst !== false) {
            echo $rst['savepath'].$rst['savename'];
        }else{
            echo $uploader->getError();
        }
    }
}