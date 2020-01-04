<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_action_log extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->_viewData['title'] = '操作日志管理';
    }

    //用户管理
    public function index(){

        //模块名
        $module_name_map = array();
        $res = $this->db->select('module_name')->group_by('module_name')->get('m_admin_action_log')->result_array();
        foreach ($res as $value){
            $module_name_map[] = $value['module_name'];
        }

        $this->load->model('m_pager');
        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['opera_obj_id'] = isset($searchData['opera_obj_id'])?$searchData['opera_obj_id']:'';
        $searchData['module_name'] = isset($searchData['module_name'])?$searchData['module_name']:'';
        $searchData['function_name'] = isset($searchData['function_name'])?$searchData['function_name']:'';

        //方法名
        $sql = "select function_name from m_admin_action_log where module_name = '{$searchData['module_name']}' group by function_name";
        $function_name_map = $this->db->query($sql)->result_array();

        //获取列表
        $admin_action_log = $this->m_pager->get_admin_action_log($searchData);

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_admin_action_log_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/admin_action_log'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['admin_action_log'] = $admin_action_log;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        $this->_viewData['module_name_map'] = $module_name_map;
        $this->_viewData['function_name_map'] = $function_name_map;
        $this->_viewData['admin_name_map'] = $this->db->select('admin_name')->get('m_admin_user')->result_array();

        parent::view('admin/admin_action_log');
    }

    /* 获取模块的所有功能点 */
    public function get_function_name(){
        if (!isset($_POST['module_name']))
        {
            exit(json_encode(array('msg'=>'参数缺失')));
        }
        $module_name = string_escapes($_POST['module_name']);

        $function_name_map = $this->db->query("select function_name from m_admin_action_log where module_name = '{$module_name}'  group by function_name")->result_array();

        exit(json_encode(array('msg'=>'','function_name_map'=>$function_name_map)));
    }

}
