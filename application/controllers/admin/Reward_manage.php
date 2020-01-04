<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward_manage extends MY_Controller {

    function __construct(){
        parent::__construct();
    }

    //佣金管理
    public function index(){
        $this->_viewData['title'] = '奖金管理';
        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['child_uid'] = isset($searchData['child_uid'])?$searchData['child_uid']:'';
        $searchData['order_id'] = isset($searchData['order_id'])?$searchData['order_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';

        $reward_list = $this->m_pager->get_reward_list($searchData);

        foreach ($reward_list as $k=>$reward){
            if($reward['type'] == 1){
                $count = $this->db->from('m_user_change_reward')->where('order_id',$reward['order_id'])->where('goods_id',$reward['goods_id'])->where('type',7)->count_all_results();
                $reward_list[$k]['is_refund'] = $count;
            }
        }

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_reward_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/reward_manage'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['reward_list'] = $reward_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];

        parent::view('admin/reward_list');
    }

    /* 商品退款 */
    public function refundOrderGoods(){

        $postData = $this->input->post();

        // 无效参数
        if (!$postData) {
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }
        $postData = string_escapes($postData);

        $rewardRow = $this->db->select('order_id,goods_id')->where('id',$postData['reward_id'])->get('m_user_change_reward')->row_array();
        if ($rewardRow == null){
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }
        if ($postData['remark'] == '') {
            $this->response(array('code'=>1001,'msg'=>'请输入退货备注','data'=>array()));
        }

        $count = $this->db->from('m_user_change_reward')->where('order_id',$rewardRow['order_id'])->where('goods_id',$rewardRow['goods_id'])->where('type',7)->count_all_results();
        if($count){
            $this->response(array('code'=>1001,'msg'=>'商品已退货','data'=>array()));
        }

        //事务开始
        $this->db->trans_begin();

        $this->m_order->refundOrderGoods($rewardRow['order_id'],$rewardRow['goods_id'],$postData['remark']);

        //管理员日志记录
        $this->db->insert('m_admin_action_log',array(
            'module_name'=>'奖金管理',
            'function_name'=>'商品退货',
            'opera_obj_id'=>$postData['reward_id'],
            'action_text'=>"退货备注：{$postData['remark']},订单号：{$rewardRow['order_id']},商品ID：{$rewardRow['goods_id']}",
            'admin_name'=>$_SESSION['admin_name']
        ));

        //事务回滚
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','商品退货操作错误:'.json_encode($postData));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>100,'msg'=>"退货成功",'data'=>array()));
        }
    }

    /**
     * 填写经营奖的分配总数 并计算根据团队利润的权重 计算出应该的经营奖金币等价券
     */
    public function addBusinessGoldPrice(){
        $postData = $this->input->post();

        // 无效参数
        if (!$postData) {
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }

        $year_month = date('Y-m',strtotime('-1 month'));
        $reward = $postData['reward'];

        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $reward) || $reward  <= 0){
            $this->response(array('code'=>1001,'msg'=>'经营奖的分配总数格式错误','data'=>array()));
        }

        if($reward <= 100){
            $this->response(array('code'=>102,'msg'=>'发放的金额不能低于100','data'=>array()));
        }

        /**
         * 1.$year_month的团队利润是否计算 ？
         * 2.$year_month的经营奖分配总数，是否填写？
         */
        $row = $this->db->select('gold_reward')->where('year_month',$year_month)
            ->get('m_stat_company_month')->row_array();
        if(!$row){
            $this->response(array('code'=>102,'msg'=>$year_month.'月利润正在计算中','data'=>array()));
        }
        if($row['gold_reward'] > 0){
            $this->response(array('code'=>102,'msg'=>$year_month.'经营奖的分配总数已经存在','data'=>array()));
        }
        /**
         * 1.如果这个月存在发放。不能更新总金价了
         */
        $isGrant = $this->m_base->C('m_stat_team_profit',['year_month'=>$year_month,'is_gold_grant'=>1]);
        if($isGrant){
            $this->response(array('code'=>102,'msg'=>'金币已经发放给会员，不能更新总价了','data'=>array()));
        }

        //开始事务
        $this->db->trans_begin();

        $total = $this->db->from('m_stat_team_profit')
            ->where('year_month',$year_month)
            ->where('percent >',0)
            ->where('gold',0)
            ->count_all_results();

        if($total == 0){
            $this->response(array('code'=>102,'msg'=>'没有找到有权重的会员了','data'=>array()));
        }

        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);

        //echo 'Stat All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            //echo 'This ' . $page . ' Page' ."\n";

            $rows = $this->db->select('uid,percent')
                ->where('year_month',$year_month)
                ->where('percent >',0)
                ->where('gold',0)
                ->limit($pageSize)
                ->get('m_stat_team_profit')->result_array();

            foreach ($rows as $row){
                $gold = price_format($row['percent'] * $reward);

                $gold = $gold < 0.01 ? 0 : $gold;
                if($gold == 0) continue;

                $data['gold'] = $gold;

                $this->db->where('uid',$row['uid'])
                    ->where('year_month',$year_month)
                    ->update('m_stat_team_profit',$data);
            }
            $page ++;
        }

        $this->db->where('year_month',$year_month)->update('m_stat_company_month',['gold_reward'=>$reward]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','经营奖的分配总数:'.json_encode($postData));
            $error = $this->db->error();
            $this->response(array('code'=>106,'msg'=>'操作失败'.$error['message'],'data'=>array()));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>0,'msg'=>'提交成功,已经根据团队利润权重计算出会员应得的金价券！','data'=>array()));
        }
    }
}
