<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/21
 * Time: 15:09
 */

namespace Home\Controller;


use Org\Util\String;
use Think\Controller;

class MemberController extends Controller {
    /**
     * 注册页面
     */
    public function reg() {
        if (IS_POST) {
            //短信验证码进行验证
            $tel_code = I('post.tel_code');
            $tel = I('post.tel');
            $redis_code = S($tel);
            if ($tel_code != $redis_code) {
                $this->error('短信验证码错误!');
            }

            //短信验证成功后,新用户的创建
            $model = D('Member');
            if ($model->create() !== false) {
                if ($model->add() !== false) {
                    $this->success('注册成功', U('login'));
                    return;
                }
            }
            $this->error(get_model_error($model));
        } else {
            $this->assign('title', '用户注册');
            $this->display('reg');
        }
    }

    /**
     * 登录页面
     */
    public function login() {
        if(IS_POST){
            $model=D('Member');
            if($model->create()!==false){
                $userinfo=$model->login();
                if(is_array($userinfo)){

                    //将用户数据保存到session
                    login($userinfo);
                    //从用户中取出ID作为UID
                    defined('UID') or define('UID',$userinfo['id']);

                    //将cookie中的商品保存到数据库中
                    $shoppingCarModel=D('ShoppingCar');
                    $shoppingCarModel->cookie2db();

                    $url=U('Index/index');
                    if(cookie('__login_return_url__')){
                        $url=cookie('__login_return_url__');
                        cookie('__login_return_url__',null);
                    }
                    $this->success('登陆成功!',$url);
                    return;
                }
            }
            $this->error(get_model_error($model));
        }else{
            $this->assign('title', '登录商城');
            $this->display('login');
        }
    }

    /**
     * 验证新建用户名,email,电话是否存在
     */
    public function check() {
        $param = I('get.');
        $model = D('Member');
        $rst = $model->check($param);
        $this->ajaxReturn($rst);
    }

    /**
     * 获取短信的验证码
     * @param $tel
     */
    public function getSMSCode($tel) {
        //获取随机码
        $str = String::randNumber(1000, 9999);
        //将验证码保存到redis中,保存5分钟
        S($tel, $str, 300);
        //发送短信
        sendSMS('注册验证', '{"code":"' . $str . '","product":"京西商城"}', $tel, 'SMS_4830036');
    }

    /**
     * 对验证码进行验证
     * @param $tel_code
     * @param $tel
     */
    public function checkTel($tel_code, $tel) {
        //根据电话号码取出redis中的验证码
        $redis_code = S($tel);
        //对验证码进行验证
        $rst = $tel_code == $redis_code;

        $this->ajaxReturn($rst);
    }

    /**
     * 用户的邮箱激活
     * @param $id
     * @param $key
     */
    public function fire($id,$key){
        $memberModel = D('Member');
        $result = $memberModel->fire($id,$key);
        if($result===false){
            $this->error('激活失败!');
        }else{
            $this->success('激活成功!',U('login'));
        }
    }

    /**
     * 用户的注销
     */
    public function logout(){
        session('USERINFO',null);
        $this->success('注销成功!',U('Index/index'));
    }
}