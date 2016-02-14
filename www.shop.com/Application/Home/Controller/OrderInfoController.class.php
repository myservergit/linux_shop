<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/30
 * Time: 14:34
 */

namespace Home\Controller;


use Think\Controller;

class OrderInfoController extends Controller {
    public function _initialize() {
        if (!isLogin()) {
            cookie('__login_return_url__', $_SERVER['REQUEST_URI']);
            $this->error('请先登录', U('Member/login'));
        }
    }

    public function add() {
        if(IS_POST){
            $orderInfoModel=D('OrderInfo');
            $rst=$orderInfoModel->add(I('post.'));
            if($rst!==false){
                $this->success('下单成功!',U('orderSuccess',array('id'=>$rst)));
            }else{
                $this->error('下单失败!'.show_model_error($orderInfoModel));
            }

        }else{
            //送货地址
            $addressModel=D('Address');
            $addresses=$addressModel->getList();
            $this->assign('addresses', $addresses);
            $address_default_id=$addressModel->getAddress('id');
            $this->assign('address_default_id',$address_default_id);
            //用户的默认地址
            $default_address=$addressModel->getAddress();
            $this->assign('default_address', $default_address);

            //查询省份
            $areaModel = D('Area');
            $provinces = $areaModel->getChildByParentId();
            $this->assign('provinces', $provinces);

            //送货方式
            $deliveryModel=D('Delivery');
            $deliverys=$deliveryModel->getList();
            $this->assign('deliverys', $deliverys);

            //支付方式
            $payTypeModel=D('PayType');
            $payTypes=$payTypeModel->getList();
            $this->assign('payTypes', $payTypes);

            //购物车商品信息
            $shoppingCarModel = D('ShoppingCar');
            $rows = $shoppingCarModel->getList();
            $this->assign('rows', $rows);

            $this->assign('meta_title', '填写核对订单信息');
            $this->display('add');
        }
    }

    /**
     * 下单成功后的操作
     * @param $id
     */
    public function orderSuccess($id){
        $orderInfoModel=D('OrderInfo');
        $sn=$orderInfoModel->getFieldById($id,'sn');
        $this->assign('sn',$sn);
        $this->display('success');
    }

    /**
     * 去支付宝支付,但是还没有支付
     * @param $sn
     */
    public function doPay($sn){
        $orderInfoModel=D('OrderInfo');
        $orderInfoModel->doPay($sn);
    }
    /**
     * 页面跳转同步通知页面路径(支付成功之后跳转网站的地址)
     */
    public function return_url(){
        $this->show('展示列表...!');
    }

    /**
     * 服务器异步通知页面路径
     */
    public function notify_url(){
        $alipay_config = C('ALIPAY_CONFIG');
        vendor('Alipay.lib.alipay_notify#class');

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的price、quantity、seller_id与通知时获取的price、quantity、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的price、quantity、seller_id与通知时获取的price、quantity、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序
                $orderInfoModel = D('OrderInfo');
                $orderInfoModel->where(array('sn'=>$out_trade_no))->save(array('order_status'=>1,'pay_status'=>2));

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
                //该判断表示卖家已经发了货，但买家还没有做确认收货的操作

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的price、quantity、seller_id与通知时获取的price、quantity、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //该判断表示买家已经确认收货，这笔交易完成

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的price、quantity、seller_id与通知时获取的price、quantity、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else {
                //其他状态判断
                echo "success";

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
}