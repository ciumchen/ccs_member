<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;

        //根据时间范围获取奖金金额
        //$getData = $this->_getData;
        //$search['start'] = isset($getData['start_time']) ? $getData['start_time'] : date("Y-m", strtotime("-1 months", time()));
        //$search['end'] = isset($getData['end_time']) ? $getData['end_time'] : date("Y-m");

        //用户信息
        $this->_viewData['user_info'] = $user_info;

        //累计奖金
        $total_reward = $this->m_user_month_reward->get_user_all_reward_total($user_info['uid']);

        //本期奖金总和 todo 就是已入账
        $push_reward = $this->m_user_month_reward->get_user_month_reward_total($user_info['uid'],date('Y-m'));


        $wait_reward = $this->m_user_change_reward->get_reward_amount(array(
            'uid'=>$user_info['uid'],
            'status'=>0,
            'start'=>date('Y-m-01'),
            'end'=>date('Y-m-t'),
        ));

        //本期待入账奖金
        $this->_viewData['wait_reward'] = price_format($wait_reward);
        /**
         * 修改意见：本期奖金 = 本期已入账+本期待入账
         */
        $this->_viewData['month_reward'] = price_format($push_reward);


        $total_wait_reward = $this->m_user_change_reward->get_reward_amount(array(
            'uid'=>$user_info['uid'],
            'status'=>0,
        ));
        //累积带入账
        $this->_viewData['total_wait_reward'] = $total_wait_reward;

        //累积已入账
        $this->_viewData['total_reward'] = price_format($total_reward);

        //可提现奖金(总奖金金额 - 已经提现的金额)
        $allow_withdraw_amount = $this->m_users->get_allow_withdraw_amount($user_info['uid']);
//        if ($allow_withdraw_amount < 0){
//            $allow_withdraw_amount = 0;
//        }
        $this->_viewData['allow_withdraw_amount'] = price_format($allow_withdraw_amount);

        //本期奖金明细
        $this->_viewData['month_reward_details'] = $this->m_user_month_reward->get_user_month_reward($user_info['uid'],date('Y-m'),date('Y-m'),true);

        //累计奖金明细
        /**
         * 直接从m_user_change_reward的求和，累积奖金明细对不上，因为5月份的数据有覆盖到m_user_month_reward。没有修改到m_user_change_reward
         * 还是从累计奖金明细 = m_user_month_reward获取+待入账
         */
        $this->_viewData['total_reward_details'] = $this->m_user_month_reward->get_user_month_reward($user_info['uid'],'','',true);

        //获取月奖金明细
        $list = $this->m_user_month_reward->get_user_month_reward_list($user_info['uid']);
        foreach ($list as $key=>&$value)
        {
            /**
             * 如果月份是上个月，并且今天小于8号，可能存在待入账的金额
             */
            if($value['at_date'] == date('Y-m',strtotime('-1 month')) && date('d') < 8 ){
                $start = $value['at_date'].'-01 00:00:00';
                $end = $value['at_date'].'-31 23:59:59';
                $sql = "select type,sum(amount) as t_amount from m_user_change_reward where uid={$user_info['uid']} and status=0 and create_time>='$start' and create_time<='$end' group by type;";
                $rows = $this->db->query($sql)->result_array();
                foreach ($rows as $row){
                    if($row['type'] == 1){
                        $value['shopping_reward'] = $value['shopping_reward']+$row['t_amount'];
                    }
                    if($row['type'] == 2){
                        $value['m_rec_reward'] = $value['m_rec_reward']+$row['t_amount'];
                    }
                    if($row['type'] == 4){
                        $value['manage_reward'] = $value['manage_reward']+$row['t_amount'];
                    }
                }
            }
            $list[$key]['total'] = $value['shopping_reward']+
                $value['m_rec_reward']+
                $value['s_rec_reward']+
                $value['manage_reward']+
                $value['plat_reward'];
            if ($value['at_date'] == date('Y-m')){
                unset($list[$key]);
            }
        }
        $this->_viewData['list'] = $list;

        //搜索参数
        //$this->_viewData['search'] = $search;

        /**
         * 今日牌价
         */
        $day = date('Ymd');
        $goldPrice = $this->m_base->S('m_gold_price_day',['day'=>$day],'gold_price');
        $this->_viewData['goldPrice'] = $goldPrice ? $goldPrice['gold_price'] : '计算中...';

		parent::view('my_reward_new');
	}

    /**
     * 兑换金币
     */
    public function amountToGold(){
        $postData = $this->_postData;

        $gold = trim($postData['gold']);
        $uid = $this->_userInfo['uid'];

        if ($gold == ''){
            $this->response(array('code'=>1001,'msg'=>'请输入兑换的金币','data'=>array()));
        }
        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $gold) || $gold  <= 0){
            $this->response(array('code'=>1001,'msg'=>'兑换金币格式错误','data'=>array()));
        }

        /**
         * 今日牌价
         */
        $day = date('Ymd');
        $goldPrice = $this->m_base->S('m_gold_price_day',['day'=>$day],'gold_price');
        if(empty($goldPrice)){
            $this->response(array('code'=>1001,'msg'=>'等待今日牌价计算中。。。','data'=>array()));
        }

        $price = price_format($gold*$goldPrice['gold_price']);
        $allow_withdraw_amount = $this->m_users->get_allow_withdraw_amount($uid);
        if($price > $allow_withdraw_amount){
            $this->response(array('code'=>1001,'msg'=>'超过可兑换的奖金','data'=>array()));
        }

        //支付密码
        if(sha1($postData['payPwd'].$this->_userInfo['token']) != $this->_userInfo['pay_password']){
            $this->response(array('code'=>1001,'msg'=>'支付密码错误','data'=>array()));
        }

        //开始事务
        $this->db->trans_begin();

        /**
         * 金额变动,
         */
        $this->m_base->I('m_user_change_reward',[
            'uid'=>$uid,
            'type'=>8,//兑换金币
            'amount'=>-$price,
            'status'=>1,
        ]);
        /**
         * 金币变动
         */
        $this->m_base->I('m_user_change_gold',[
            'uid'=>$uid,
            'type'=>5,
            'before_amount'=>$price,
            'gold'=>$gold,
            'gold_price'=>$goldPrice['gold_price'],
            'status'=>1
        ]);


        /**
         * 3.累计金币
         */
        $this->db->where('uid', $uid)
            ->set('gold', 'gold+' . $gold, FALSE)
            ->set('transfer_amount', 'transfer_amount+' . $price, FALSE)
            ->update('m_users');


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','兑换金币错误:'.json_encode($postData));
            $this->response(array('code'=>106,'msg'=>'操作失败','data'=>array()));
        } else {
            $this->db->trans_commit();
            $f_amount = $allow_withdraw_amount - $price;
            $this->response(array('code'=>0,'msg'=>'','data'=>array('amount'=>$f_amount)));
        }
    }
}
