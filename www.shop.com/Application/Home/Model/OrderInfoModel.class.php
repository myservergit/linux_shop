<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/13
 * Time: 16:11
 */

namespace Home\Model;


use Think\Model;

class OrderInfoModel extends Model{
    /**
     * 添加订单
     * @param mixed|string $requestData
     * @return bool|mixed
     */
    public function add($requestData){
        $this->startTrans();
        //为order_info表准备数据
        $orderInfo = array();
        $orderInfo['member_id']=UID;

        //>>收货人信息
        $address_id=$requestData['address_id'];
        $addressModel=D('Address');
        $address=$addressModel->field('id,member_id,is_default,status',true)->find($address_id);
        $orderInfo=array_merge($orderInfo,$address);

        //>>送货方式
        $delivery_id=$requestData['delivery'];
        $deliveryModel=D('Delivery');
        $delivery=$deliveryModel->field('id as delivery_id,name as delivery_name,price as delivery_price')->find($delivery_id);
        $orderInfo=array_merge($orderInfo,$delivery);
        $orderInfo['delivery_time']=$requestData['delivery_time'];

        //>>支付方式
        $pay_type_id=$requestData['pay'];
        $payTypeModel=D('PayType');
        $pay_type=$payTypeModel->field('id as pay_type_id,name as pay_type_name')->find($pay_type_id);
        $orderInfo = array_merge($orderInfo,$pay_type);

        //>>下单时间
        $orderInfo['inputtime']=NOW_TIME;

        //购物车中信息
        $shoppingCarModel=D('ShoppingCar');
        $shopping_car=$shoppingCarModel->getList();
        //>>计算购物明细金额及订单总金额
        $goods_total_price=0;//购物明细金额
        foreach($shopping_car as $item){
            $goods_total_price+=($item['num']*$item['price']);
        }
        $orderInfo['total_price']=$goods_total_price+$delivery['delivery_price'];

        //保存订单信息到数据库中
        $id=parent::add($orderInfo);
        if($id===false){
            $this->rollback();
            return false;
        }

        //将购物车中的信息保存到购物明细表中
        foreach($shopping_car as &$item){
            unset($item['id']);
            unset($item['member_id']);
            $item['order_info_id'] = $id;
        }
        unset($item);
        $orderInfoItemModel=M('OrderInfoItem');
        $rst=$orderInfoItemModel->addAll($shopping_car);
        if($rst===false){
            $this->rollback();
            $this->error='保存订单明细失败';
            return false;
        }

        //发票信息
        $invoiceModel=M('Invoice');
        $invoice=array();//发票数据

        //>>发票名字
        if($requestData['type']==1){
            $userinfo=login();
            $invoice_name=$userinfo['username'];
        }else{
            $invoice_name=$requestData['invoice_name'];
        }

        //>>发票相关信息
        $invoice_content='';
        if($requestData['content']=='明细'){
            foreach($shopping_car as $item){
                $invoice_content.=($item['name'].' '.$item['num'].' '.$item['price'].' '.$item['num']*$item['price']."\r\n");
            }
        }else{
            $invoice_content=$requestData['content'];
        }

        $invoice['name']=$invoice_name;
        $invoice['content']=$invoice_content;
        $invoice['order_info_id']=$id;
        $invoice['total_price']=$goods_total_price;

        $invoice_id=$invoiceModel->add($invoice);
        if($invoice_id===false){
            $this->rollback();
            $this->error='保存发票失败';
            return false;
        }

        //将订单号和发票id更新到订单表中
        $sn=date('Ymd').str_pad($id,11,'0',STR_PAD_LEFT);
        $rst=parent::save(array('sn'=>$sn,'invoice_id'=>$invoice_id,'id'=>$id));
        if($rst===false){
            $this->rollback();
            $this->error='更新订单号和发票ID失败';
            return false;
        }

        $this->commit();
        return $id;
    }
    public function doPay($sn){
        //订单信息
        $orderInfo=$this->where(array('sn'=>$sn))->find();

        $alipay_config = C('ALIPAY_CONFIG');
        vendor("Alipay.lib.alipay_submit#class");

        /**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "http://www.shop.com/index.php/OrderInfo/notify_url";
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = "http://www.shop.com/index.php/OrderInfo/return_url";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //商户订单号
        $out_trade_no = $sn;
        //商户网站订单系统中唯一订单号，必填
        //订单名称
        $subject = '京西商城订单';
        //必填
        //付款金额
        $price = $orderInfo['total_price'];
        //必填
        //商品数量
        $quantity = "1";
        //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
        //物流费用
        $logistics_fee = '0';
        //必填，即运费
        //物流类型
        $delivery_type=array(1,2,3);
        if(in_array($orderInfo['delivery_id'],$delivery_type)){
            $logistics_type = "EXPRESS";
        }else{
            $logistics_type = "POST";
        }
        //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        //物流支付方式
        $logistics_payment = "SELLER_PAY";
        //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
        //订单描述
        $body = $_POST['WIDbody'];
        //商品展示地址
        $show_url = "http://www.shop.com/index.php/OrderInfo/show/id/".$orderInfo['id'];
        //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html
        //收货人姓名
        $receive_name = $orderInfo['name'];
        //如：张三
        //收货人地址
        $receive_address = $orderInfo['province_name'].$orderInfo['city_name'].$orderInfo['area_name'].$orderInfo['detail_address'];
        //如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
        //收货人邮编
        $receive_zip = $_POST['WIDreceive_zip'];
        //如：123456
        //收货人电话号码
        $receive_phone = $orderInfo['tel'];
        //如：0571-88158090
        //收货人手机号码
        $receive_mobile = $_POST['WIDreceive_mobile'];
        //如：13312341234


        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_partner_trade_by_buyer",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> $payment_type,
            "notify_url"	=> $notify_url,
            "return_url"	=> $return_url,
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "price"	=> $price,
            "quantity"	=> $quantity,
            "logistics_fee"	=> $logistics_fee,
            "logistics_type"	=> $logistics_type,
            "logistics_payment"	=> $logistics_payment,
            "body"	=> $body,
            "show_url"	=> $show_url,
            "receive_name"	=> $receive_name,
            "receive_address"	=> $receive_address,
            "receive_zip"	=> $receive_zip,
            "receive_phone"	=> $receive_phone,
            "receive_mobile"	=> $receive_mobile,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        header('Content-Type: text/html;charset=utf-8');

        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }
}