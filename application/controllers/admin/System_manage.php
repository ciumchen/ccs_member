<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_manage extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->_viewData['title'] = '系统参数';
    }

    //开关设置
    public function switch_setting()
    {
        parent::view('/admin/system_switch_setting');
    }

    //参数设置
    public function param_setting()
    {
        if($this->_postData){
            $day = $this->_postData['day'];
            if(isset($this->_postData['isSubmit'])){
                $Iday = date('Ymd',strtotime("$day"));
                $row = $this->m_base->S('m_gold_price_day',['day'=>$Iday],'gold_price');
                if(isset($row['gold_price']) && $row['gold_price']){
                    $this->response(array('code'=>1001,'msg'=>'提交失败，已经存在了今日牌价'.$row['gold_price'],'data'=>[]));
                }
                $gold = $this->_postData['gold'];
                if(!$gold){
                    $this->response(array('code'=>1001,'msg'=>'牌价必填'.$row['gold_price'],'data'=>[]));
                }
                $this->m_base->I('m_gold_price_day',['day'=>$Iday,'gold_price'=>$gold]);
                $this->response(array('code'=>0,'msg'=>'','data'=>[]));
            }

            //计算昨天的比例
            $calc_day = date('Y-m-d',strtotime("$day -1 day"));
            $Q = $this->_postData['dataProfit'];

            if($Q === ''){
                $this->response(array('code'=>1001,'msg'=>'请输入大数据利润','data'=>[]));
            }

            $data = $this->calcGoldPrice($calc_day);

            $percent = $data['X']*0.2+$data['Y']*0.4+$data['Z']*0.1+$data['P']*0.2+$Q*0.1;

            $Iday = date('Ymd');
            $row = $this->m_base->S('m_gold_price_day',['day'=>$Iday],'gold_price');
            $gold = 1*($row['gold_price']+$percent);
            $gold =  number_format($gold,2,'.',',');


            $data['Q'] = $Q*0.1;
            $data['gold'] = $gold;

            $this->response(array('code'=>0,'msg'=>'','data'=>$data));
        }

        /**
         * 得到时间前后3天
         */
        $days[] = date('Y-m-d',strtotime('+1 day'));
        $days[] = date('Y-m-d',strtotime('+2 day'));

        $this->_viewData['days'] = $days;

        $year_month = date('Y-m',strtotime('-1 month'));
        $this->_viewData['year_month'] = $year_month;

        /*
         * 获取最近5天的牌价
         */
        $rows =  $this->db->order_by('day','desc')->limit(5)->get('m_gold_price_day')->result_array();
        $this->_viewData['rows'] = $rows;
        parent::view('/admin/system_param_setting');
    }

    public function calcGoldPrice($day)
    {
        /**
         *  昨天会员注册量的增长
         */
        $start = date('Y-m-d', strtotime($day));
        $end = date('Y-m-d 23:59:59', strtotime($day));
        $A = $this->m_base->C('m_users', ['create_time >=' => $start, 'create_time <=' => $end]);
        $T = $this->m_base->C('m_users', ['create_time <=' => $end]);
        $X = $A / $T;

        /**
         * VIP
         */
        $B = $this->m_base->C('m_users', ['create_time >=' => $start, 'create_time <=' => $end, 'level >' => 0]);
        $M = $this->m_base->C('m_users', ['create_time <=' => $end, 'level >' => 0]);
        $Y = $B / $M;

        $this->mall = $this->load->database('ccs168_mall', TRUE);
        /*
         * 利润增长
         */
        $yes_month_end = date('Y-m-d 23:59:59', strtotime("$start -1 day"));//昨天
        $yes_month_start = date('Y-m-d', strtotime("$start -31 day"));//昨天前30天的营业额
        $month_start = date('Y-m-d', strtotime("$start -30 day"));//今天前30天的营业额
        $month_end = date('Y-m-d 23:59:59', strtotime($start));//今天

        $S1 = $this->mall->query("SELECT sum(og.goods_price-og.cost_price) as sale
FROM i_order o left join i_order_goods og on og.order_id=o.id
WHERE o.pay_status=1 AND o.`create_time` >= '{$month_start}' AND o.`create_time` <= '{$month_end}'")->row_array();

        $S1 = $S1['sale'] ? $S1['sale'] : 1;

        $S2 = $this->mall->query("SELECT sum(og.goods_price-og.cost_price) as sale
FROM i_order o left join i_order_goods og on og.order_id=o.id
WHERE o.pay_status=1 AND o.`create_time` >= '{$yes_month_start}' AND o.`create_time` <= '{$yes_month_end}'")->row_array();
        $S2 = $S2['sale'] ? $S2['sale'] : 1;
        $Z = ($S1-$S2) / $S2;

        /**
         * 销售额
         */
        $W1 = $this->mall->query("SELECT sum(og.goods_price) as sale
FROM i_order o left join i_order_goods og on og.order_id=o.id
WHERE o.pay_status=1 AND o.`create_time` >= '{$month_start}' AND o.`create_time` <= '{$month_end}'")->row_array();

        $W1 = $W1['sale'] ? $W1['sale'] : 1;

        $W2 = $this->mall->query("SELECT sum(og.goods_price) as sale
FROM i_order o left join i_order_goods og on og.order_id=o.id
WHERE o.pay_status=1 AND o.`create_time` >= '{$month_start}' AND o.`create_time` <= '{$yes_month_end}'")->row_array();
        $W2 = $W2['sale'] ? $W2['sale'] : 1;
        $P = ($W1-$W2) / $W2;

        //log_message('error', '$A=' . $A . '$T=' . $T . '$B=' . $B . '$M=' . $M . '$profit=' . $profit . '$S2=' . $S2 . '$sale=' . $sale . '$W2=' . $W2);


        //$percent = $X*0.2+$Y*0.4+$Z*0.1+$P*0.2;

        return ['X'=>$X,'Y'=>$Y,'Z'=>$Z,'P'=>$P];
        }
}
