<?php


class M_user_change_gold extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param uid
     * @param int pager
     * @return mixed
     */
    public function get_list($uid,$pager = 1){
        $uid = $uid;
        $this->db->select('child_uid,type,gold,order_id,status,create_time');
        $this->db->where('uid', $uid);
        $this->db->order_by('create_time', 'desc');
        $this->db->limit(10, ($pager - 1) * 10);
        return $this->db->get('m_user_change_gold')->result_array();
    }

    /*
     * 指定金币来源明细
     *
     * */
    /*
     * public function get_user_change_gold_detail($uid, $year_mobth = false)
    {
        $gold_info = array(
            'sign'     => 0,
            'point'    => 0,
            'share'    => 0,
            'operate'  => 0,
            'buy'      => 0,
            'bestowal' => 0
        );
        $type_map = [1 => 'sign', 2=> 'point', 3 => 'share', 4 => 'operate', 5 => 'buy', 6 => 'bestowal'];
    }
    */
}