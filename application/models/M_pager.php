<?php
/**
 * 	获取分页数据类
 * @author jason
 */
class M_pager extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 会员分页数据
     * @param $filter
     * @param int $perPage
     * @return mixed
     */
    public function get_users_list($filter, $perPage = 10) {
        $this->db->from('m_users');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'true_name':
                    $this->db->like('true_name',$v);
                    break;
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取用户记录数
    public function get_users_rows($filter) {
        $this->db->from('m_users');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'true_name':
                    $this->db->like('true_name',$v);
                    break;
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }



    public function get_withdraw_list($filter,$perPage = 10) {
        $this->db->select('u.mobile,u.true_name,u.image_url,uw.*')->from('m_user_withdraw uw')->join('m_users u','uw.uid = u.uid');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'mobile':
                    $this->db->where('u.mobile',$v);
                    break;
                case 'true_name':
                    $this->db->like('u.true_name',$v);
                    break;
                case 'start':
                    $this->db->where('uw.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('uw.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where('uw.'.$k, $v);
                    break;
            }
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取用户提现列表记录数
    public function get_withdraw_rows($filter) {
        $this->db->select('u.mobile,u.true_name,uw.*')->from('m_user_withdraw uw')->join('m_users u','uw.uid = u.uid');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'mobile':
                    $this->db->where('u.mobile',$v);
                    break;
                case 'true_name':
                    $this->db->like('u.true_name',$v);
                    break;
                case 'account':
                    $this->db->like('uw.account',$v);
                    break;
                case 'start':
                    $this->db->where('uw.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('uw.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where('uw.'.$k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    //获取订单记录数
    public function get_order_rows($filter) {
        $this->db->select('o.*,g.category_id,g.category_sub_id')->from('order o')->join('goods g','o.goods_id = g.goods_id');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'words':
                    $this->db->where('o.words <>','');
                    break;
                case 'category_id':
                    $this->db->where('g.category_id',$v);
                    break;
                case 'category_sub_id':
                    $this->db->where('g.category_sub_id',$v);
                    break;
                case 'goods_id':
                    $this->db->where('g.goods_id',$v);
                    break;
                case 'url':
                    $this->db->like('o.url', ($v));
                    break;
                case 'start':
                    $this->db->where('o.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('o.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /*******************************************   订单退款列表   ************************************/
    public function get_order_refund_list($filter, $perPage = 10) {
        $this->db->select('or.*,o.user_id,o.order_amount,o.goods_id,o.order_type,or.create_time,o.url,o.goods_name,o.number,o.start_number,o.finish_number,o.price,o.store_price,o.vote_item');
        $this->db->from('order_refund or')->join('order o','or.order_id = o.order_id');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'category_id':
                    $this->db->where('or.cate_id', ($v));
                    break;
                case 'category_sub_id':
                    $this->db->where('or.cate_sub_id', ($v));
                    break;
                case 'goods_id':
                    $this->db->where('or.goods_id', ($v));
                    break;
                case 'status':
                    $this->db->where('or.status', ($v));
                    break;
                case 'start':
                    $this->db->where('or.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('or.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where('o.'.$k, $v);
                    break;
            }
        }

        $this->db->order_by("create_time", "desc");

        //需要分页
        if($perPage) {
            $this->db->limit($perPage, ($filter['page'] - 1) * $perPage);
        }
        return $this->db->get()->result_array();
    }

    //获取记录数
    public function get_order_refund_rows($filter) {
        $this->db->select('or.*,o.user_id,o.order_amount,o.goods_id,o.goods_name,o.number,o.start_number,o.finish_number,o.price,o.store_price')->from('order_refund or')->join('order o','or.order_id = o.order_id');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'status':
                    $this->db->where('or.status', ($v));
                    break;
                case 'category_id':
                    $this->db->where('or.cate_id', ($v));
                    break;
                case 'category_sub_id':
                    $this->db->where('or.cate_sub_id', ($v));
                    break;
                case 'goods_id':
                    $this->db->where('or.goods_id', ($v));
                    break;
                case 'start':
                    $this->db->where('o.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('o.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where('o.'.$k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /**
     * @param $filter
     * @param int $perPage
     * @return mixed
     */
    public function get_reward_list($filter, $perPage = 10) {
        $this->db->from('m_user_change_reward');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取佣金记录数
    public function get_reward_rows($filter) {
        $this->db->from('m_user_change_reward');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /*****************************************  支付宝交易号列表  *****************************************/
    public function get_alipay_no_list($filter, $perPage = 10) {
        $this->db->from('zfb_pay');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取支付宝交易号记录数
    public function get_alipay_no_rows($filter) {
        $this->db->from('zfb_pay');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }




    /*****************************************  充值列表  *****************************************/
    public function get_recharge_list($filter, $perPage = 10) {
        $this->db->from('recharge_logs');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取充值记录数
    public function get_recharge_rows($filter) {
        $this->db->from('recharge_logs');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }

    /*****************************************  获取管理员列表  *****************************************/
    public function get_admin_user_list($filter, $perPage = 10) {
        $this->db->from('m_admin_user');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("id", "asc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取管理员记录数
    public function get_admin_user_rows($filter) {
        $this->db->from('m_admin_user');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }

    /**
     * 获取管理员操作记录
     * @param $filter
     * @param int $perPage
     * @return mixed
     */
    public function get_admin_action_log($filter, $perPage = 10) {
        $this->db->from('m_admin_action_log');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'opera_obj_id':
                    $this->db->like('opera_obj_id',$v);
                    break;
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /**
     * 获取管理员操作记录数
     * @param $filter
     * @return mixed
     */
    public function get_admin_action_log_rows($filter) {
        $this->db->from('m_admin_action_log');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'opera_obj_id':
                    $this->db->like('opera_obj_id',$v);
                    break;
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }



    /*****************************************  获取公告列表  *****************************************/
    public function get_bulletin_list($filter, $perPage = 10) {
        $this->db->from('bulletin');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by("id", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取公告记录数
    public function get_bulletin_rows($filter) {
        $this->db->from('bulletin');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }

    /*****************************************  会员金币  *****************************************/
    /*
     * 获取会员金币记录
     *
     * */
    public function get_gold_list($filter, $perPage = 10)
    {
        $this->db->from('m_user_change_gold');
        # $this->db->from('m_users');
        foreach ($filter as $k => $v)
        {
            //if($k = '' || $v = 'page')
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        # return $this->db->select('m_users.uid, m_users.true_name, m_users.mobile, m_user_change_gold.gold')->from('m_users')->join('m_user_change_gold', 'm_users.uid = m_user_change_gold.uid', 'left')->order_by('id', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        return $this->db->order_by('id', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取会员金币结记录数
     *
     * */
    public function get_gold_rows($filter)
    {
        $this->db->from('m_user_change_gold');
        foreach ($filter as $k => $v)
        {
            //if($k = '' || $v = 'page')
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('create_time >=', $v);
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /*****************************************  会员积分  *****************************************/
    /*
     * 获取会员积分记录
     *
     * */
    public function get_point_list($filter, $perPage = 10)
    {
        $this->db->from('m_user_change_point');
        foreach ($filter as $k => $v)
        {
            //if($k = '' || $v = 'page')
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by('id', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取会员积分记录数
     *
     * */
    public function get_point_rows($filter)
    {
        $this->db->from('m_user_change_point');
        foreach ($filter as $k => $v)
        {
            //if($k = '' || $v = 'page')
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('create_time >=', $v);
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /*****************************************  会员等级  *****************************************/
    /*
     * 获取会员等级记录
     *
     * */
    public function get_level_list($filter, $perPage = 10)
    {
        $this->db->from('m_user_change_level');
        foreach ($filter as $k => $v)
        {
            //if($k = '' || $v = 'page')
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by('id', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取会员等级记录数
     *
     * */
    public function get_level_rows($filter)
    {
        $this->db->from('m_user_change_level');
        foreach ($filter as $k => $v)
        {
            //if($k = '' || $v = 'page')
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('create_time >=', $v);
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }

    /*****************************************  签到列表  *****************************************/
    /*
     * 获取每日签到记录
     *
     * */
    public function get_sign_list($filter, $perPage = 10)
    {
        $this->db->from('m_user_sign_detail');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('sign_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('sign_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by('id', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取每日签到记录数
     *
     * */
    public function get_sign_rows($filter)
    {
        $this->db->from('m_user_sign_detail');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('sign_time >=', $v);
                    break;
                case 'end':
                    $this->db->where('sign_time <=', date('Y-m-d H:i:s', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /*****************************************  每日牌价  *****************************************/
    /*
     * 获取每日牌价记录
     *
     * */
    public function get_admin_goldPrice_list($filter, $perPage = 10)
    {
        $this->db->from('m_gold_price_day');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('day >=', ($v));
                    break;
                case 'end':
                    $this->db->where('day <=', date('Y-m-d H:i:s', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by('day', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取每日牌价记录数
     *
     * */
    public function get_admin_goldPrice_rows($filter)
    {
        $this->db->from('m_gold_price_day');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('day >=', $v);
                    break;
                case 'end':
                    $this->db->where('day <=', date('Y-m-d H:i:s', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }



    /*****************************************  每月消费  *****************************************/
    /*
     * 获取每月消费记录
     *
     * */
    public function get_saleMonth_list($filter, $perPage = 10)
    {
        $this->db->from('m_stat_sale_month');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('year_month >=', ($v));
                    break;
                case 'end':
                    $this->db->where('year_month <=', date('Y-m', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by('year_month', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取每月消费记录数
     *
     * */
    public function get_saleMonth_rows($filter)
    {
        $this->db->from('m_stat_sale_month');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('year_month >=', $v);
                    break;
                case 'end':
                    $this->db->where('year_month <=', date('Y-m', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }


    /*****************************************  帮扶计划  *****************************************/
    /*
     * 帮扶计划列表
     *
     * */
    public function get_helpPlan_list($filter, $perPage = 10)
    {
        $this->db->from('m_user_help_plan');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }
            switch ($k)
            {
                case 'start':
                    $this->db->where('year_month >=', ($v));
                    break;
                case 'end':
                    $this->db->where('year_month <=', date('Y-m', strtotime($v) + 86400 - 1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->order_by('id', 'desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /*
     * 获取帮扶计划记录数
     *
     * */
    public function get_helpPlan_rows($filter)
    {
        $this->db->from('m_user_help_plan');
        foreach ($filter as $k => $v)
        {
            if ($v == '' || $k=='page')
            {
                continue;
            }

            switch ($k)
            {
                case 'start':
                    $this->db->where('year_month >=', $v);
                    break;
                case 'end':
                    $this->db->where('year_month <=', date('Y-m', strtotime($v) + 86400 -1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        return $this->db->count_all_results();
    }
}
