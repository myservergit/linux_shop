namespace Admin\Model;


use Think\Model;

class <?php echo $name?>Model extends BaseModel {
    protected $_validate = array(
        <?php foreach($fields as $field):
            if($field['null']=='YES'){
                continue;
            }
            echo "array('{$field['field']}', 'require', '{$field['comment']}不能为空!'),\r\n\t\t";
        endforeach;
        ?>
    );
}