<?php

class M_goods extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取会员买过的商品，作为【我的足迹】、【我的收藏】
     * @param $uid
     * @param bool $grouping
     * @return array
     */
    public function get_buy_goods($uid,$grouping = false){

        $this->mall = $this->load->database('ccs168_mall', TRUE);

        $list = $this->db->select('goods_id,order_id,source_order_create_time')
                    ->where('user_id',$uid)
                    ->group_by('goods_id')
                    ->order_by('source_order_create_time','desc')
                    ->get('sea_user_order')->result_array();

        foreach ($list as $key =>$value){
            $goods = $this->mall->select('name,sell_price,img,point')
                ->where('id',$value['goods_id'])
                ->get('i_goods')->row_array();

            $list[$key]['goods_name'] = $goods['name'];
            $list[$key]['sell_price'] = $goods['sell_price'];

            //获取缩略图
            $list[$key]['goods_img'] = getGoodsThumbImg($goods['img']);
            $list[$key]['point'] = $goods['point'];
            $list[$key]['date'] = date('Y-m-d',strtotime($value['source_order_create_time']));
        }

        $new_list = array();
        //是否按日期分组
        if ($grouping == true){
            foreach ($list as $key=>$value){
                $date = $value['date'];
                $new_list[$date][] = array(
                    'goods_id'=>$value['goods_id'],
                    'order_id'=>$value['order_id'],
                    'date'=>$value['date'],
                    'goods_name'=>$value['goods_name'],
                    'sell_price'=>$value['sell_price'],
                    'goods_img'=>$value['goods_img'],
                    'point'=>$value['point'],
                );
            }
            return $new_list;
        }
        return $list;
    }

    public function addCouponLog($uid,$coupon_id){
        $count = $this->db->from('m_mall_coupon')->where('uid',$uid)->where('coupon_id',$coupon_id)->count_all_results();
        if($count == 0){
            $this->db->insert('m_mall_coupon',[
                'uid'=>$uid,
                'coupon_id'=>$coupon_id,
            ]);
        }
    }

}