<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct()
    {
        parent::__construct();
    }

    //管理员登录页面
    public function admin_login(){
        $this->load->view('/admin/admin_login');
    }

    //登录提交
    public function admin_login_submit(){
        $post_data = $this->input->post();
        if (!$post_data) {
            die(json_encode(array('code'=>106,'msg'=>"参数缺失",'success'=>false)));
        }

        if ($post_data['admin_login_name'] == '') {
            die(json_encode(array('code'=>1001,'msg'=>"请输入账号",'success'=>false)));
        }

        if ($post_data['admin_login_password'] == '') {
            die(json_encode(array('code'=>1002,'msg'=>"请输入密码",'success'=>false)));
        }

        $post_data = string_escapes($post_data);
        $admin_info = $this->db->select('*')->where('admin_name',$post_data['admin_login_name'])->get('m_admin_user')->row_array();
        if (!$admin_info) {
            die(json_encode(array('code'=>1011,'msg'=>"该账户不存在",'success'=>false)));
        }

        //验证密码
        $password = md5($admin_info['token'].$post_data['admin_login_password'].'!@#');
        if ($admin_info['admin_password'] != $password) {
            die(json_encode(array('code'=>1012,'msg'=>"密码错误,请重试",'success'=>false)));
        }

        //管理员日志记录
        $realIp = getRealIp();
        $this->db->insert('m_admin_action_log',array(
            'module_name'=>'管理员登录',
            'function_name'=>'登录',
            'opera_obj_id'=>$post_data['admin_login_name'],
            'action_text'=>"{$post_data['admin_login_name']} 登录IP为{$realIp}",
            'admin_name'=>$post_data['admin_login_name']
        ));

        $_SESSION['admin_name'] = $post_data['admin_login_name'];
        die(json_encode(array('code'=>100,'msg'=>"验证通过",'success'=>true)));
    }


    /*  管理员退出登录 */
    public function admin_logout(){
        unset($_SESSION['admin_name']);
        redirect($this->_domain.'/admin/index/admin_login');
    }

}
