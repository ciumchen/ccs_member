<?php

class M_user_month_reward extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /** 根据月份范围查找
     * @param $uid  用户ID
     * @param string $start_ym 开始时间(Y-m)
     * @param string $end_ym 结束时间
     * @param string $is_pending 是否包含未入账的
     * @return array 返回每项奖金的金额
     */
    public function get_user_month_reward($uid,$start_ym = '',$end_ym = '',$is_pending = false){

        $reward_info = array(
            'shopping_reward'=>0,
            'm_rec_reward'=>0,
            's_rec_reward'=>0,
            'manage_reward'=>0,
            'plat_reward'=>0,
        );
        $this->db->select('shopping_reward,m_rec_reward,s_rec_reward,manage_reward,plat_reward');
        $this->db->where('uid',$uid);
        if ($start_ym != ''){
            $this->db->where('at_date >=',$start_ym);
        }
        if ($end_ym != ''){
            $end_ym2 = $end_ym;
            $end_ym2 = date("Y-m", strtotime("+1 months", strtotime($end_ym2)));
            $this->db->where('at_date <',$end_ym2);
        }
        $user_month_reward = $this->db->get('m_user_month_reward')->result_array();

        foreach ($user_month_reward as $value){
            $reward_info['shopping_reward'] += $value['shopping_reward'];
            $reward_info['m_rec_reward'] += $value['m_rec_reward'];
            $reward_info['s_rec_reward'] += $value['s_rec_reward'];
            $reward_info['manage_reward'] += $value['manage_reward'];
            $reward_info['plat_reward'] += $value['plat_reward'];
        }

        if($is_pending === true){
            $type_map = [1=>'shopping_reward',2=>'m_rec_reward',3=>'s_rec_reward',4=>'manage_reward',5=>'plat_reward'] ;
            $sql = "select type,sum(amount) as total from m_user_change_reward where uid = $uid and status = 0 ";
            if ($start_ym != ''){
                $create_time = date('Y-m-d 00:00:00',strtotime($start_ym));
                $sql .= " and create_time >= '$create_time'";
            }
            if ($end_ym != ''){
                $create_time = date('Y-m-t 23:59:59',strtotime($end_ym));
                $sql .= " and create_time <= '$create_time'";
            }
            $sql .= ' group by type';

            $reward_detail = $this->db->query($sql)->result_array();
            foreach ($reward_detail as $val){
                $reward_info[$type_map[$val['type']]] += $val['total'];
            }
        }

        return $reward_info;
    }

    /**
     * 得到指定各个奖金明细：包含未入账
     * @param $uid
     * @param bool $year_month false表示获取所有的奖金明细
     * @return array
     */
    public function get_user_month_reward_detail($uid,$year_month = false){
        $reward_info = array(
            'shopping_reward'=>0,
            'm_rec_reward'=>0,
            's_rec_reward'=>0,
            'manage_reward'=>0,
            'plat_reward'=>0,
        );
        $type_map = [1=>'shopping_reward',2=>'m_rec_reward',3=>'s_rec_reward',4=>'manage_reward',5=>'plat_reward'] ;

        if($year_month !== false){

            $month_begin = date('Y-m-01 00:00:00',strtotime($year_month));
            $sql = "select type,sum(amount) as total from m_user_change_reward where uid = $uid and create_time >='$month_begin' group by type";

        }else{
            $sql = "select type,sum(amount) as total from m_user_change_reward where uid = $uid group by type";
        }

        $reward_detail = $this->db->query($sql)->result_array();
        foreach ($reward_detail as $val){
            $reward_info[$type_map[$val['type']]] = $val['total'];
        }
        return $reward_info;
    }

    /** 根据月份范围查找
     * @param $uid  用户ID
     * @param string $start_ym 开始时间(Y-m)
     * @param string $end_ym 结束时间
     * @return float|int 返回一个总金额
     */
    public function get_user_month_reward_total($uid,$start_ym = '',$end_ym = ''){
        $user_month_reward = $this->get_user_month_reward($uid,$start_ym,$end_ym);
        $user_month_reward_total = $user_month_reward['shopping_reward'] +
            $user_month_reward['m_rec_reward'] +
            $user_month_reward['s_rec_reward'] +
            $user_month_reward['manage_reward'] +
            $user_month_reward['plat_reward'];

        return $user_month_reward_total;
    }

    /**
     * 获取用户所有奖金总和
     * @param $uid 用户ID
     * @return float|int 返回一个总金额
     */
    public function get_user_all_reward_total($uid){

        $sum = '`shopping_reward`+`m_rec_reward`+`s_rec_reward`+`manage_reward`+`plat_reward`';
        $sql = "select sum({$sum}) as total from m_user_month_reward where uid = {$uid}";
        $res = $this->db->query($sql)->row_array();
        return $res['total'] == null ? 0 : $res['total'];
    }


    /**
     * 获取月奖金明细
     * @param $uid
     * @return array
     */
    public function get_user_month_reward_list($uid){
        $this->db->select('*')->where('uid',$uid);
        $this->db->order_by('at_date','desc');
        $list = $this->db->get('m_user_month_reward')->result_array();
        return $list;
    }
}
