<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MY_Controller {

	public function index(){
        $user_info  = $this->_userInfo;
        $getData = $this->_getData;
        $message_type = isset($getData['type']) ? $getData['type'] : 0;
        $map = array_keys(config_item('message_type'));
        if (!in_array($message_type,$map)){
            $message_type = 0;
        }

        //进入页面，设置为无未读消息
        $this->db->where('uid',$user_info['uid'])->update('m_users',['message_type'=>0]);

        $level_sale_map = config_item('mini_sales');

        //当月完成度
        $date = date('Y-m');
        $month = $this->m_users->get_month_sale_amount($user_info['uid'],$date);
        if($user_info['level'] == 0){
            $percent = $month ? '100' : '0';
        }else{
            $percent = round($month/$level_sale_map[$user_info['level']],2)*100;
        }

        //奖金、订单、团队、积分 信息
        if ($message_type < 10) {
            $msg_list = $this->db->select('*')
                ->where('type <',10)
                ->where('uid', $user_info['uid'])
                ->order_by("id", "desc")
                ->limit(30)
                ->get('m_message')
                ->result_array();
        }

        //系统消息
        if ($message_type >= 10 && $user_info['message_type'] < 100){
            $msg_list = $this->db->select('*')
                ->where('type >=',10)
                ->order_by("id", "desc")
                ->limit(30)
                ->get('m_message')
                ->result_array();
        }

        //系统消息
        if ($message_type == 100){
            $msg_list = array();
        }

        $this->_viewData['user_info'] = $user_info;
        $this->_viewData['message_type'] = $message_type;
        $this->_viewData['list'] = $msg_list;
        $this->_viewData['percent'] = $percent;

        parent::view('message');
	}
}
