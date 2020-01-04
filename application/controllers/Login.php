<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    //登录页面
	public function login_page(){
		parent::view('login_page');
	}

    /* 密码登录 */
    public function login_for_pwd(){
        $postData = $this->_postData;
        if (!isset($postData['mobile']) || !isset($postData['password'])){
            $this->response(array('code'=>101,'msg'=>"参数缺失",'data'=>array()));
        }

        //密码登录方式
        $postData['login_type'] = 1;
        $ret_data = $this->m_users->check_login($postData);
        if ($ret_data['code'] === 0){
            $_SESSION['user_id'] = $ret_data['data']['user_info']['uid'];
        }
        $this->response($ret_data);
    }

	/* 短信登录 */
	public function login_for_sms(){
        $postData = $this->_postData;
        if (!isset($postData['mobile']) || !isset($postData['mobile_code'])){
            $this->response(array('code'=>101,'msg'=>"参数缺失",'data'=>array()));
        }
        //短信登录方式
        $postData['login_type'] = 2;
        $ret_data = $this->M_user->check_login($postData);
        if ($ret_data['code'] === 0){
            $_SESSION['user_id'] = $ret_data['user_info']['uid'];
        }
        $this->response($ret_data);
    }

    /* 发送手机短信 */
    public function send_sms(){

        if(!$this->input->is_ajax_request())
        {
            exit(json_encode(array('code'=>101,'msg'=>"FAIL",'data'=>array())));
        }
        $postData = $this->_postData;
        if (!isset($postData['mobile']) || !isset($postData['sms_type']) || !isset($postData['img_code'])){
            exit(json_encode(array('code'=>101,'msg'=>"参数缺失",'data'=>array())));
        }

        if($postData['sms_type'] == 1 || $postData['sms_type'] == 5){
            if($postData['img_code'] != $_SESSION['code']){
                $this->response(array('code'=>101,'msg'=>"图形验证码不正确",'data'=>array()));
            }
            if ($postData['sms_type'] == 1) {
                if (!isset($postData['parent_mobile']) && $postData['parent_mobile'] == '') {
                    $this->response(array('code' => 101, 'msg' => "请输入推荐手机号", 'data' => array()));
                }
            }
        }else{
            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null) {
                $this->response(array('code'=>101,'msg'=>"账户还未登录",'data'=>array()));
            }
        }

        if($postData['img_code'] != $_SESSION['code']){
            $this->response(array('code'=>101,'msg'=>"图形验证码不正确",'data'=>array()));
        }

        //发送短信
        $ret_data = $this->m_user_mobile_code->send_sms($postData);
        $this->response($ret_data);
    }

    //忘记密码页面
    public function reset_pwd(){
        parent::view('reset_pwd_page.php');
    }

    public function forget_pwd_submit(){
        $postData = $this->_postData;
        if (!isset($postData['mobile']) || !isset($postData['mobile_code']) || !isset($postData['password'])){
            $this->response(array('code'=>101,'msg'=>"参数缺失",'data'=>array()));
        }
        $ret_data = $this->m_users->check_reset_pwd($postData);
        $this->response($ret_data);
    }
}
