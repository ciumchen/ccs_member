<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 提现业务
 * Class Withdraw
 */

class Withdraw extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }


    /* 提现页面 */
    public function index(){
        $user_info = $this->_userInfo;
        $this->_viewData['allow_withdraw_amount'] = $this->m_users->get_allow_withdraw_amount($user_info['uid']);

        /**
         * 查询会员的上一次提现的支付宝账号
         */
        $this->db->select('account,account_name');
        $this->db->where('uid',$user_info['uid'])->order_by('id desc');
        $row = $this->db->get('m_user_withdraw')->row_array();

        $this->_viewData['account'] = isset($row['account']) ? $row['account'] :'' ;
        $this->_viewData['account_name'] = isset($row['account_name']) ? $row['account_name'] :'' ;
        parent::view('withdraw_page');
    }

    /* 提现操作 */
    public function submit()
    {
        $user_info = $this->_userInfo;
        $postData = $this->_postData;

        //验证金额
        $allow_withdraw_amount = $this->m_users->get_allow_withdraw_amount($user_info['uid']);
        if (trim($postData['amount']) == ''){
            $this->response(array('code'=>1001,'msg'=>'请输入提现金额','data'=>array()));
        }
        if (!is_numeric($postData['amount'])){
            $this->response(array('code'=>1001,'msg'=>'提现金额错误','data'=>array()));
        }
        if ($postData['amount'] > $allow_withdraw_amount){
            $this->response(array('code'=>1001,'msg'=>'超过可提现金额','data'=>array()));
        }
        if ($postData['amount'] < 100){
            $this->response(array('code'=>1001,'msg'=>'提现金额不能少于100','data'=>array()));
        }

        //验证支付宝账户
        if (trim($postData['account']) == ''){
            $this->response(array('code'=>1001,'msg'=>'请输入支付宝账号','data'=>array()));
        }

        //验证真实姓名
        if (!is_real_name(trim($postData['real_name']))){
            $this->response(array('code'=>1001,'msg'=>'真实姓名错误','data'=>array()));
        }

        //验证协议
        if (!isset($postData['agree'])){
            $this->response(array('code'=>1001,'msg'=>'请先勾选用户协议','data'=>array()));
        }

        //支付密码
        if(sha1($postData['payPwd'].$this->_userInfo['token']) != $this->_userInfo['pay_password']){
            $this->response(array('code'=>1001,'msg'=>'支付密码错误','data'=>array()));
        }

        //开始事务
        $this->db->trans_begin();

        //1.累加提现金额
        $this->db->set('withdraw_amount','withdraw_amount+'.$postData['amount'],false);
        $this->db->where('uid',$user_info['uid']);
        $this->db->update('m_users');

        //2.添加提现记录
        $this->db->insert('m_user_withdraw',array(
            'uid'=>$user_info['uid'],
            'amount'=>$postData['amount'],
            'actual_amount'=>$postData['amount'],
            'type'=>1,
            'account_name'=>$postData['real_name'],
            'account'=>$postData['account']
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','提现错误:'.json_encode($postData));
            $this->response(array('code'=>106,'msg'=>'操作失败','data'=>array()));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>0,'msg'=>'提交成功,请等待平台处理','data'=>array()));
        }

    }

    /* 提现记录 */
    public function withdraw_log(){
        $date = isset($this->_getData['date']) ? $this->_getData['date'] : '';
        $user_info = $this->_userInfo;
        $this->db->select('*');
        $this->db->where('uid',$user_info['uid']);
        if ($date != '') {
            $this->db->like('create_time', $date);
        }
        $list = $this->db->get('m_user_withdraw')->result_array();
        $total = 0;
        # print_r($list);die();
        foreach ($list as $key=>$value){
            $list[$key]['create_time'] = date('n'.'月'.'j'.'日'. 'H:i:s' ,strtotime($value['create_time']));
            $total += $value['amount'];
        }
        $this->_viewData['list'] = $list;
        $this->_viewData['date'] = $date;
        $this->_viewData['total'] = $total;
        parent::view('withdraw_log');
    }
}