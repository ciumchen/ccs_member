<?php


class M_user_change_point extends CI_Model {

    /**
     * @param uid
     * @param int pager
     * @return mixed
     */
    public function get_list($uid,$pager = 1){
        $this->db->select('*,sum(point) as total_point');
        $this->db->where('uid',$uid);
        $this->db->group_by('order_id');
        $this->db->order_by('create_time', 'desc');
        $this->db->limit(10, ($pager - 1) * 10);
        return $this->db->get('m_user_change_point')->result_array();
    }
}