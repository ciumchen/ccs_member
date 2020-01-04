<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_account_manage extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->_viewData['title'] = '管理员账户管理';
    }

    /* 管理员账户列表 */
    public function admin_account_list(){
        $this->load->model('m_pager');
        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $admin_user_list = $this->m_pager->get_admin_user_list($searchData);

        foreach ($admin_user_list as $k=>$admin){

        }

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_admin_user_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/admin_account_manage/admin_account_list'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['admin_user_list'] = $admin_user_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        parent::view('admin/admin_account_list');
    }

    /* 编辑管理员信息 */
    public function edit_account_info(){
        $this->_viewData['title'] = '编辑管理员信息';
        $getData = $this->input->get();
        if (!$getData['admin_name']){
            echo "缺失管理员账号";
            exit();
        }
        $admin_info = $this->db->select('*')->where('admin_name',$getData['admin_name'])->get('m_admin_user')->row_array();
        if ($admin_info == null){
            echo "账户不存在";
            exit();
        }
        $this->_viewData['admin_info'] = $admin_info;
        parent::view('admin/edit_account_info');
    }

    /* 编辑管理员信息提交 */
    public function edit_account_info_submit(){
        $postData = $this->input->post();
        if (!$postData){
            die(json_encode(array('success'=>true, 'msg'=>'参数缺失', 'code'=>1010)));
        }
        //转义
        $postData = string_escapes($postData);

        //账号角色
        if ($postData['role'] == '' || !is_numeric($postData['role'])){
            $this->response(array('code'=>1011,'msg'=>'请选择账号角色','data'=>array()));
        }
        //账号角色
        if ($postData['status'] == '' || !is_numeric($postData['status'])){
            $this->response(array('code'=>1011,'msg'=>'请选择账号状态','data'=>array()));
        }

        //更新项
        $update_attr = array('role'=>$postData['role'],'status'=>$postData['status']);
        $update_ret = $this->db->where('id',$postData['id'])->update('m_admin_user',$update_attr);

        if ($update_ret) {
            // 管理员日志记录
            $this->db->insert('m_admin_action_log',array(
                'module_name'=>'管理员账号管理',
                'function_name'=>'编辑',
                'opera_obj_id'=>$postData['admin_user'],
                'action_text'=>var_export($update_attr,true),
                'admin_name'=>$_SESSION['admin_name']
            ));
            $this->response(array('code'=>100,'msg'=>'信息修改成功','data'=>array()));
        }
    }



    /* 新增管理员账号 */
    public function add_admin_account_submit(){

        $postData = $this->input->post();
        if (!$postData){
            $this->response(array('code'=>1010,'msg'=>'参数缺失','data'=>array()));
        }

        //登录账号
        if ($postData['admin_name'] == '' || strlen($postData['admin_name']) < 4){
            $this->response(array('code'=>1011,'msg'=>'用户名不能少于四位','data'=>array()));
        }
        $ret = $this->db->select('id')->where('admin_name',$postData['admin_name'])->get('m_admin_user')->row_array();
        if ($ret != null){
            $this->response(array('code'=>1011,'msg'=>'该账号已经存在','data'=>array()));
        }

        //登录密码
        if ($postData['admin_password'] == '' || strlen($postData['admin_password']) < 6){
            $this->response(array('code'=>1012,'msg'=>'密码不能少于6位','data'=>array()));
        }

        //真实姓名
        if ($postData['realname'] == ''){
            $this->response(array('code'=>1013,'msg'=>'请填写真实姓名','data'=>array()));
        }

        //账号角色
        if (!in_array($postData['role'],array_keys(config_item('admin_role')))){
            $this->response(array('code'=>1014,'msg'=>'请选择账号角色','data'=>array()));
        }

        //创建唯一token、密码加密
        $token = create_token();
        $password = md5($token.$postData['admin_password'].'!@#');

        //新增数据
        $insert_ret = $this->db->insert('m_admin_user',array(
            'admin_name'=>$postData['admin_name'],
            'token'=>$token,
            'admin_password'=>$password,
            'realname'=>$postData['realname'],
            'role'=>$postData['role'],
        ));

        if ($insert_ret) {

            //管理员日志记录
            $this->db->insert('m_admin_action_log',array(
                'module_name'=>'管理员账号管理',
                'function_name'=>'添加管理员账号',
                'opera_obj_id'=>$postData['admin_name'],
                'action_text'=>"新增{$postData['admin_name']}管理员",
                'admin_name'=> $_SESSION['admin_name']
            ));
            $this->response(array('code'=>100,'msg'=>'新增账号成功','data'=>array()));
        }
    }
}
