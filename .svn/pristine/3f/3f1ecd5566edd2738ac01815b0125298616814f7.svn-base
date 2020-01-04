<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report_manage extends MY_Controller {

    function __construct()
    {
        parent::__construct();
    }

    //充值管理
    public function recharge_list(){
        $this->_viewData['title'] = '充值记录';
        $this->load->model('o_pager');
        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:'';
        $searchData['order_id'] = isset($searchData['order_id'])?$searchData['order_id']:'';
        $searchData['card_no'] = isset($searchData['card_no'])?$searchData['card_no']:'';
        $searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

        //去空格,转义
        $searchData = string_escapes($searchData);

        $recharge_list = $this->o_pager->get_recharge_list($searchData);

        //提现到余额的记录
        if ($searchData['type'] == 4 && $searchData['status'] != '0'){
            $this->db->select('user_id,order_id,status,amount,create_time')->from('withdraw');
            $this->db->where('withdraw_to',1);
            $this->db->where('status',1);
            if ($searchData['order_id'] != ''){
                $this->db->where('order_id',$searchData['order_id']);
            }
            if ($searchData['user_id'] != ''){
                $this->db->where('user_id',$searchData['user_id']);
            }
            if ($searchData['start'] != '') {
                $this->db->where('create_time >=', ($searchData['start']));
            }
            if ($searchData['end'] != '') {
                $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($searchData['end'])+86400-1));
            }

            $recharge_list = $this->db->order_by("create_time", "desc")->limit(10, ($searchData['page'] - 1) * 10)->get()->result_array();
            foreach ($recharge_list as $k=>$recharge){
                $recharge_list[$k]['user_id'] = $recharge['user_id'];
                $recharge_list[$k]['type'] = $searchData['type'];
                $recharge_list[$k]['card_no'] = '';
                $recharge_list[$k]['real_pay_amount'] = $recharge['amount'];
                $recharge_list[$k]['remark'] = "";
            }
        }

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->o_pager->get_recharge_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/report_manage/recharge_list'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['recharge_list'] = $recharge_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];

        parent::view('admin/recharge_list');
    }


    //余额变动记录
    public function amount_change_list(){
        $this->_viewData['title'] = "余额变动记录";

        $this->load->model('o_pager');
        $this->load->model('o_user');
        $this->load->library('pagination');


        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:'';
        $searchData['order_id'] = isset($searchData['order_id'])?$searchData['order_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
        $searchData['payment_type'] = isset($searchData['payment_type'])?$searchData['payment_type']:'';

        $amount_change_report = $this->o_pager->get_amount_change_report($searchData);
        foreach ($amount_change_report as $k=>$report){
            $amount_change_report[$k]['amount'] = numberFormat($report['amount']);
        }

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->o_pager->get_amount_change_row($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/report_manage/amount_change_list'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['amount_change_report'] = $amount_change_report;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];

        parent::view('admin/amount_change_list');
    }
}
