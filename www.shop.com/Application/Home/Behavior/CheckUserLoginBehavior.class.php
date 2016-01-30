<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/22
 * Time: 9:56
 */

namespace Home\Behavior;


use Think\Behavior;

/**
 * 1.检查用户登录的情况下, 将用户ID放在UID中,   那么在其他的控制器中, 如果用户登录就可以直接通过UID访问用户ID
 * Class CheckUserLoginBehavior
 * @package Home\Behavior
 */
class CheckUserLoginBehavior extends Behavior
{

    public function run(&$params){
        if(isLogin()){
             $userinfo = login();
             defined('UID') or define('UID',$userinfo['id']);
        }
    }

}