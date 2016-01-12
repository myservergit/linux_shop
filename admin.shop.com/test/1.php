<?php
header('Content_Type: text/html;charset=utf-8');
//$str='供应商';
//$str='简介@textarea';
$str='是否显示@radio|1=是&0=否';
$pattern='/(.*)@([a-z]*)\|?(.*)/';
if(strpos($str,'@')===false){
    $rst=$str;
}else{
    preg_match($pattern,$str,$rst);
}
echo '<pre>';
var_dump($rst);
$str1=$rst['2'];
var_dump($rst1);
