<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 17:52
 */

namespace Admin\Controller;


use Think\Controller;

class GiiController extends Controller {
    public function index() {
        if (IS_POST) {
            $table_name = I('post.table_name');
            //通过表名生成thinkphp中的规范的控制器名称
            $name = parse_name($table_name, 1);
            //通过表名得到表的注解
            $sql = "SELECT table_comment FROM information_schema.`TABLES` WHERE table_schema='" . C('DB_NAME') . "' AND table_name='$table_name'";
            $model = M();
            $rst = $model->query($sql);
            $meta_title = $rst[0]['table_comment'];
            //查询表中的字段信息
            $sql = "SHOW FULL COLUMNS FROM $table_name";
            $fields = $model->query($sql);
            foreach ($fields as $k => &$field) {
                if ($field['field'] == 'id') {
                    unset($fields[$k]);
                } elseif (strpos($field['comment'], '@') !== false) {
                    $pattern = '/(.*)@([a-z]*)\|?(.*)/';
                    preg_match($pattern, $field['comment'], $rst);
                    $field['comment'] = $rst['1'];
                    $field['field_type'] = $rst['2'];
                    if (!empty($rst['3'])) {
                        parse_str($rst['3'], $option_values);
                        $field['option_values'] = $option_values;
                    }
                }
            }
            unset($field);

            //定义代码模板的目录
            defined('TPL_PATH') or define('TPL_PATH', ROOT_PATH . 'Template/');

            //生成控制器
            require TPL_PATH . 'Controller.tpl';
            $controller_content = "<?php\r\n" . ob_get_clean();
            $controller_path = APP_PATH . 'Admin/Controller/' . $name . 'Controller.class.php';
            file_put_contents($controller_path, $controller_content);

            //生成模型
            ob_start();
            require TPL_PATH . 'Model.tpl';
            $model_content = "<?php\r\n" . ob_get_clean();
            $model_path = APP_PATH . 'Admin/Model/' . $name . 'Model.class.php';
            file_put_contents($model_path, $model_content);

            //生成编辑模板
            ob_start();
            require TPL_PATH . 'edit.tpl';
            $edit_content =ob_get_clean();
            $edit_dir=APP_PATH.'Admin/View/'.$name;
            if(is_dir($edit_dir)===false){
                mkdir($edit_dir);
            }
            $edit_path = $edit_dir . '/edit.html';
            file_put_contents($edit_path, $edit_content);

            //生成index页面模板
            ob_start();
            require TPL_PATH . 'index.tpl';
            $index_content =ob_get_clean();
            $index_dir=APP_PATH.'Admin/View/'.$name;
            if(is_dir($index_dir)===false){
                mkdir($index_dir);
            }
            $index_path = $index_dir . '/index.html';
            file_put_contents($index_path, $index_content);

            $this->success('生成成功',U('index'));
        } else {
            $this->assign('meta_title', '代码生成器');
            $this->display('index');
        }
    }
}