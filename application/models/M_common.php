<?php

/**
 * 公共函数
 * Class M_common
 */
class M_common extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 金价券转换成金币
     */
    public function transferToGold($amount,$gold_price){

        $gold = $gold_price > 0 ? number_format($amount/$gold_price,2,'.','') : 0;

        return $gold < 0.01 ? 0 : $gold;
    }

    /**
     * 获取今日牌价
     */
    public function getGoldPrice($day=false){
        if($day == false){
            $day = date('Ymd');
        }
        $gold_price_row = $this->m_base->S('m_gold_price_day',['day'=>$day],'gold_price');

        /**
         * 如果为空,获取最近一天的牌价
         */
        if (!$gold_price_row) {
            $gold_price_row = $this->db->query('select * from m_gold_price_day order by `day` desc limit 1;')->row_array();
        }

        $gold_price = isset($gold_price_row['gold_price'])
            && $gold_price_row['gold_price'] ? $gold_price_row['gold_price'] : 1;

        return $gold_price;
    }

    /**
     * 判断今日牌价还在计算中，金币的操作需要暂停
     * ｅｘｐ：兑换金币，签到，积分，分享，经营
     */
    public function isGoldPriceEmpty(){
        $day = date('Ymd');
        $gold_price_row = $this->m_base->S('m_gold_price_day',['day'=>$day],'gold_price');
        return $gold_price_row ? false : true;
    }
}