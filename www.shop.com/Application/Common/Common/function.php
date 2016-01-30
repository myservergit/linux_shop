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
function arr2select($name, $rows, $defaultValue = '', $valueField = 'id', $textField = 'name') {
    $select_html = "<select name='$name'>";
    $select_html .= "<option value = '0' > --请选择--</option >";
    foreach ($rows as $row) {
        $selected = '';
        if ($row[$valueField] == $defaultValue) {
            $selected = 'selected';
        }
        $select_html .= "<option value = '{$row[$valueField]}' {$selected}>{$row[$textField]}</option >";
    }
    $select_html .= "</select>";
    return $select_html;
}

/**
 * 存放用户登录信息. 如果没有传入用户信息, 表示从session取出用户信息
 * @param null $userinfo
 * @return mixed
 */
function login($userinfo = null) {
    if ($userinfo) {
        session('USERINFO', $userinfo);
    } else {
        return session('USERINFO');
    }
}

/**
 * 判断用户是否登录
 * @return bool
 */
function isLogin() {
    return login() !== null;
}

/**
 * 发送短信
 * @param $smsFreeSignName  签名
 * @param $smsParam      短信模板中的参数 : 例如：'{"code":"我爱你","product":"京西商城"}';
 * @param $recNum 　　　　　接收号码
 * @param $smsTemplateCode 　　　短信模板的编号
 */
function sendSMS($smsFreeSignName, $smsParam, $recNum, $smsTemplateCode) {
    vendor('Alidayu.TopSdk');;
    date_default_timezone_set('Asia/Shanghai');

    $c = new TopClient;

    //从配置文件中得到短信应用的配置
    $sms_config = C('SMS_CONFIG');

    //设置应用的APP_KEY
    $c->appkey = $sms_config['appkey'];
    //安全码
    $c->secretKey = $sms_config['secretKey'];

    //发送短信的对象
    $req = new AlibabaAliqinFcSmsNumSendRequest;
//    $req->setExtend("123456");
    //短信类型
    $req->setSmsType("normal");
    //短信签名
    $req->setSmsFreeSignName($smsFreeSignName);
    //为短信模板中的变量赋值
    $req->setSmsParam($smsParam);
    //接收短信的手机号
    $req->setRecNum($recNum);
    //短信模板的编号
    $req->setSmsTemplateCode($smsTemplateCode);  //   验证码${code}，您正在注册成为${product}用户，感谢您的支持！

    //发送短信
    $resp = $c->execute($req);
}

/**
 * 发送邮件.
 * @param $receiver  收件人
 * @param $title     邮件标题
 * @param $content   邮件内容
 */
function sendMail($receiver, $title, $content) {
    //通过126的账号登陆到126的邮箱服务器上
    vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer;

    //得到邮件的配置
    $mail_config = C('MAIL_CONFIG');

    //>>1.登陆邮件服务器
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $mail_config['Host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $mail_config['Username'];                 // SMTP username
    $mail->Password = $mail_config['Password'];                           // SMTP password
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 587;                                    // TCP port to connect to

    $mail->CharSet = 'utf-8';//设置编码

    //写邮件内容
    $mail->setFrom($mail_config['From'], 'noReplay'); //指定发件人

    $mail->addAddress($receiver);     // 收件人的名字
    //$mail->addReplyTo('info@example.com', 'Information'); // 收件人
//        $mail->addCC('ggz@itsource.cn','xxx');  //抄送
//        $mail->addBCC('ggz@itsource.cn','yyyy');   //密送

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // 指定是否为html的邮件

    //指定邮件的标题
    $mail->Subject = $title;
    //指定邮件的内容
    $mail->Body = $content;
    //指定邮件的内容(当html无效时显示该内容)
    $mail->AltBody = $content;

    //发送邮件
    return $mail->send();
}