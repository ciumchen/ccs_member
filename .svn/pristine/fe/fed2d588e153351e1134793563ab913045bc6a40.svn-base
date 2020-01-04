<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;

        /**
         * 1，先查sea_user_order表，获取订单号，然后根据订单号，
         * 查另一个数据库的订单信息，得到该订单的自增ID，再查i_order_goods才能查到
         */
        $this->load->model('m_order');
        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['user_id'] = $user_info['uid'];

        $order_list = $this->m_order->get_order_list($searchData,10);

        foreach ($order_list as $k=>$order)
        {
            $this->mall = $this->load->database('ccs168_mall', TRUE);

            //订单状态
            if($order['pay_status'] == 0){
                $order_status = "待支付";
            }elseif($order['pay_status'] == 1){
                if($order['distribution_status'] == 1 || $order['distribution_status'] == 1){
                    $order_status = "待收货";
                    if($order['status'] == 5){
                        $order_status = "已完成";
                    }
                }else{
                    $order_status = "待发货";
                }
            }else{
                $order_status = "已退款";
            }

            $goods_list = $this->mall->select('goods_id,img,goods_array,goods_price,goods_point,goods_nums')
                ->where('order_id',$order['id'])
                ->get('i_order_goods')->result_array();

            $goods_count = 0;
            $order_point = 0;
            foreach ($goods_list as $key2=>$goods){
                $goods_count += $goods['goods_nums'];
                $order_point += $goods['goods_point'] * $goods['goods_nums'];
                $goods_array = json_decode($goods['goods_array'],true);
                $goods_list[$key2]['goods_name'] = $goods_array['name'];
                $goods_list[$key2]['img'] = getGoodsThumbImg($goods['img']);
                $goods_list[$key2]['goods_nums'] = $goods['goods_nums'];
                $goods_list[$key2]['goods_point'] = $goods['goods_point'];
                $goods_list[$key2]['goods_url'] = $this->m_users->get_sync_link($user_info['uid'],$goods['goods_id']);
            }
            $order_list[$k]['goods_list'] = $goods_list;
            $order_list[$k]['goods_count'] = $goods_count;
            $order_list[$k]['order_point'] = $order_point;
            $order_list[$k]['status'] = $order_status;
        }

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_order->get_order_list($searchData,10,'count(*)');
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/order'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['order_list'] = $order_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];

		parent::view('my_order');
	}
}
