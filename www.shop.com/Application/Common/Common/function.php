<?php
/**
 * 获取model中的错误信息
 * @param $model
 * @return string
 */
function get_model_error($model) {
    $errors = $model->getError();
    $errorMsg = '<ul>';
    if (is_array($errors)) {
        foreach ($errors as $error) {
            $errorMsg .= "<li>$error</li>";
        }
    } else {
        $errorMsg .= "<li>$errors</li>";
    }
    $errorMsg .= '</ul>';
    return $errorMsg;
}

/**
 * 返回数组中指定的一列
 */
if (!function_exists('array_column')) {//版本兼容处理
    function array_column($rows, $field) {
        $value = array();
        foreach ($rows as $row) {
            $value[] = $row[$field];
        }
        return $value;
    }
}
/**
 * 下拉菜单的生成
 */
function arr2select($name,$rows,$defaultValue='',$valueField='id',$textField='name') {
    $select_html="<select name='$name'>";
    $select_html.="<option value = '0' > --请选择--</option >";
    foreach($rows as $row){
        $selected='';
        if($row[$valueField]==$defaultValue){
            $selected='selected';
        }
        $select_html.="<option value = '{$row[$valueField]}' {$selected}>{$row[$textField]}</option >";
    }
    $select_html.="</select>";
    return $select_html;
}