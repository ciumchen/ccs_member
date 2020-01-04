<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/30
 * Time: 10:19
*/
    defined('BASEPATH') OR exit('No direct script access allowed');
    /*
     * 签到模块
     * */
    class Sign extends MY_Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function setSign()
        {
            /*
            * 金币等价券规则，返回对应天数对应的金币
            * @param int($totle_day) 天数
            * @return $before_amount    金币等价券
            * */
            $res = $this->_userInfo;
            $uid = $res['uid'];
            //连续签到的天数+1，计算出这次签到得到的积分
            $before_amount = getSignGold($res['total_day']+1,$res['last_sign_time']);

            # 获取今天时间
            $date = date('Y-m-d H:i:s');
            # 判断今天是否签到
            $day = date('Y-m-d');
            $start_time = $day . " 00:00:00";
            $end_time = $day . " 23:59:59";
            $where = array('uid' => $uid, 'last_sign_time >' => $start_time, 'last_sign_time <' => $end_time);
            $isexit = $this->db->where($where)->count_all_results('m_users');
            # log_message('error',$this->db->last_query());
            log_message('error',$this->db->last_query());
            if ($isexit)
            {
                $this->response(array('code' => 1110,'msg' => "今天已经签到过了，请明天继续",'data' => []));
            }

            if ($res['last_sign_time'])
            {
                # 存在签到 todo 改为自然日，判断签到的时间+1天，是否是今天 by john
                # 判断签到的时间+1后不等于今天 天数重置为1
                if ($day != date('Y-m-d',strtotime($res['last_sign_time'].'+1 day')))
                {
                    $data = array(
                        'total_day'      => 1,
                        'last_sign_time' => $date
                    );
                    $total_day = 1;

                } else
                {
                    # 更新签到的天数
                    $data = array(
                        'total_day'    => $res['total_day'] + 1,
                        'last_sign_time' => $date
                    );
                    $total_day = $res['total_day'] + 1;
                }
            } else
            {
                # 没有签到
                $data = array(
                    'total_day'      => 1,
                    'last_sign_time' => $date
                );
                $total_day = 1;
            }

            # 用户今天签到数据
            # 昨天连续签到
            /*$start_time = date("Y-m-d 00:00:00", strtotime("-1 day"));
            $end_time = date("Y-m-d 23:59:59", strtotime("+1 day"));
            $where = array('uid' => $uid, 'sign_time >' => $start_time, 'sign_time <=' => $end_time);
            $result = $this->db->field('sign_time')->where($where)->get('m_sign_detail')->row_array();*/

            //开始事务
            $this->db->trans_begin();

            /**
             * 0.签到详情记录
             */
            $this->m_base->I('m_user_sign_detail',[
                'uid'      => $uid,
                'sign_day' => $day,
                'sign_time'=> $date
            ]);

            //没有满足低消的会员，签到得到0
            $year_month = date('Y-m');
            $sale = $this->db->select('sale_amount')->where('uid',$uid)->where('year_month',$year_month)->get('m_stat_sale_month')->row_array();
            if($this->_userInfo['level']==0 || $sale['sale_amount'] < ($this->_userInfo['level']*300)){
                $before_amount = 0;
            }

                /**
             * 1.金价根据今日牌价转换成金币
             */
            $goldPrice = $this->m_common->getGoldPrice();
            # var_dump($goldPrice);die();
            $gold = $this->m_common->transferToGold($before_amount,$goldPrice);
            # var_dump($gold);die();

            /**
             * 2.金币变动
             */
            $this->m_base->I('m_user_change_gold',[
                'uid'           => $uid,
                'type'          => 1,
                'before_amount' => $before_amount,
                'gold'          => $gold,
                'gold_price'    => $goldPrice,
                'status'        => 1
            ]);
            # var_dump($this->m_base);die();

            /**
             * 3.累计金币,最后签到时间，连续签到天数
             */
            $this->db->where('uid', $uid)
                ->set('gold', 'gold+' . $gold, FALSE)
                ->update('m_users',$data);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $this->response(array('code' => 1110,'msg' => '操作失败','data' => []));
            } else
            {
                $this->db->trans_commit();
                $this->response(array('code' => 0,'msg' => "这是您第" . $total_day . "天签到",'data' => ['total_day'=>$total_day]));
            }
        }

        /*
         * 签到规则
         * */
        public function sign_rule()
        {
            parent::view('sign_rule');
        }
    }