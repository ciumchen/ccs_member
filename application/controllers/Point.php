<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point extends MY_Controller {

	public function index(){
        $user_info = $this->_userInfo;

        $list = $this->m_user_change_point->get_list($user_info['uid']);
        foreach ($list as $key=>$value){
            $list[$key]['order_no'] = $this->m_order->get_order_no($value['order_id']);
            $list[$key]['type_text'] = $value['child_uid'] == '0' ? '本人消费' : "分享消费";
            $list[$key]['order_point'] = $value['total_point'] / $value['percent'];
            $list[$key]['point'] = $value['total_point'];
            $list[$key]['point_per'] = $value['percent'] * 100 .'%';
        }

        $more_per = '0%';
        if ($user_info['point'] > 0){
            $member_count = $this->db->from('m_users')->count_all_results();
            $than_less_count = $this->db->from('m_users')->where('point <',$user_info['point'])->count_all_results();
            $more_per = number_format(($than_less_count / $member_count) * 100,1).'%';
        }

        if ($user_info['level'] == 6){
            $upgrade_tips = '当前是最高级别';
        }else{
            $after_level = $user_info['level']+1;
            $diff_point = getLevelNeedPoint($after_level)-$user_info['point'];
            $diff_point = $diff_point > 0 ? $diff_point : 0;
            $upgrade_tips = "距离升级V{$after_level}积分还差{$diff_point}";

            if(date('Y-m-d') >= '2018-10-14') {
                if ($after_level >= 2) {
                    $find_level = $user_info['level'];
                    $childCount = $this->m_users->get_level_count($user_info['uid'], $find_level);
                    $diff_percent = 3 - $childCount;
                    $diff_percent = $diff_percent > 0 ? $diff_percent : 0;
                    $upgrade_tips .= ",团队中至少V{$find_level}等级还差{$diff_percent}人";
                    // 如果积分差0，直推人差0，调用升级流程
                    if($diff_point == 0 && $diff_percent == 0){
                        $this->m_order->upgradeLevel($user_info['uid'],date('Y-m-d H:i:s'));
                    }
                }
            }else{
                if($after_level == 2){
                    $childCount = $this->m_base->C('m_users',['parent_id'=>$user_info['uid']]);
                    $diff_percent = 3 - $childCount;
                    $diff_percent = $diff_percent > 0 ? $diff_percent : 0;
                    $upgrade_tips .= ",直推人还差{$diff_percent}人";
                    // 如果积分差0，直推人差0，调用升级流程
                    if($diff_point == 0 && $diff_percent == 0){
                        $this->m_order->upgradeLevel($user_info['uid'],date('Y-m-d H:i:s'));
                    }
                }
            }
        }
        $this->_viewData['user_info'] = $user_info;
        $this->_viewData['more_per'] = $more_per;
        $this->_viewData['upgrade_tips'] = $upgrade_tips;
        $this->_viewData['list'] = $list;

		parent::view('point');
	}

	/* 获取积分数据 */
	public function next_page(){
        $uid = $this->_userInfo['uid'];
        $page = isset($this->_postData['page']) ? $this->_postData['page'] : 1;
        $list = $this->m_user_change_point->get_list($uid,$page);
        foreach ($list as $key=>$value){
            $list[$key]['order_no'] = $this->m_order->get_order_no($value['order_id']);
            $list[$key]['type_text'] = $value['child_uid'] == '0' ? '本人消费' : "推荐消费";
            $list[$key]['order_point'] = $value['total_point'] / $value['percent'];
            $list[$key]['point'] = $value['total_point'];
            $list[$key]['point_per'] = $value['percent'] * 100 .'%';
        }
        $this->response(array('code'=>0,'msg'=>'','data'=>$list));
    }
}
