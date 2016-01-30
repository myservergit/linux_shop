<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/21
 * Time: 20:52
 */

namespace Home\Model;


use Think\Model;

class MemberModel extends Model {
    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('salt','\Org\Util\String::randString','','function'),
        array('reg_time',NOW_TIME),
        array('reg_ip','get_client_ip_bigint','','callback'),
    );

    /**
     * 获取注册用户的ip并转化成int类型
     * @return int
     */
    public function get_client_ip_bigint(){
        $ip = get_client_ip();
        return ip2long($ip);
    }
    /**
     * 注册用户的添加
     */
    public function add() {
        //获取邮箱
        $email=$this->data['email'];

        //密码加密
        $this->data['password']=md5(md5($this->data['password']).$this->data['salt']);

        $id=parent::add();

        $key=md5($email);
        $mail_content="<a href='http://www.shop.com/index.php/Home/Member/fire/id/{$id}/key/$key' target='_blank'>点击我激活账号</a>";
        //发送邮件
        $rst=sendMail($email,'京西商城的激活邮件',$mail_content);
        if($rst===false){
            $this->error = '发送邮件失败!';
            return false;
        }

        return $id;
    }

    /**
     * 验证新建用户名,email,电话是否存在
     * @param string $param
     * @return bool
     */
    public function check($param) {
        $keys = array_keys($param);
        $key = $keys[0];
        $count = $this->where(array($key => $param[$key], 'status' => 1))->count();
        return $count == 0;
    }

    /**
     * 用户的邮箱激活
     * @param $id
     * @param $key
     * @return bool
     */
    public function fire($id,$key){
        $email = $this->getFieldById($id,'email');
        if(md5($email)==$key){
            return $this->where(array('id'=>$id))->setField('status',1);
        }else{
            $this->error = '激活失败!';
            return false;
        }
    }
    /**
     * 用户登录验证
     */
    public function login(){
        $username = $this->data['username'];
        $password = $this->data['password'];

        //判断用户是否存在
        $row = $this->field('id,username,password,salt,status')->where(array('status'=>array('gt',-1)))->getByUsername($username);
        if(empty($row)){
            $this->error = '该用户不存在!';
            return false;
        }
        if($row['status']==='0'){
            $this->error = '该用户未激活或者被锁定';
            return false;
        }

        //用户存在就验证密码
        if($row['password']==md5(md5($password).$row['salt'])){
            //密码对比上之后才登陆成功

            //将登陆成功后的IP和时间更新到数据库表中
            parent::save(array('last_login_time'=>NOW_TIME,'last_login_ip'=>ip2long(get_client_ip()),'id'=>$row['id']));
            return $row;
        }else{
            $this->error = '密码不正确!';
            return false;
        }
    }
}