<?php

/* 奖金相关封装类 */

class M_user_change_reward extends CI_Model {

    /**
     * @param $filter    array   过滤条件
     * @param $is_page   bool    是否分页
     * @return mixed
     */
    public function get_reward_info($filter = array(),$is_page = false){
        $this->db->from('m_user_change_reward');
        foreach ($filter as $k => $value){
            if ($value === '' || $k == 'page') {
                continue;
            }
            switch ($k) {
                case 'uid':
                    $this->db->where('uid',$value);
                    break;
                case 'type':
                    $this->db->where('type',$value);
                    break;
                case 'status':
                    $this->db->where('status',$value);
                    break;
                case 'start':
                    $this->db->where('create_time >=', ($value));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($value) + 86400));
                    break;
                default:
                    $this->db->where($k,$value);
                    break;
            }
        }
        $this->db->order_by('create_time', 'desc');

        //是否分页
        if ($is_page == true){
            $perPage = 10;
            $this->db->limit($perPage, ($filter['page'] - 1) * $perPage);
        }
        return $this->db->get()->result_array();
    }


    /**
     * @param $filter    array   过滤条件
     * @param $is_page   bool    是否分页
     * @return mixed
     */
    public function get_reward_amount($filter = array(),$is_page = false){
        $amount = 0;
        $reward_info = $this->get_reward_info($filter,$is_page);
        foreach ($reward_info as $key=>$value){
            $amount += $value['amount'];
        }
        return $amount;
    }
}