<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 测试发奖函数
 */

class Test extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_order');
    }

    /**
     * 测试奖励
     */
    public function john(){

        $uid = 978;
        $pid = 708;
        $order_id = 'N20170701';
        $goods_id = '20170701';
        $create_time = '2018-08-24';
        $sales = 99;//订单利润。按供应商拆单。
//        /**
//         * 个人消费奖
//         */
        //$this->m_order->shoppingSales($uid, $sales, $order_id, $goods_id, 300, 1, $create_time, 200);
//        /**
//         * 直接推荐奖
//         */
//        $this->m_order->parentSales($uid,$pid,$sales,$order_id);
//        /**
//         * 供应商推荐奖
//         */
//        $supplier_uid = 828;//供应商推荐人
//        $this->m_order->supplierSales($supplier_uid,$sales,$order_id);

        //积分奖励
//        $point = 30000; //订单维度。可能包含多个商品的积分
//        $this->m_order->pointSales($uid,$point,$order_id,$goods_id,$create_time);


//        $Sp = $this->db
//            ->select_sum('sale_profit')
//            ->where('year_month','2018-06')
//            ->get('m_stat_sale_month')
//            ->row_array();
//        $begin = date('Y-m-01 00:00:00',strtotime('2018-06'));
//        $end = date('Y-m-t 23:59:59',strtotime('2018-06'));
//
//        $a = $this->db
//            ->select_sum('amount')
//            ->where_in('type',[1,2])
//            ->where('create_time >=',$begin)
//            ->where('create_time <=',$end)
//            ->get('m_user_change_reward')
//            ->row_array();
//        var_dump($a);

//        ini_set('date.timezone','Asia/shanghai');
//        $date = date('Y-m-d H:i:s', strtotime('-7 days'));
//        echo $date;

        //$this->m_order->upgradeLevel('978',date('Y-m-d H:i:s'));
        //$this->m_users->check_parent_upgrade_level(978);
        //$this->m_order->upgradeLevel('978',date('Y-m-d H:i:s'),true);

        //$this->m_order->create_help_plan($uid,$pid,'2018-11',1,date('Y-m-d H:i:s'),$order_id);
        //$this->m_users->check_parent_upgrade_level(1400);

//        $userLevel = $this->db->query("select MAX(new_level) as u_level from m_user_change_level where 1 and uid=3655")->row_array();
//        $u_level = $userLevel['u_level'] ? $userLevel['u_level'] : 0;
//        $jumpCount = 3 - $u_level;
//        var_dump($u_level);
//        var_dump($userLevel);
//        var_dump($jumpCount);

        $this->m_order->create_active_plan('333',13652388723,date('Y-m-d H:i:s'),$order_id);
    }
}