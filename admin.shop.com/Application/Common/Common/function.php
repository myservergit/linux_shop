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
if(!function_exists('array_column')){//版本兼容处理
    function array_column($rows,$field){
        $value=array();
        foreach($rows as $row){
            $value[]=$row[$field];
        }
        return $value;
    }
}
