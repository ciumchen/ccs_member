<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_manage extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->_viewData['title'] = '用户管理';
    }

    /*
     * 用户列表
     * */
    public function index(){
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['parent_id'] = isset($searchData['parent_id'])?$searchData['parent_id']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['mobile'] = isset($searchData['mobile'])?$searchData['mobile']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['level'] = isset($searchData['level'])?$searchData['level']:'';
        $searchData['true_name'] = isset($searchData['true_name'])?$searchData['true_name']:'';
        $users_list = $this->m_pager->get_users_list($searchData);

        foreach ($users_list as $k=>$user){
            $users_list[$k]['image_url'] = $user['image_url'];
            if (!strstr($user['image_url'],'http')){
                $users_list[$k]['image_url'] = $this->_head_ico_dir.$user['image_url'];
            }
            $users_list[$k]['reward'] = $this->m_user_month_reward->get_user_month_reward($user['uid']);
        }
        add_params_to_url($url,$searchData);
        #  var_dump($searchData);die;
        $config['total_rows'] = $this->m_pager->get_users_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['users_list'] = $users_list;
        # var_dump($users_list);die;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];

        parent::view('admin/users_list');
    }

    /*
     * 编辑会员信息
     * */
    public function edit_users_info()
    {
        $this->_viewData['title'] = '编辑会员信息';
        $getData = $this->input->get();
        if (!$getData['uid'])
        {
            echo "缺失会员账号";
            exit();
        }
        $users_info = $this->db->select('*')->where('uid',$getData['uid'])->get('m_users')->row_array();
        if ($users_info == null)
        {
            echo "账户不存在";
            exit();
        }
        $this->_viewData['users_info'] = $users_info;
        parent::view('admin/edit_users');
    }

    /*
     * 编辑会员信息提交
     * */
    public function doEdit_users()
    {
        $postData = $this->input->post();
        if (!$postData)
        {
            die(json_encode(array('success'=>true, 'msg'=>'参数缺失', 'code'=>1010)));
        }
        // 转义
        $postData = string_escapes($postData);

        // 账号状态
        if ($postData['status'] == '' || !is_numeric($postData['status']))
        {
            $this->response(array('code'=>1011,'msg'=>'请选择会员状态','data'=>array()));
        }
        // 更新状态
        $update_attr = array('status'=>$postData['status'], 'mobile' => $postData['mobile']);
        $update_ret = $this->db->where('uid',$postData['uid'])->update('m_users',$update_attr);

        if ($update_ret)
        {
            $this->response(array('code'=>100,'msg'=>'信息修改成功','data'=>array()));
        }
    }

    /*
     * 新增会员账号
     * */
    public function add_users_submit()
    {
        # $uid = $this->_userInfo['uid'];
        $goldPrice = $this->m_common->getGoldPrice();
        $postData = $this->input->post();
        # var_dump($postData);die;
        if (!$postData)
        {
            $this->response(array('code'=>1010,'msg'=>'参数缺失','data'=>array()));
        }

        // 真实姓名
        if ($postData['true_name'] == '')
        {
            $this->response(array('code'=>1011,'msg'=>'请填写真实姓名','data'=>array()));
        }

        // 手机号
        if ($postData['mobile'] == '')
        {
            $this->response(array('code'=>1012,'msg'=>'请填写正确手机号','data'=>array()));
        }
        $ret = $this->db->where('mobile',$postData['mobile'])->get('m_users')->row_array();
        if ($ret != null){
            $this->response(array('code'=>1011,'msg'=>'该手机号已经存在','data'=>array()));
        }

        // 登录密码
        if ($postData['password'] == '' || strlen($postData['password']) < 6)
        {
            $this->response(array('code'=>1012,'msg'=>'密码不能少于6位','data'=>array()));
        }

        // 推荐人手机号
        if ($postData['parent_mobile'] == '')
        {
            $this->response(array('code'=>1014,'msg'=>'请填写正确推荐人手机号','data'=>array()));
        }
        $ret = $this->db->where('mobile',$postData['parent_mobile'])->get('m_users')->row_array();
        if ($ret === null)
        {
            $this->response(array('code'=>1015,'msg'=>'该推荐人手机号不存在','data'=>array()));
        }

        // 获取推荐人ID
        $parent_id = $this->db->select('uid')->where('mobile',$postData['parent_mobile'])->get('m_users')->row_array();
        // 创建唯一token、密码加密
        $token = create_token();
        $password = md5($token.$postData['password'].'!@#');

        // 金币来源
        if ($postData['type'] == '')
        {
            $this->response(array('code'=>1016,'msg'=>'请选择金币来源','data'=>array()));
        }

        // 金币数量
        if ($postData['gold'] == '')
        {
            $this->response(array('code'=>1017,'msg'=>'请输入金币数量','data'=>array()));
        }

        $postData['status'] = 1;
        $postData['type'] = 12;
        $num = 20;
        $postData['gold'] = $num / $goldPrice;
        # 执行事务
        $this->db->trans_begin();
        # 添加会员
        $insert_attr = array();
        $insert_attr['mobile'] = $postData['mobile'];
        $insert_attr['username'] = 'p'.rand(100000,999999);
        $insert_attr['token'] = create_token();
        $insert_attr['password'] = md5($postData['password']);
        $insert_attr['parent_id'] = $parent_id['uid'];
        $insert_attr['true_name'] = $postData['true_name'];
        $insert_attr['gold'] = $postData['gold'];
        $this->db->insert('m_users',$insert_attr);
        $uid = $this->db->insert_id();

        # 根据uid发放注册奖励
        $insert_gold = array();
        $insert_gold['uid'] = $uid;
        $insert_gold['type'] = $postData['type'];
        $insert_gold['gold'] = $postData['gold'];
        $insert_gold['status'] = $postData['status'];
        $this->db->insert('m_user_change_gold',$insert_gold);

        # 统计会员所有的推荐人
        $parent_info = $this->m_users->get_user_info(array('mobile' => $postData['parent_mobile']));
        # var_dump($parent_info);die;
        $pidRes = $this->db->select('parent_id,level')->where('uid',$parent_info['uid'])->get('m_user_all_parents')->result_array();
        $pidArr = [];
        $pidArr[] = [
            'uid'       => $uid,
            'parent_id' => $parent_info['uid'],
            'level'     => 1
        ];
        foreach ($pidRes as $val)
        {
            $pidArr[] = [
                'uid'       => $uid,
                'parent_id' => $val['parent_id'],
                'level'     => $val['level']+1
            ];
        }
        unset($pidRes);
        $this->db->insert_batch('m_user_all_parents',$pidArr);

        # 进行事务判断数据插入是否成功
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            log_message('error','新增会员错误:'.json_encode($postData));
            $this->response(array('code' => 106,'msg' => '操作失败','data' => array()));
        } else
        {
            $this->db->trans_commit();
            $this->response(array('code' => 100,'msg' => '新增账号成功','data' => array()));
        }
        parent::view('admin/users_list');
    }

    /*
     * 提现列表
     * */
    public function withdraw_list(){
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['true_name'] = isset($searchData['true_name'])?$searchData['true_name']:'';
        $searchData['mobile'] = isset($searchData['mobile'])?$searchData['mobile']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $withdraw_list = $this->m_pager->get_withdraw_list($searchData);
        # var_dump($withdraw_list);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_withdraw_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/withdraw_list'.$url;

        $this->pagination->initialize($config);
        $this->_viewData['withdraw_list'] = $withdraw_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];

        parent::view('admin/withdraw_list');
    }

    /*
     * 提现到支付宝
     * */
    public function withdraw_to_alipay(){
        $postData = $this->input->post();
        // 无效参数
        if (!$postData) {
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }

        // 验证该提现记录是否存在
        $withdraw_info = $this->db->select('id,uid,amount,status')
            ->where('id',$postData['id'])
            ->get('m_user_withdraw')->row_array();
        if ($withdraw_info == null){
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }

        // 事务开始
        $this->db->trans_begin();

        // 实际到账金额
        $real_withdraw_amount = $withdraw_info['amount'];

        // 管理员日志记录
        $this->db->insert('m_admin_action_log',array(
            'module_name'=>'用户管理',
            'function_name'=>'支付宝已打款',
            'opera_obj_id'=>$withdraw_info['id'],
            'action_text'=>"提现金额：{$withdraw_info['amount']}，实际到账：{$real_withdraw_amount}",
            'admin_name'=>$_SESSION['admin_name']
        ));

        //提现申请修改为已打款
        $this->db->where('id',$postData['id'])->update('m_user_withdraw',array('status'=>2));

        //事务回滚
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','已打款操作错误:'.json_encode($postData));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>100,'msg'=>"已确认打款",'data'=>array()));
        }
    }

    /*
     * 提现驳回
     * */
    public function withdraw_refute(){
        $postData = $this->input->post();

        // 无效参数
        if (!$postData) {
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }
        $postData = string_escapes($postData);

        //验证order_id 和 user_id 是否是同一条记录
        $withdraw_info = $this->db->select('id,uid,amount')->where('id',$postData['withdraw_id'])->get('m_user_withdraw')->row_array();
        if ($withdraw_info == null){
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }
        if ($postData['remark'] == '') {
            $this->response(array('code'=>1001,'msg'=>'请输入驳回原因','data'=>array()));
        }

        //事务开始
        $this->db->trans_begin();

        //修改处理状态
        $this->db->where('id',$postData['withdraw_id'])->update('m_user_withdraw',array(
            'status'=>3,
            'remark'=>$postData['remark']
        ));

        //提现金额返还
        $this->db->query("update m_users set withdraw_amount = withdraw_amount-{$withdraw_info['amount']} where uid = {$withdraw_info['uid']}");

        //管理员日志记录
        $this->db->insert('m_admin_action_log',array(
            'module_name'=>'用户管理',
            'function_name'=>'提现驳回',
            'opera_obj_id'=>$postData['withdraw_id'],
            'action_text'=>"驳回原因：{$postData['remark']}",
            'admin_name'=>$_SESSION['admin_name']
        ));

        //事务回滚
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error','提现驳回操作错误:'.json_encode($postData));
        } else {
            $this->db->trans_commit();
            $this->response(array('code'=>100,'msg'=>"驳回成功",'data'=>array()));
        }
    }

    /*
     * 查看用户后台
     * */
    public function review_back_office(){

        $post = $this->input->post();
        if (!$post){
            die(json_encode(array('success'=>true, 'msg'=>'参数缺失', 'code'=>1010)));
        }

        //转义
        $post = string_escapes($post);
        $user_id = base64_decode($post['user_id']);

        $user_info = $this->m_users->get_user_info(array('uid'=>$user_id));
        if ($user_info == null){
            die(json_encode(array('success'=>true, 'msg'=>'用户ID异常', 'code'=>1010)));
        }

        //管理员日志记录
        $this->db->insert('m_admin_action_log',array(
            'module_name'=>'用户管理',
            'function_name'=>'查看后台',
            'opera_obj_id'=>$user_id,
            'action_text'=>$_SESSION['admin_name']."查看了{$user_id}的后台",
            'admin_name'=>$_SESSION['admin_name']
        ));

        $_SESSION['user_id'] = $user_id;
        $_SESSION['readOnly'] = $user_id;
        die(json_encode(array('success'=>true, 'msg'=>'模拟登陆成功', 'code'=>100)));
    }

    /*
     * 金币列表
     * */
    public function gold_list()
    {
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid']:'';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status']:'';
        $searchData['type'] = isset($searchData['type']) ? $searchData['type']:'';
        $searchData['child_uid'] = isset($searchData['child_uid']) ? $searchData['child_uid']:'';
        $searchData['order_id'] = isset($searchData['order_id']) ? $searchData['order_id']:'';
        # $searchData['gold'] = isset($searchData['gold']) ? $searchData['gold']:'';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

        $gold_list = $this->m_pager->get_gold_list($searchData);
        # var_dump($searchData['child_uid']);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_gold_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/gold_list'.$url;

        $this->pagination->initialize($config);
        # var_dump($config);die;
        $this->_viewData['gold_list'] = $gold_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        /*
         * 金币各项统计
        */
        /*
        $goldDetail = $this->db->query("select `type`,sum(`gold`) value from `m_user_change_gold` where `uid`={$this->_userInfo['uid']} group by `type`")->result_array();
        # var_dump($goldDetail);die();
        $showGold = [];
        $total_gold=0;
        foreach ($goldDetail as $val)
        {
            $val['type'] <=11 && $total_gold += $val['value'];
            $showGold[$val['type']] = $val['value'];
        }
        unset($goldDetail);
        $this->_viewData['showGold'] = $showGold;
        */
        # var_dump($showGold);die();
        # $this->_viewData['total_gold'] = $total_gold;
        /*
         * 金币各项统计
        */
        $goldDetail = $this->db->query("select `type`,sum(`gold`) value from `m_user_change_gold` where `uid`='{$this->_userInfo['uid']}' group by `type`")->result_array();
        # var_dump($goldDetail);die();
        $showGold = [];
        $total_gold=0;
        foreach ($goldDetail as $val)
        {
            $val['type'] <=12 && $total_gold += $val['value'];
            $showGold[$val['type']] = $val['value'];
        }
        unset($goldDetail);
        $this->_viewData['showGold'] = $showGold;
        # 金币转赠、受赠查询
        # $joinUid = $this->db->query("select `child_uid`,`uid` from `m_user_change_gold` where `type` in(6,7)")->result_array();
        # $this->_viewData['joinUid'] = $joinUid;
        # 金币总数统计
        $totGold = $this->db->query("select CASE type
                                     when 1 then '签到'
                                     when 2 then '积分'
                                     when 3 then '分享'
                                     when 4 then '大数据'
                                     when 5 then '兑换'
                                     when 6 then '受赠'
                                     when 7 then '转赠'
                                     when 8 then '手续费'
                                     when 9 then '游戏奖励'
                                     when 10 then '平台奖励'
                                     when 11 then '投资'
                                     when 12 then '注册奖励'
                                     when 13 then '商品兑换'
                                     when 14 then '商品退款' end type,
                             sum(gold) as gold from `m_user_change_gold` where 1 GROUP BY type")->result_array();
        $this->_viewData['totGold'] = $totGold;
        # var_dump($totGold);die;
        parent::view('admin/gold_list');
    }

    /*
     * 积分列表
     * */
    public function point_list()
    {
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid']:'';
        # var_dump($searchData['uid']);die();
        $searchData['point'] = isset($searchData['point']) ? $searchData['point']:'';
        $searchData['order_id'] = isset($searchData['order_id']) ? $searchData['order_id']:'';
        $searchData['goods_id'] = isset($searchData['goods_id']) ? $searchData['goods_id']:'';
        $searchData['child_uid'] = isset($searchData['child_uid']) ? $searchData['child_uid']:'';
        $searchData['type'] = isset($searchData['type']) ? $searchData['type']:'';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

        $point_list = $this->m_pager->get_point_list($searchData);
        # var_dump($searchData);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_point_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/point_list'.$url;

        $this->pagination->initialize($config);
        # var_dump($config);die;
        $this->_viewData['point_list'] = $point_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        parent::view('admin/point_list');
    }

    /*
     * 等级变动列表
     * */
    public function level_list()
    {
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid']:'';
        # var_dump($searchData['uid']);die();
        $searchData['old_level'] = isset($searchData['old_level']) ? $searchData['old_level']:'';
        $searchData['new_level'] = isset($searchData['new_level']) ? $searchData['new_level']:'';
        $searchData['create_time'] = isset($searchData['create_time']) ? $searchData['create_time']:'';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

        $level_list = $this->m_pager->get_level_list($searchData);
        # var_dump($searchData);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_level_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/level_list'.$url;

        $this->pagination->initialize($config);
        # var_dump($config);die;
        $this->_viewData['level_list'] = $level_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        parent::view('admin/level_list');
    }

    /*
     * 签到列表
     * */
    public function sign_list()
    {
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid']:'';
        # var_dump($searchData['uid']);die();
        $searchData['sign_day'] = isset($searchData['sign_day']) ? $searchData['sign_day']:'';
        $searchData['sign_time'] = isset($searchData['sign_time']) ? $searchData['sign_time']:'';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

        $sign_list = $this->m_pager->get_sign_list($searchData);
        # var_dump($sign_list);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_sign_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/sign_list'.$url;

        $this->pagination->initialize($config);
        # var_dump($config);die;
        $this->_viewData['sign_list'] = $sign_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        parent::view('admin/sign_list');
    }

    /*
     * 月消费列表
     * */
    public function saleMonth_list()
    {
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid']:'';
        # var_dump($searchData['uid']);die();
        $searchData['year_month'] = isset($searchData['year_month']) ? $searchData['year_month']:'';
        $searchData['sale_amount'] = isset($searchData['sale_amount']) ? $searchData['sale_amount']:'';
        $searchData['sale_profit'] = isset($searchData['sale_profit']) ? $searchData['sale_profit']:'';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

        $saleMonth_list = $this->m_pager->get_saleMonth_list($searchData);
        # var_dump($sign_list);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_saleMonth_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/saleMonth_list'.$url;

        $this->pagination->initialize($config);
        # var_dump($config);die;
        $this->_viewData['saleMonth_list'] = $saleMonth_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        parent::view('admin/saleMonth_list');
    }

    /*
     * 帮扶计划列表
     * */
    public function helpPlan_list()
    {
        $this->load->library('pagination');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData = string_escapes($searchData);
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid']:'';
        # var_dump($searchData['uid']);die();
        $searchData['count'] = isset($searchData['count']) ? $searchData['count']:'';
        $searchData['year_month'] = isset($searchData['year_month']) ? $searchData['year_month']:'';
        $searchData['is_buy'] = isset($searchData['is_buy']) ? $searchData['is_buy']:'';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status']:'';
        $searchData['profit'] = isset($searchData['profit']) ? $searchData['profit']:'';
        $searchData['order_id'] = isset($searchData['order_id']) ? $searchData['order_id']:'';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start']:'';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end']:'';

        $helpPlan_list = $this->m_pager->get_helpPlan_list($searchData);
        # var_dump($sign_list);die;

        add_params_to_url($url,$searchData);
        $config['total_rows'] = $this->m_pager->get_helpPlan_rows($searchData);
        $config['cur_page'] = $searchData['page'];

        $config['per_page'] = 10;
        $config['base_url'] = '/admin/users_manage/helpPlan_list'.$url;

        $this->pagination->initialize($config);
        # var_dump($config);die;
        $this->_viewData['helpPlan_list'] = $helpPlan_list;
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['total_rows'] = $config['total_rows'];
        parent::view('admin/helpPlan_list');
    }
}
