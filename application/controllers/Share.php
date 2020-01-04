<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Share extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;

        $level_sale_map = config_item('mini_sales');
        /**
         * 当月的销售额度
         */
        $date = date('Y-m');
        $month = $this->m_users->get_month_sale_amount($user_info['uid'],$date);

        if($user_info['level'] == 0){
            $percent = $month ? '100' : '0';
        }else{
            $percent = intval($month/$level_sale_map[$user_info['level']]*100);
        }

        $percent = $percent > 100 ? 100 : $percent;
        $user_info['month_sales'] = $month;
        $user_info['percent'] = $percent.'%';

        //全部团队人数
        $all_child_count = $this->db
            ->from('m_user_all_parents')
            ->where('parent_id',$user_info['uid'])
            ->count_all_results();

        //直推人总数
        $child_count = $this->m_base->C('m_users',['parent_id'=>$user_info['uid']]);

        /*
         *
         * V1以上团队人数
        */
        $level = 1;
        # 直推V1及以上人数
        # $child_level = $this->m_base->C('m_users', ['parent_id'=>$user_info['uid'], 'level >= ' => $level]);
        # 团队V1及以上人数
        $all_level = $this->db
            ->from('m_users u')->join('m_user_all_parents p', 'p.uid = u.uid', 'left')
            ->where(['p.parent_id' => $user_info['uid'], 'u.level >=' => $level])
            ->count_all_results();

        /**
         * 当月新增人数
         */
        $month_begin = date('Y-m-01');
        $month_end = date('Y-m-t 23:59:59');
        $month_child_count = $this->m_base->C('m_users',[
            'parent_id'=>$user_info['uid'],
            'create_time >='=>$month_begin,
            'create_time <='=>$month_end
        ]);

        $sql = "select count(ms.uid) as count from m_user_all_parents mp,m_users ms where ms.uid=mp.uid and mp.parent_id={$user_info['uid']}
and ms.create_time>='$month_begin' and ms.create_time<='$month_end'";
        $all_child_month = $this->db->query($sql)->row_array();

        /**
         * 直推分享
         */
        $child_info = $this->m_users->get_child_info($user_info['uid']);

        foreach ($child_info as $key => $val)
        {
            # 参加帮扶计划的会员
            $where = array(
                'uid'   => $val['uid'],
                'count' => 1
            );
            $res = $this->db->select('uid')->where($where)->get('m_user_help_plan')->row_array();
            if($res){
                $child_info[$key]['isHelp'] = '是';
            }else{
                $child_info[$key]['isHelp'] = '否';
            }
        }

        $this->_viewData['user_info'] = $user_info;
        $this->_viewData['child_count'] = $child_count;
        $this->_viewData['month_child_count'] = $month_child_count;
        $this->_viewData['all_child_count'] = $all_child_count;
        $this->_viewData['all_child_month'] = $all_child_month['count'] ?  $all_child_month['count'] : 0;
        $this->_viewData['child_info'] = $child_info;
        $this->_viewData['level_sale_map'] = $level_sale_map;
        $this->_viewData['all_level'] = $all_level;

		parent::view('my_share');
	}
}
