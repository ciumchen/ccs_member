<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class History extends MY_Controller
    {

        public function index()
        {
            $user_info = $this->_userInfo;
            $this->_viewData['list'] = $this->m_goods->get_buy_goods($user_info['uid'],true);
            parent::view('history');
        }
    }
