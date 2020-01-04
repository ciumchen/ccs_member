<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;
        parent::view('coupons');
	}
}
