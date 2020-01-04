<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    //用户信息
	protected $_userInfo;

    protected $_domainAdmin = '';

    //域名
    protected $_domain = '';

    //控制器所在目录
    protected $_dirName = '';

    //控制器名
    protected $_controllerName;

    //方法名
    protected $_methods;

    //数据容器
    protected $_viewData = array();

    //是否已经登录
    protected $_isLogin = false;

    //post数据
    protected $_postData = array();

    //get数据
    protected $_getData = array();

    //头像路径
    protected $_head_ico_dir = '';

	public function __construct() {
		parent::__construct();
        /**
         * 执行定时器时不走这流程，会报错
         */
		if(!$this->input->is_cli_request()) {
            self::init();
            self::checkLogin();
            self::checkAdminLogin();
        }
	}

    protected function init(){

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = null;
        }

        //协议头
        $http_head = 'http://';
        if (isset($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME'] == 'https'){
            $http_head = 'https://';
        }
        $this->_postData = $this->input->post();
        $this->_getData = $this->input->get();
        $this->_domain = $http_head.$_SERVER['HTTP_HOST'];
        $this->_domainAdmin = $this->_domain.'/admin/users_manage/index';
        $this->_dirName = ($this->router->directory== null) ? '/' :$this->router->directory;
        $this->_controllerName = strtolower(get_class($this));
        $this->_methods = isset($this->uri->rsegments[2]) ? $this->uri->rsegments[2] : '';
        $this->_head_ico_dir = $this->_domain.'/user_head_image/';

        $this->_viewData['domain_admin'] = $this->_domainAdmin;
        $this->_viewData['domain'] = $this->_domain;
        $this->_viewData['dirName'] = $this->_dirName;
        $this->_viewData['controllerName'] = $this->_controllerName;
        $this->_viewData['methods'] = $this->_methods;

    }


	/* 检查是否已经登录, 如已登录获取用户信息 */
	public function checkLogin(){

        if ($this->_dirName == '/' && $this->_controllerName == 'cron'){
            return;
        }

        //登录后进入登录页或注册页
        if ($this->_controllerName == 'login' || $this->_controllerName == 'register') {
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null && $this->_methods != 'send_sms') {
                redirect($this->_domain);
            }
        } else{
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null) {
                $this->_isLogin = true;
                $user_info = $this->m_users->get_user_info(array('uid'=>$_SESSION['user_id']));
                if (!strstr($user_info['image_url'],'http')){
                    $user_info['image_url'] = $this->_head_ico_dir.$user_info['image_url'];
                }
                $this->_userInfo = $user_info;
            } else {
                if ($this->_dirName != 'admin/') {
                    redirect($this->_domain . '/login/login_page');
                }
            }
        }
	}

    /* 检查管理员登录 */
    protected function checkAdminLogin(){
        if ($this->_dirName == 'admin/'){
            //已经登录
            if (isset($_SESSION['admin_name']) && $_SESSION['admin_name'] != null){

                $adminInfo = $this->db->select('admin_name,role,status')
                    ->where('admin_name',$_SESSION['admin_name'])
                    ->get('m_admin_user')->row_array();

                $this->_viewData['adminInfo'] = $adminInfo;
                //登录后再到登录页面，重定向到后台主页
                if ($this->_controllerName == 'index'){
                    if ($this->_methods == 'admin_login'){
                        redirect($this->_domainAdmin);
                    }
                }
            }else{
                if ($this->_controllerName != 'index'){
                    if ($this->_methods != 'admin_login'){
                        redirect($this->_domain.'/admin/index/admin_login');
                    }
                }
            }
        }
    }


    /**
     * 重写view
     * @param string $page  显示的页面
     * @param string $header    页头
     * @param string $footer    页尾
     */
    protected function view($page ='',$header='',$footer='') {
        $directory = $this->_dirName;
        $page = $page == '' ? $directory.$this->_controllerName : $page;
        $header = $header ? $header : $this->_dirName.'header';
        $footer = $footer ? $footer : $this->_dirName.'footer';

        $this->load->view($header, $this->_viewData);
        $this->load->view($page, $this->_viewData);
        $this->load->view($footer, $this->_viewData);
    }

    /* 接口输出数据 */
    protected function response($data){
        header('Content-type: application/json');
        exit(json_encode($data));
    }
}

