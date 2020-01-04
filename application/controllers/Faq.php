<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;
        parent::view('faq');
	}
}
