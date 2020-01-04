<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    /*
     * 每日牌价模块
     * */
    class Admin_goldPrice_list extends MY_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->_viewData['title'] = '每日牌价';
        }

        /*
        * 牌价记录表
        * */
        public function index()
        {
            $this->load->library('pagination');
            $searchData = $this->input->get()?$this->input->get():array();
            $searchData = string_escapes($searchData);
            $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
            $searchData['day'] = isset($searchData['day']) ? $searchData['day']:'';
            # var_dump($searchData['day']);die;
            $searchData['gold_price'] = isset($searchData['gold_price']) ? $searchData['gold_price']:'';
            $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
            $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

            $admin_goldPrice_list = $this->m_pager->get_admin_goldPrice_list($searchData);
            # var_dump($goldPrice_list);die;

            add_params_to_url($url,$searchData);
            $config['total_rows'] = $this->m_pager->get_admin_goldPrice_rows($searchData);
            $config['cur_page'] = $searchData['page'];

            $config['per_page'] = 10;
            $config['base_url'] = '/admin/admin_goldPrice_list'.$url;

            $this->pagination->initialize($config);
            # var_dump($config);die;
            $this->_viewData['admin_goldPrice_list'] = $admin_goldPrice_list;
            $this->_viewData['pager'] = $this->pagination->create_links();
            $this->_viewData['searchData'] = $searchData;
            $this->_viewData['total_rows'] = $config['total_rows'];
            parent::view('admin/admin_goldPrice_list');
        }
    }