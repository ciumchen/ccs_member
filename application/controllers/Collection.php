<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;
        $this->_viewData['list'] = $this->m_goods->get_buy_goods($user_info['uid']);
        parent::view('my_collection');
	}
}
