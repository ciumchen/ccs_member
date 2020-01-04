<?php

/* 用户模型 */

class M_users extends CI_Model {

    /**
     * 查询用户
     * @param array  $where
     * @param string $field
     * @return mixed
     */
    public function get_user_info($where,$field = "*"){
        $user_info = $this->db->select($field)->where($where)->get("m_users")->row_array();
        return $user_info;
    }

    /**
     * 用户钱包
     */
    public function get_user_wallet($where,$field = "*"){
        $user_info = $this->db->select($field)->where($where)->get("m_user_wallet")->row_array();
        return $user_info;
    }

    /**
     * 获取直推会员的全部销售额
     * @param $uid
     * @return float
     */
    public function get_child_amount($uid,$year_month = false){
        /**
         * 获取指定月份的销售额
         */
        if($year_month){
            //1.获取全部团队人数
            $one_child = $this->db->select('uid')->where('parent_id',$uid)->where('level',1)->get('m_user_all_parents')->result_array();
            $uid_list = [];
            foreach ($one_child as $child){
                $uid_list[]  = $child['uid'];
            }
            //没有下线，销售业绩为0
            if ( ! $uid_list){
                return 0;
            }

            $res = $this->db->select_sum('sale_amount')
                ->where_in('uid',$uid_list)
                ->where('year_month',$year_month)
                ->get('m_stat_sale_month')
                ->row_array();
            return $res['sale_amount'] == null ? 0 : $res['sale_amount'];

        }
        $res = $this->db->select_sum('shopping_amount')->where('parent_id',$uid)->get('m_users')->row_array();
        return $res['shopping_amount'] == null ? 0 : $res['shopping_amount'];
    }

    /**
     * 获取团队所有会员的全部销售额
     * @param $uid
     * @return float
     */
    public function get_all_child_amount($uid,$year_month = false){

        $uid_list = array();

        //1.获取全部团队人数
        $all_child = $this->db->select('uid')->where('parent_id',$uid)->get('m_user_all_parents')->result_array();
        foreach ($all_child as $child){
            $uid_list[]  = $child['uid'];
        }

        //没有下线，销售业绩为0
        if ($uid_list == array()){
            return 0;
        }

        /**
         * 获取指定月份的销售额
         */
        if($year_month){

            $res = $this->db->select_sum('sale_amount')
                ->where_in('uid',$uid_list)
                ->where('year_month',$year_month)
                ->get('m_stat_sale_month')
                ->row_array();
            return $res['sale_amount'] == null ? 0 : $res['sale_amount'];
        }

        $res = $this->db->select_sum('shopping_amount')->where_in('uid',$uid_list)->get('m_users')->row_array();
        return $res['shopping_amount'] == null ? 0 : $res['shopping_amount'];
    }

    public function get_child_info($uid){
        $level_map = [
            '0'=>'V0',
            '1'=>'V1',
            '2'=>'V2',
            '3'=>'V3',
            '4'=>'V4',
            '5'=>'V5',
            '6'=>'V6',
        ];

        $mini_sales = config_item('mini_sales');
        $res = $this->db->select('uid,mobile,true_name,level')->where('parent_id',$uid)->get('m_users')->result_array();

        $date = date('Y-m');
        foreach ($res as &$val){

            $month = $this->get_month_sale_amount($val['uid'],$date);

            if($val['level'] == 0){
                $percent = $month ? '100' : '0';
            }else{
                $percent = intval($month/$mini_sales[$val['level']]*100);
            }

            $percent = $percent > 100 ? 100 : $percent;
            $val['month_sales'] = $month;
            $val['percent'] = $percent.'%';
            $val['level_text'] = $level_map[$val['level']];
        }
        return $res;
    }

    /* 查询最大ID */
    public function get_max_id(){
        $res = $this->db->select_max('user_id','max_user_id')->get('m_users')->row_array();
        return $res['max_user_id'] == null ? 1888 : $res['max_user_id'];
    }

    /**
     * 获取可提现的金额
     * @param $uid
     * @return float
     */
    public function get_allow_withdraw_amount($uid){
        $allow_withdraw_amount = 0;
        $user_info = $this->get_user_info(array('uid'=>$uid));
        if ($user_info)
        {
            $total_reward = $this->m_user_month_reward->get_user_all_reward_total($uid);
            $allow_withdraw_amount = $total_reward - $user_info['withdraw_amount'] - $user_info['transfer_amount'];
            /*if ($allow_withdraw_amount < 0){
                $allow_withdraw_amount = 0;
            }*/
        }
        return price_format($allow_withdraw_amount);
    }

    /**
     * 根据供应商手机号，获取推荐人ID
     * @param $supplier_mobile   供应商手机号
     * @return null|int
     */
    public function get_supplier_recommend_id($supplier_mobile){

        if ($supplier_mobile == null){
            return null;
        }

        //1.根据user_id 查找sea_company_user 信息
        $company_info = $this->db->select('inviter_username,mobile')
            ->where('mobile',$supplier_mobile)
            ->get('m_supplier')->row_array();
        if ($company_info == null){
            return null;
        }

        //2.推荐人手机号是否为空
        if ($company_info['inviter_username'] == null){
            return null;
        }

        //3.查找推荐人的ID号
        $user_info = $this->db->select('uid')->where('mobile',$company_info['inviter_username'])->get('m_users')->row_array();
        if ($user_info == null){
            return null;
        }
        return $user_info['uid'];
    }

    /**
     * 根据月份获取用户的销售额
     * @param $uid  用户ID
     * @param $date 时间格式(Y-m)
     * @return int
     */
    public function get_month_sale_amount($uid,$date){
        //$user_info = $this->m_users->get_user_info(array('uid'=>$uid));
        //$this->mall = $this->load->database('ccs168_mall', TRUE);

        //$order_info = $this->mall->select_sum('real_amount','total')
        //    ->where('user_mobile',$user_info['mobile'])
        //    ->where('pay_status',1)
        //    ->like('pay_time',$date)
        //    ->get('i_order')->row_array();
        //$month_sale_amount = $order_info['total'] == null ? 0 : $order_info['total'];
        $res = $this->db->select('sale_amount')
            ->where_in('uid',$uid)
            ->where('year_month',$date)
            ->get('m_stat_sale_month')
            ->row_array();
        return $res['sale_amount'] == null ? 0 : $res['sale_amount'];;
    }

    /**
     * 登录验证
     * @param $param 请求参数
     * @return array
     */
    public function check_login($param){

        if (is_phone($param['mobile']) == ''){
            return array('code'=>1001,'msg'=>'请输入正确的手机号码','data'=>array());
        }

        $mobile = $param['mobile'];
        $user_info = $this->get_user_info(array('mobile'=>$mobile));
        if ($user_info == null){
            return array('code'=>1002,'msg'=>'该手机号码还未注册','data'=>array());
        }
        if($user_info['status'] == 2){
            return array('code'=>1002,'msg'=>'账户已被冻结，无法登录','data'=>array());
        }

        //1.账号密码登录
        if ($param['login_type'] == 1) {
            if (trim($param['password']) == ''){
                return array('code'=>1002,'msg'=>'请输入密码','data'=>array());
            }
            if (md5($param['password']) != $user_info['password']){
                return array('code'=>1004,'msg'=>'密码错误,请重试','data'=>array());
            }
        }

        //2.验证码登录
        if ($param['login_type'] == 2){
            if (trim($param['mobile_code']) == ''){
                return array('code'=>1002,'msg'=>'请输入验证码','data'=>array());
            }

            //验证码是否正确
            $where = array('mobile'=>$mobile,'code'=>$param['mobile_code'],'code_type'=>SMS_LOGIN);
            $code_info = $this->M_user_mobile_code->get_mobile_code($where);
            if ($code_info == null) {
                return array('code'=>1002,'msg'=>'验证码错误','data'=>array());
            }else{
                if ($code_info['expire_time'] < date('Y-m-d H:i:s')){
                    return array('code'=>1002,'msg'=>'验证码已过期','data'=>array());
                }
            }
        }
        $data['user_info'] = $user_info;
        return array('code'=>0,'msg'=>'验证成功','data'=>$data);
    }

    /**
     * 验证注册信息
     * @param $param
     * @return array
     */
    public function checkRegister($param){
        // 验证真实用户名
        if (is_real_name($param['true_name']) == ''){
            return array('code'=>1001,'msg'=>'请输入正确的姓名','data'=>array());
        }

        //是否已存在
        $truename = $param['true_name'];
        /*$user_info = $this->get_user_info(array('true_name'=>$truename));
        if ($user_info){
            return array('code'=>1001,'msg'=>'该用户名已经注册','data'=>array());
        }
        */

        //验证手机号
        if (is_phone($param['mobile']) == ''){
            return array('code'=>1001,'msg'=>'请输入正确的手机号码','data'=>array());
        }

        //是否已存在
        $mobile = $param['mobile'];
        $user_info = $this->get_user_info(array('mobile'=>$mobile));
        if ($user_info){
            return array('code'=>1001,'msg'=>'该手机号已经注册','data'=>array());
        }

        //验证推荐人手机号
        $parent_mobile = $param['parent_mobile'];
        $parent_info = $this->get_user_info(array('mobile'=>$parent_mobile));
        if (!$parent_info){
            return array('code'=>1001,'msg'=>'推荐人不存在','data'=>array());
        }
        if ($parent_mobile == $mobile){
            return array('code'=>1001,'msg'=>'推荐人不能为自己','data'=>array());
        }

        //验证密码
        if (strlen($param['password']) < 6 || strlen($param['password']) > 20){
            return array('code'=>1002,'msg'=>'密码长度在6-20位之间','data'=>array());
        }
        //验证手机验证码
        if (trim($param['mobile_code']) == ''){
            return array('code'=>1003,'msg'=>'请输入手机验证码','data'=>array());
        }

        //查询验证码
        $where = array(
            'mobile'=>$mobile,
            'code'=>$param['mobile_code'],
            'code_type'=>REGISTER
        );
        $code_info = $this->m_user_mobile_code->get_mobile_code($where);
        if ($code_info == null){
            return array('code'=>1004,'msg'=>'验证码错误','data'=>array());
        }else{
            if ($code_info['expire_time'] < date('Y-m-d H:i:s')){
                return array('code'=>1004,'msg'=>'验证码已过期','data'=>array());
            }
        }

        /***************************************  创建账户 *********************************************/

        $this->db->trans_begin();

        $insert_attr = array();
        $insert_attr['mobile'] = $mobile;
        $insert_attr['username'] = 'p'.rand(100000,999999);
        $insert_attr['token'] = create_token();
        $insert_attr['password'] = md5($param['password']);
        $insert_attr['parent_id'] = $parent_info['uid'];
        $insert_attr['true_name'] = $truename;
        $insert_attr['image_url'] = 'default_head_ico.jpg';
        $this->db->insert('m_users',$insert_attr);
        $uid = $this->db->insert_id();

        /**
         * 统计会员所有的推荐人
         */
        $pidRes = $this->db->select('parent_id,level')->where('uid',$parent_info['uid'])->get('m_user_all_parents')->result_array();
        $pidArr = [];
        $pidArr[] = [
            'uid'=>$uid,
            'parent_id'=>$parent_info['uid'],
            'level'=>1,
        ];
        foreach ($pidRes as $val){
            $pidArr[] = [
                'uid'=>$uid,
                'parent_id'=>$val['parent_id'],
                'level'=>$val['level']+1,
            ];
        }
        unset($pidRes);
        $this->db->insert_batch('m_user_all_parents',$pidArr);

        /**
         * 3.如果上线的等级是1，触发3个直推升级流程
         */
        /**
         * 新升级规则 这里的调用的废弃 by john 2018-09-09
         */
        if(date('Y-m-d') < '2018-10-14'){
            if($parent_info['level'] == 1){
                $this->m_order->upgradeLevel($parent_info['uid'],date('Y-m-d H:i:s'));
            }
        }

        /**
         *  4.设为有未读消息，新增消息
         */
        $this->db->where('uid',$parent_info['uid'])->update('m_users',['message_type'=>2]);
        $this->db->insert('m_message',array(
            'uid'=>$parent_info['uid'],
            'type'=>2,
            'param1'=>$mobile,
            'content'=>'你有新增的直接分享1名!'
        ));

        /**
         * 5.注册增加20枚大数据注册奖励
         */
        $grant = 20;
        $goldPrice = $this->m_common->getGoldPrice();
        $gold = $this->m_common->transferToGold($grant,$goldPrice);
        $this->m_base->I('m_user_change_gold',[
            'uid'=>$uid,
            'type'=>12,
            'before_amount'=>$gold,
            'gold'=>$gold,
            'gold_price'=>1,
            'status'=>1,
        ]);

        $this->db->where('uid', $uid)
            ->set('gold', 'gold+' . $gold, FALSE)
            ->update('m_users');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('code'=>102,'msg'=>'网络开小差了,请重试一次','data'=>array());
        } else {
            $this->db->trans_commit();
            $_SESSION['user_id'] = $uid;

            return array('code'=>0,'msg'=>'注册成功','data'=>array());
        }
    }


    /**
     * 验证重置密码
     * @param $param
     * @return array
     */
    public function check_reset_pwd($param){

        //验证手机号
        if (is_phone($param['mobile']) == ''){
            return array('code'=>1001,'msg'=>'请输入正确的手机号码','data'=>array());
        }

        //是否已存在
        $mobile = $param['mobile'];
        $user_info = $this->get_user_info(array('mobile'=>$mobile));
        if (!$user_info){
            return array('code'=>1001,'msg'=>'该手机号还未注册','data'=>array());
        }

        //验证密码
        if (strlen($param['password']) < 6 || strlen($param['password']) > 20){
            return array('code'=>1002,'msg'=>'密码长度在6-20位之间','data'=>array());
        }
        //验证手机验证码
        if (trim($param['mobile_code']) == ''){
            return array('code'=>1003,'msg'=>'请输入手机验证码','data'=>array());
        }

        //查询验证码
        $where = array(
            'mobile'=>$mobile,
            'code'=>$param['mobile_code'],
            'code_type'=>RESET_PASSWORD
        );
        $code_info = $this->m_user_mobile_code->get_mobile_code($where);
        if ($code_info == null){
            return array('code'=>1004,'msg'=>'验证码错误','data'=>array());
        }else{
            if ($code_info['expire_time'] < date('Y-m-d H:i:s')){
                return array('code'=>1004,'msg'=>'验证码已过期','data'=>array());
            }
        }

        /*************************************** 验证完毕， 修改密码 *********************************************/
        $this->db->trans_begin();

        $this->db->where('mobile',$mobile)->update('m_users',array(
            'password'=>md5($param['password'])
        ));

        //同时修改商城的用户密码
        $this->mall = $this->load->database('ccs168_mall_new', TRUE);
        $this->mall->where('loginName',$mobile)->update('wst_users',array(
            'loginPwd'=>md5($param['password'])
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('code'=>102,'msg'=>'网络开小差了,请重试一次','data'=>array());
        } else {
            $this->db->trans_commit();
            return array('code'=>0,'msg'=>'操作成功，请登录','data'=>array());
        }
    }

    /** 返回同步登陆链接(如果商品ID不为空，说明要重定向到商城的商品页)
     * @param $uid
     * @param $goods_id
     * @return string
     */
    public function get_sync_link($uid,$goods_id = ''){
        ini_set("display_errors", 0);
        error_reporting(E_ALL ^ E_NOTICE);
        error_reporting(E_ALL ^ E_WARNING);

        $user_info = $this->get_user_info(array('uid'=>$uid));

        //同步参数
        $mall_param = config_item('mall_param');
        $secret_key = $mall_param['secret_key'];

        $wx_info = json_decode($user_info['wx_info']);

        $arr['mobile']= $user_info['mobile'];
        $arr['password'] = $user_info['password'];
        $arr['openid'] = isset($wx_info->openid) ? $wx_info->openid : '';
        $arr['create_time'] = time();
        $string = json_encode($arr);


        if(config_item('is_new_mall')){
            $domain = config_item('new_mall_site');
            $key = $this->crypt->lock_url($string,$secret_key);
            $hash_string = base64_encode($key);

            $sync_link = 'http://'.$domain.'/mobile/users/api_login?t='.$hash_string;
            if ($goods_id != ''){
                $sync_link = 'http://'.$domain.'/mobile/users/api_login?t='.$hash_string."&redirect=http://www.css.cn/products_{$goods_id}.html";
            }
        }else{
            $domain = config_item('old_mall_site');
            $key = $this->crypt->encode($string,$secret_key,5);
            $hash_string = base64_encode($key);
            $sync_link = 'http://'.$domain.'/simple/hm_baidu_login?t='.$hash_string;
            if ($goods_id != ''){
                $sync_link = 'http://'.$domain.'/simple/hm_baidu_login?t='.$hash_string."&redirect=http://mall.591zzx.com/products_{$goods_id}.html";
            }
        }
        //http://testmall.ccs168.cn/mobile/users/api_login?t=YTU1ZGM0OWVmZE5ETTNORHM3T3owNk9UUm1NbXN3WkRJNk9UQTFOVHBuTkRKbU1UQnBabU56SkdSbloybGtaU002SVRRek5qQTFORFl4TURJMElpd2xjR0Z4YzNKcmNtUWpPaVUvUG1FL1BUSTBPemd4TkdWaU1EZGxPVFZqTlRJNWJHUTFZak0xTmpFME1pVXNJbXB5YldKOFlGWjBiMjFsSnpreE16RTBNRG93T0RJemVn
        return $sync_link;
    }

    /**
     * 获取目前团队的特定等级的人数
     */
    public function get_level_count($uid,$find_level){
        $sql = "select count(0) as `count` from m_user_all_parents mp,m_users m where m.uid=mp.uid and mp.parent_id={$uid} and m.`level`>={$find_level}";
        $team_level_count = $this->db->query($sql)->row_array();
        $childCount = isset($team_level_count['count']) ? $team_level_count['count'] : 0;
        return $childCount;
    }

    /**
     * 当A会员的等级变动，需要检测A的整个上线推荐树，是否满足升级
     */
    public function check_parent_upgrade_level($uid){
        $pidRes = $this->db->select('parent_id')->where('uid',$uid)->get('m_user_all_parents')->result_array();
        foreach ($pidRes as $val){
            $this->m_order->upgradeLevel($val['parent_id'],date('Y-m-d H:i:s'));
        }
    }
}