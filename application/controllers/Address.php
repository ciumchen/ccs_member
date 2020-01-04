<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('m_address');
    }

    public function index(){
        $user_info = $this->_userInfo;
        $address_list = $this->m_address->get_address_list($user_info['mobile']);
        $this->_viewData['address_list'] = $address_list;
		parent::view('address');
	}


    /* 添加地址 */
    public function add(){
        $postData = $this->_postData;
        $mobile = $this->_userInfo['mobile'];
        $retData = $this->m_address->add($mobile,$postData);
        $this->response($retData);
    }

    /*  编辑地址 */
    public function edit(){
        $postData = $this->_postData;
        if (!isset($postData['address_id'])){
            $this->response(array('code'=>102,'msg'=>'参数缺失',array()));
        }
        $retData = $this->m_address->edit($postData['address_id']);
        $this->response($retData);
    }

    /*  编辑地址 - 提交 */
    public function edit_submit(){
        $postData = $this->_postData;
        $retData = $this->m_address->edit_submit($postData);
        $this->response($retData);
    }

    /* 删除地址 */
    public function delete(){
        $postData = $this->_postData;
        if (!isset($postData['address_id'])){
            $this->response(array('code'=>102,'msg'=>'参数缺失',array()));
        }
        $retData = $this->m_address->delete($postData['address_id']);
        $this->response($retData);
    }


    /* 设为默认地址 */
    public function set_default(){
        $postData = $this->_postData;
        if (!isset($postData['address_id'])){
            $this->response(array('code'=>102,'msg'=>'参数缺失',array()));
        }
        $mobile = $this->_userInfo['mobile'];
        $retData = $this->m_address->set_default($mobile,$postData['address_id']);
        $this->response($retData);
    }
}
