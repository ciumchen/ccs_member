<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;

        $rows = $this->m_base->S('m_user_help_plan',['uid'=>$user_info['uid']],false,true);
        $this->_viewData['rows'] = $rows;

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

        $this->_viewData['goods_url'] = $sync_link."&redirect=http://{$domain}/goods-2746.html";


        parent::view('plan');
	}
}
