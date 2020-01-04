<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gold extends MY_Controller {

	public function index(){

        $user_info = $this->_userInfo;
        //用户信息
        $this->_viewData['user_info'] = $this->_userInfo;

        /**
         * 金币收入的各项统计
         */
        $goldDetail = $this->db->query("select type,sum(gold) value from m_user_change_gold where uid={$this->_userInfo['uid']} group by type;")->result_array();
        $showGold = [];
        $total_gold=0;
        foreach ($goldDetail as $val){
            $val['type'] <=6 && $total_gold += $val['value'];
            $showGold[$val['type']] = $val['value'];
        }
        unset($goldDetail);
        $this->_viewData['showGold'] = $showGold;
        $this->_viewData['total_gold'] = $total_gold;

        /**
         * 今日牌价
         */
        $day = date('Ymd');
        $goldPrice = $this->m_base->S('m_gold_price_day',['day'=>$day],'gold_price');
        $this->_viewData['goldPrice'] = $goldPrice ? $goldPrice['gold_price'] : '计算中...';

        /**
         * 获取金币变动记录
         */
        $this->load->model('m_user_change_gold');
        $list = $this->m_user_change_gold->get_list($this->_userInfo['uid']);

        $typeMap = config_item('gold_type');

        foreach ($list as $key=>&$value){
            $value['type_text'] = $typeMap[$value['type']];
            $value['create_time'] = date('Y-m-d',strtotime($value['create_time']));
            $value['order_id'] = $value['order_id'] ? $value['order_id'] : '';
            $value['child_uid'] = $value['child_uid'] ? $value['child_uid'] : '';
        }
        $this->_viewData['list'] = $list;

        //连续签到的天数+1，计算出这次签到得到的积分
        $before_amount = getSignGold($this->_userInfo['total_day']+1,$this->_userInfo['last_sign_time']);

        $this->_viewData['before_amount'] = $before_amount;

        /**
         * 检测今天是否签到
         */
        $isSign = false;
        if(date('Y-m-d',strtotime($this->_userInfo['last_sign_time'])) == date('Y-m-d')){
            $isSign = true;
        }
        $this->_viewData['isSign'] = $isSign;

        # 兑换商品兑换链接
        if(config_item('is_new_mall'))
        {
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

            $this->_viewData['addon_url'] = $sync_link."&redirect=http://{$domain}/addon/integral-goods-molists.html";
        } else
        {
            $this->_viewData['addon_url'] = '/addon';
        }

		parent::view('my_gold_new');
	}

    /* 获取积分数据 */
    public function next_page(){
        $uid = $this->_userInfo['uid'];
        $page = isset($this->_postData['page']) ? $this->_postData['page'] : 1;
        $this->load->model('m_user_change_gold');
        $list = $this->m_user_change_gold->get_list($uid,$page);

        $typeMap = config_item('gold_type');

        foreach ($list as $key=>&$value){
            $value['type_text'] = $typeMap[$value['type']];
            $value['create_time'] = date('Y-m-d',strtotime($value['create_time']));
            $value['order_id'] = $value['order_id'] ? $value['order_id'] : '';
            $value['child_uid'] = $value['child_uid'] ? $value['child_uid'] : '';
        }
        $this->response(array('code'=>0,'msg'=>'','data'=>$list));
    }

    /**
     * 转赠金币
     */
	public function TransferMemberGold(){
        $postData = $this->_postData;
        $uid = $this->_userInfo['uid'];

        if (trim($postData['gold']) == ''){
            $this->response(array('code'=>1001,'msg'=>'请输入转赠的金币','data'=>array()));
        }
        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $postData['gold']) || $postData['gold']  <= 0){
            $this->response(array('code'=>1001,'msg'=>'转赠金币格式错误','data'=>array()));
        }

        if($postData['gold'] < 100){
            $this->response(array('code'=>1001,'msg'=>'金币转赠至少是100','data'=>array()));
        }

        $user_info = $this->m_users->get_user_info(array('uid'=>$uid),'gold');

        if(price_format($postData['gold']*1.15) > price_format($user_info['gold'])){
            $this->response(array('code'=>1001,'msg'=>'超过可转赠金币','data'=>array()));
        }

        $receive = [];
        //验证账户是否存在
        if (trim($postData['mobile']) == ''){
            $this->response(array('code'=>1001,'msg'=>'请输入受赠的手机号','data'=>array()));
        }else{
            if(trim($postData['mobile']) == $this->_userInfo['mobile']){
                $this->response(array('code'=>1001,'msg'=>'不能转赠自己','data'=>array()));
            }
            $receive = $this->m_users->get_user_info(['mobile'=>trim($postData['mobile'])],'uid');
            if(!$receive){
                $this->response(array('code'=>1001,'msg'=>'受赠账户不存在','data'=>array()));
            }
        }
        //支付密码
        if(sha1($postData['payPwd'].$this->_userInfo['token']) != $this->_userInfo['pay_password']){
            $this->response(array('code'=>1001,'msg'=>'支付密码错误','data'=>array()));
        }

        //开始事务
        $this->db->trans_begin();

        $service_charge = config_item('service_charge');
        $service_fee = price_format($postData['gold']*$service_charge);

        $gold = $service_fee + $postData['gold'];

        /**
         * 转让方减去15%的额外金额 日志类型：转赠。接收方类型：受赠。
         */
        /**
         * .金币变动,
         */
        $this->m_base->I('m_user_change_gold',[
            'uid'=>$uid,
            'type'=>7,
            'before_amount'=>-$postData['gold'],
            'gold'=>-$postData['gold'],
            'gold_price'=>1, //转赠都是1
            'status'=>1,
            'child_uid'=>$receive['uid'],//受赠人
        ]);

        $this->m_base->I('m_user_change_gold',[
            'uid'=>$uid,
            'type'=>8,
            'before_amount'=>-$service_fee,
            'gold'=>-$service_fee,
            'gold_price'=>1,
            'status'=>1,
            'child_uid'=>$receive['uid'],//受赠人
        ]);

        /**
         * 累计金币
         */
        $this->db->where('uid', $uid)
            ->set('gold', 'gold-' . $gold, FALSE)
            ->update('m_users');

        /**
         * 接收方
         */
        $this->m_base->I('m_user_change_gold',[
            'uid'=>$receive['uid'],
            'type'=>6,
            'before_amount'=>$postData['gold'],
            'gold'=>$postData['gold'],
            'gold_price'=>1, //转赠都是1
            'status'=>1,
            'child_uid'=>$uid,//转赠人
        ]);

        /**
         * 3.累计金币
         */
        $this->db->where('uid', $receive['uid'])
            ->set('gold', 'gold+' . $postData['gold'], FALSE)
            ->update('m_users');


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','转增金币错误:'.json_encode($postData));
            $this->response(array('code'=>106,'msg'=>'操作失败','data'=>array()));
        } else {
            $this->db->trans_commit();
            $f_gold = $this->_userInfo['gold'] - $gold;
            $this->response(array('code'=>0,'msg'=>'提交成功,请等待平台处理','data'=>array('gold'=>$f_gold)));
        }
    }

    /**
     * 获取接收人的名字
     */
    public function getReceive(){
        $postData = $this->_postData;

        if (!is_phone($postData['mobile'])){
            $this->response(array('code'=>1001,'msg'=>'请输入手机号','data'=>array()));
        }
        if(trim($postData['mobile']) == $this->_userInfo['mobile']){
            $this->response(array('code'=>1001,'msg'=>'不能转赠自己','data'=>array()));
        }
        $receive = $this->m_users->get_user_info(['mobile'=>trim($postData['mobile'])],'true_name');
        if(!$receive){
            $this->response(array('code'=>1001,'msg'=>'受赠账户不存在','data'=>array()));
        }
        $this->response(array('code'=>0,'msg'=>'操作成功','data'=>array('name'=>$receive['true_name'])));
    }
}
