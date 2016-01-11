<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 17:52
 */

namespace Admin\Controller;


use Think\Controller;

class GiiController extends Controller{
    public function index(){
        if(IS_POST){
            $table_name=I('post.table_name');
            //通过表名生成thinkphp中的规范的控制器名称
            $name=parse_name($table_name,1);
            //通过表名得到表的注解
            $sql="SELECT table_comment FROM information_schema.`TABLES` WHERE table_schema='".C('DB_NAME')."' AND table_name='$table_name'";
            $model=M();
            $rst=$model->query($sql);
            $meta_title=$rst[0]['table_comment'];
            //查询表中的字段信息
            $sql="SHOW FULL COLUMNS FROM $table_name";
            $fields=$model->query($sql);

            //定义代码模板的目录
            defined('TPL_PATH') or define('TPL_PATH',ROOT_PATH.'Template/');

            //生成控制器
            require TPL_PATH.'Controller.tpl';
            $controller_content="<?php\r\n".ob_get_clean();
            $controller_path=APP_PATH.'Admin/Controller/'.$name.'Controller.class.php';
            file_put_contents($controller_path,$controller_content);

            //生成模型
            ob_start();
            require TPL_PATH.'Model.tpl';
            $model_content="<?php\r\n".ob_get_clean();
            $model_path=APP_PATH.'Admin/Model/'.$name.'Model.class.php';
            file_put_contents($model_path,$model_content);
        }else{
            $this->assign('meta_title','代码生成器');
            $this->display('index');
        }
    }
}