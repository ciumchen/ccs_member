<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_manage extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;
        $this->_viewData['user_info'] = $user_info;
        if(config_item('is_new_mall')) {
            $domain = config_item('new_mall_site');

            $mall_param = config_item('mall_param');
            $secret_key = $mall_param['secret_key'];

            $arr['mobile']= $user_info['mobile'];
            $arr['password'] = $user_info['password'];
            $arr['create_time'] = time();
            $string = json_encode($arr);
            $key = $this->crypt->lock_url($string,$secret_key);
            $hash_string = base64_encode($key);

            $sync_link = 'http://'.$domain.'/mobile/users/api_login?t='.$hash_string;
            $this->_viewData['address_url'] = $sync_link."&redirect=http://{$domain}/mobile/useraddress/index.html";
        }else{
            $this->_viewData['address_url'] = '/address';
        }
		parent::view('account_manage');
	}


    public function payPwd(){
        /**
         * 如果pay_password 为空，第一次设置
         */
        $this->_viewData['mobile'] = $this->_userInfo['mobile'];
        $this->_viewData['pay_pwd'] = $this->_userInfo['pay_password'];
        parent::view('set_pay_pwd');

    }

    /**
     * 忘记支付密码页面
     */
    public function setPayPwd(){
        $this->_viewData['mobile'] = $this->_userInfo['mobile'];
        $this->_viewData['pay_pwd'] = '';
        parent::view('set_pay_pwd');
    }

    /**
     * 忘记支付密码页面
     */
    public function setLoginPwd(){
        parent::view('set_login_pwd');
    }

    /**
     * 设置支付密码
     */
    public function set_pay_pwd(){
        $requestData = $this->input->post();
        $ret_data['success'] = false;
        if (!$requestData){
            $this->response(array('code'=>1001,'msg'=>'参数错误','data'=>array()));
        }

        //验证密码
        if (strlen($requestData['password']) < 6 || strlen($requestData['password']) > 20){
            $this->response(array('code'=>1001,'msg'=>'密码长度在6-20位之间','data'=>array()));
        }
        //两次输入的新密码不一致
        if ($requestData['confirm_password'] !== $requestData['password']){
            $this->response(array('code'=>1001,'msg'=>'密码不一致','data'=>array()));
        }

        //验证手机验证码
        if (trim($requestData['mobile_code']) == ''){
            $this->response(array('code'=>1001,'msg'=>'请输入手机验证码','data'=>array()));
        }

        //查询验证码
        $where = array(
            'mobile'=>$this->_userInfo['mobile'],
            'code'=>$requestData['mobile_code'],
            'code_type'=>SET_PAY_PASSWORD
        );
        $code_info = $this->m_user_mobile_code->get_mobile_code($where);
        if ($code_info == null){
            $this->response(array('code'=>1001,'msg'=>'验证码错误','data'=>array()));
        }else{
            if ($code_info['expire_time'] < date('Y-m-d H:i:s')){
                $this->response(array('code'=>1001,'msg'=>'验证码已过期','data'=>array()));
            }
        }

        /*************************************** 验证完毕， 修改密码 *********************************************/
        $this->db->trans_begin();

        $new_pass = sha1($requestData['password'].$this->_userInfo['token']);
        $this->db->where('mobile',$this->_userInfo['mobile'])->update('m_users',array(
            'pay_password'=>$new_pass
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(array('code'=>1001,'msg'=>'网络开小差了,请重试一次','data'=>array()));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>0,'msg'=>'操作成功','data'=>array()));
        }
    }

    /**
     * 修改支付密码
     */
    public function modify_pay_pwd(){
        $requestData = $this->input->post();
        $ret_data['success'] = false;
        if (!$requestData){
            $this->response(array('code'=>1001,'msg'=>'参数错误','data'=>array()));
        }

        if(sha1($requestData['old_password'].$this->_userInfo['token']) != $this->_userInfo['pay_password']){
            $this->response(array('code'=>1001,'msg'=>'原支付密码错误','data'=>array()));
        }

        //验证密码
        if (strlen($requestData['password']) < 6 || strlen($requestData['password']) > 20){
            $this->response(array('code'=>1001,'msg'=>'密码长度在6-20位之间','data'=>array()));
        }
        //两次输入的新密码不一致
        if ($requestData['confirm_password'] !== $requestData['password']){
            $this->response(array('code'=>1001,'msg'=>'密码不一致','data'=>array()));
        }

        /*************************************** 验证完毕， 修改密码 *********************************************/
        $this->db->trans_begin();

        $new_pass = sha1($requestData['password'].$this->_userInfo['token']);
        $this->db->where('mobile',$this->_userInfo['mobile'])->update('m_users',array(
            'pay_password'=>$new_pass
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(array('code'=>1001,'msg'=>'网络开小差了,请重试一次','data'=>array()));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>0,'msg'=>'操作成功','data'=>array()));
        }
    }


    /**
     * 修改登录密码
     */
    public function modify_login_pwd(){
        $requestData = $this->input->post();
        $ret_data['success'] = false;
        if (!$requestData){
            $this->response(array('code'=>1001,'msg'=>'参数错误','data'=>array()));
        }

        if(md5($requestData['old_password']) != $this->_userInfo['password']){
            $this->response(array('code'=>1001,'msg'=>'原登陆密码错误','data'=>array()));
        }

        //验证密码
        if (strlen($requestData['password']) < 6 || strlen($requestData['password']) > 20){
            $this->response(array('code'=>1001,'msg'=>'密码长度在6-20位之间','data'=>array()));
        }
        //两次输入的新密码不一致
        if ($requestData['confirm_password'] !== $requestData['password']){
            $this->response(array('code'=>1001,'msg'=>'密码不一致','data'=>array()));
        }

        /*************************************** 验证完毕， 修改密码 *********************************************/
        $this->db->trans_begin();

        $new_pass = md5($requestData['password']);
        $this->db->where('mobile',$this->_userInfo['mobile'])->update('m_users',array(
            'password'=>$new_pass
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(array('code'=>1001,'msg'=>'网络开小差了,请重试一次','data'=>array()));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>0,'msg'=>'操作成功,下次请使用新密码登录！','data'=>array()));
        }
    }

    public function person_info(){
        $user_info = $this->_userInfo;
        //推荐人信息
        $user_info['parent_info'] = $this->m_users->get_user_info(
            array('uid'=>$user_info['parent_id']),
            'username,mobile,true_name'
        );
        $this->_viewData['user_info'] = $user_info;
        parent::view('person_info');
    }

    public function edit_person_info(){
        $user_info = $this->_userInfo;
        $getData = $this->_getData;

        //允许修改的字段
        $field_map = array('true_name','mobile');
        if (!isset($getData['type']) || !in_array($getData['type'],$field_map)){
            $user_info['parent_info'] = $this->m_users->get_user_info(array('uid'=>$user_info['parent_id']), 'username,mobile,true_name');
            $this->_viewData['user_info'] = $user_info;
            parent::view('person_info');
        }else {
            $this->_viewData['edit_filed'] = $getData['type'];
            $this->_viewData['user_info'] = $user_info;
            parent::view('edit_person_info');
        }
    }

    //提交修改
    public function edit_person_info_submit(){
        $postData = $this->_postData;
        if (!$postData){
            $this->response(array('code'=>102,'msg'=>'参数缺失','data'=>array()));
        }
        $update_attr = array();
        foreach ($postData as $key=>$value){
            if ($key == 'true_name'){
                if (trim($value) == ''){
                    $this->response(array('code'=>1001,'msg'=>'请填写姓名','data'=>array()));
                }
                if (!is_real_name($value)){
                    $this->response(array('code'=>1001,'msg'=>'无效的姓名','data'=>array()));
                }
                $update_attr['true_name'] = $value;
            }
        }
        $this->db->where('uid',$this->_userInfo['uid'])->update('m_users',$update_attr);
        $this->response(array('code'=>0,'msg'=>'修改成功','data'=>array()));

    }

	/* 退出登录 */
	public function logout(){
        if (isset($_SESSION['user_id'])){
            unset($_SESSION['user_id']);
            redirect($this->_domain.'/login/login_page');
        }
    }

    /* 上传头像 */
    public function upload_head_ico(){
        $user_info = $this->_userInfo;
        $postData = $_FILES;
        if (!isset($postData['head_ico_file'])){
            $this->response(array('code'=>1001,'msg'=>'请选择图片','data'=>array()));
        }

        $head_ico_file = $postData['head_ico_file'];

        //获取后缀名，验证类型
        $image_type_map = array('.jpg', '.jpeg', '.gif', '.bmp', '.png');
        $postfix = strrchr($head_ico_file['name'],'.');
        if (!in_array($postfix,$image_type_map)){
            $this->response(array('code' =>1001,'msg'=>'图像格式错误','data'=>array()));
        }

        //验证大小
        if ($head_ico_file['size'] > (5 * 1024 * 1024)){
            $this->response(array('code' =>1001,'msg'=>'图片不能大于5M','data'=>array()));
        }

        //上传图片
        $file_name = time().'_'.$user_info['uid'].'.jpg';

        if(move_uploaded_file($head_ico_file['tmp_name'],'./user_head_image/'.$file_name)){
            $this->db->where('uid',$user_info['uid'])->update('m_users',array(
               'image_url'=>$file_name
            ));
            $this->response(array('code' =>0,'msg'=>'上传成功','data'=>array()));
        }
        $this->response(array('code' =>1001,'msg'=>'操作失败','data'=>array()));
    }
}
