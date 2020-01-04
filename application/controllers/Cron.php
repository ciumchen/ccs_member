<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 计划任务类
 *  php index.php cron 方法名 参数
 */
class Cron extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        ignore_user_abort();
        set_time_limit(0);

        /**  强制cron.php只能使用命令行(CLI) */
        if (!$this->input->is_cli_request()) {
            echo 'Please run this script in CLI.';
            exit;
        }
    }

    /* 更换上线，更新关系树 */
    public function updateParent()
    {
        $uid = 3845;
        $parent_id = 3885;

        //删除以前的关系树
        $this->db->where('uid',$uid)->delete('m_user_all_parents');

        /**
         * 统计会员所有的推荐人
         */
        $pidRes = $this->db->select('parent_id,level')->where('uid',$parent_id)->get('m_user_all_parents')->result_array();
        $pidArr = [];
        $pidArr[] = [
            'uid'=>$uid,
            'parent_id'=>$parent_id,
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
        //更新现有的关系树
        $this->db->insert_batch('m_user_all_parents',$pidArr);
        //更新上线
        $this->db->where('uid',$uid)->update('m_users',['parent_id'=>$parent_id]);

        //$uid下线的关系树都需要变更
        $child_uids =$this->db->where('parent_id',$uid)->get('m_user_all_parents')->result_array();
        foreach ($child_uids as $child_uid){
            //删除以前的关系树
            $this->db->where('uid',$child_uid['uid'])->delete('m_user_all_parents');

            $pid = $this->db->where('uid',$child_uid['uid'])->select('parent_id')->get('m_users')->row_array();
            $parent_id = $pid['parent_id'];


            /**
             * 统计会员所有的推荐人
             */
            $pidRes = $this->db->select('parent_id,level')->where('uid',$parent_id)->get('m_user_all_parents')->result_array();
            $pidArr = [];
            $pidArr[] = [
                'uid'=>$uid,
                'parent_id'=>$parent_id,
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
            //更新现有的关系树
            $this->db->insert_batch('m_user_all_parents',$pidArr);
        }
    }

    /* 迁移用户 */
    public function relocate_user()
    {

        //m_users 表信息
        $user_info_list = array();

        //切换数据库
        $this->ccs168_bak = $this->load->database('ccs168_bak', TRUE);
        $old_user_list = $this->ccs168_bak->select('*')->get('sea_user')->result_array();
        foreach ($old_user_list as $key => $old_user) {
            $user_info = array();
            $user_info['uid'] = $old_user['id'];                                 //用户ID
            $user_info['mobile'] = $old_user['mobile'];                          //手机号
            $user_info['open_id'] = $old_user['open_id'];                        //微信标识
            $user_info['password'] = $old_user['password'];                      //用户密码，原库是直接MD5加密
            $user_info['token'] = md5(time() + rand(1, 9999));                      //生成token
            $user_info['username'] = $old_user['username'];                      //TODO 用户名(唯一编号，C开头的不知是否供应商)
            $user_info['pay_password'] = '';                                     //支付密码(暂无，由用户自行设置)
            $user_info['parent_id'] = $old_user['inviter_user_id'];              //推荐人ID
            $user_info['image_url'] = 'default_head_ico.jpg';

            //提现信息
            $reward_info = $this->ccs168_bak->select('shopping_reward,m_rec_reward,s_rec_reward,manage_reward,plat_reward')
                ->where('user_id', $old_user['id'])
                ->get('withdraw_log')->row_array();
            $shopping_reward = $reward_info == null ? 0 : $reward_info['shopping_reward'];         //消费奖
            $m_rec_reward = $reward_info == null ? 0 : $reward_info['m_rec_reward'];               //推荐奖
            $s_rec_reward = $reward_info == null ? 0 : $reward_info['s_rec_reward'];               //供应商奖
            $manage_reward = $reward_info == null ? 0 : $reward_info['manage_reward'];             //管理奖
            $plat_reward = $reward_info == null ? 0 : $reward_info['plat_reward'];                 //经营奖

            //已经提现的金额，累加提现金额
            $user_info['withdraw_amount'] = $shopping_reward + $m_rec_reward + $s_rec_reward + $manage_reward + $plat_reward;

            //增加一条提现记录
            if ($user_info['withdraw_amount'] > 0) {
                $this->db->insert('m_user_withdraw', array(
                    'uid' => $user_info['uid'],
                    'amount' => $user_info['withdraw_amount'],
                    'actual_amount' => $user_info['withdraw_amount'],
                    'type' => 2,
                    'status' => 2,
                    'create_time' => '2018-05-17 13:20:23'
                ));
            }

            /*************************************  用户额外信息 *******************************/
            $user_des = $this->ccs168_bak->select('*')
                ->where('user_id', $old_user['id'])
                ->get('sea_member')->row_array();

            $user_info['true_name'] = isset($user_des['true_name']) ? $user_des['true_name'] : '';       //真实姓名
            $user_info['id_card'] = isset($user_des['id_card']) ? $user_des['id_card'] : '';             //身份证号
            $user_info['province'] = isset($user_des['province']) ? $user_des['province'] : '';             //省份ID
            $user_info['city'] = isset($user_des['city']) ? $user_des['city'] : '';                        //城市ID
            $user_info['area'] = isset($user_des['area']) ? $user_des['area'] : '';                         //地区ID
            $user_info['address'] = isset($user_des['address']) ? $user_des['address'] : '';                //详细地址
            $user_info['create_time'] = $old_user['create_time'];               //创建时间

            /*************************************  获取用户已经提现的金额 *******************************/

            $user_info_list[] = $user_info;
            echo $key . "\n";
        }

        //切回数据库
        $this->db->insert_batch('m_users', $user_info_list);
    }


    /* 创建用户 */
    public function create_user()
    {


        $user_info = array();
        $user_info['mobile'] = 19918738787;                          //手机号
        $user_info['open_id'] = '';                        //微信标识
        $user_info['password'] = 'e2a5628cc69b7ab1003b8e83310e0203';                      //用户密码，原库是直接MD5加密
        $user_info['token'] = '29fb0af34ba6a45827e77c4845bb9625a4d075e3';                      //生成token
        $user_info['username'] = '';                      //TODO 用户名(唯一编号，C开头的不知是否供应商)
        $user_info['pay_password'] = '2aa64e3dd940f7f8a6425fb29db39d87b4653a80';                                     //支付密码(暂无，由用户自行设置)
        $user_info['parent_id'] = 0;              //推荐人ID
        $user_info['image_url'] = 'default_head_ico.jpg';
        $user_info['true_name'] = '张惠欣';       //真实姓名
        $user_info['create_time'] = date('Y-m-d H:i:s');               //创建时间

        $user_info = array();
        $user_info['mobile'] = 13918338717;                          //手机号
        $user_info['open_id'] = '';                        //微信标识
        $user_info['password'] = 'e2a5628cc69b7ab1003b8e83310e0203';                      //用户密码，原库是直接MD5加密
        $user_info['token'] = '29fb0af34ba6a45827e77c4845bb9625a4d075e3';                      //生成token
        $user_info['username'] = '';                      //TODO 用户名(唯一编号，C开头的不知是否供应商)
        $user_info['pay_password'] = '2aa64e3dd940f7f8a6425fb29db39d87b4653a80';                                     //支付密码(暂无，由用户自行设置)
        $user_info['parent_id'] = 0;              //推荐人ID
        $user_info['image_url'] = 'default_head_ico.jpg';
        $user_info['true_name'] = '王佳惠';       //真实姓名
        $user_info['create_time'] = date('Y-m-d H:i:s');               //创建时间

        $user_info = array();
        $user_info['mobile'] = 13618338717;                          //手机号
        $user_info['open_id'] = '';                        //微信标识
        $user_info['password'] = 'e2a5628cc69b7ab1003b8e83310e0203';                      //用户密码，原库是直接MD5加密
        $user_info['token'] = '29fb0af34ba6a45827e77c4845bb9625a4d075e3';                      //生成token
        $user_info['username'] = '';                      //TODO 用户名(唯一编号，C开头的不知是否供应商)
        $user_info['pay_password'] = '2aa64e3dd940f7f8a6425fb29db39d87b4653a80';                                     //支付密码(暂无，由用户自行设置)
        $user_info['parent_id'] = 0;              //推荐人ID
        $user_info['image_url'] = 'default_head_ico.jpg';
        $user_info['true_name'] = '王佳惠';       //真实姓名
        $user_info['create_time'] = date('Y-m-d H:i:s');               //创建时间

        $user_info_list[] = $user_info;


        //切回数据库
        $this->db->insert_batch('m_users', $user_info_list);
    }

    /**
     * 统计会员的全部推荐人
     */
    public function fixParentArr()
    {

        $total = $this->db->from('m_users')
            //->where('uid',40)
            ->count_all_results();

        $page = 1;
        $pageSize = 500; //每次500,遍历处理
        $pageCount = ceil($total / $pageSize);

        echo '共' . $pageCount . '页,' . $total . '条记录' . "\n";

        $this->db->query("TRUNCATE `m_user_all_parents`");

        while ($page <= $pageCount) {
            echo '第' . $page . '页' . "\n";

            $start = ($page - 1) * $pageSize;

            $members = $this->db->select('uid')
                //->where('uid',40)
                ->limit($pageSize, $start)
                ->get('m_users')->result_array();

            foreach ($members as $member) {
                $batch = $this->recursiveParentArr($member['uid'], $member['uid']);

                if (!$batch) continue;
                $this->db->insert_batch('m_user_all_parents', $batch);
            }
            $page++;
        }

    }


    /**
     * 递归得到所有推荐人
     */
    public function recursiveParentArr($user_id, $uid, $idArr = array(), $level = 1)
    {

        $user = $this->db->from('m_users')->select('parent_id')->where('uid', $user_id)->get()->row_array();

        if ($user['parent_id']) {
            $idArr[] = [
                'uid' => $uid,
                'parent_id' => $user['parent_id'],
                'level' => $level
            ];
            $level = $level + 1;
            $idArr = $this->recursiveParentArr($user['parent_id'], $uid, $idArr, $level);
        }

        return $idArr;

    }


    /**
     * 递归得到所有推荐人
     */
    public function recursiveParentArrNew($parent_id, $uid, $idArr = array(), $level = 1)
    {

        if($parent_id){
            $idArr[] = [
                'uid' => $uid,
                'parent_id' => $parent_id,
                'level' => $level
            ];
            $level = $level + 1;
            $user = $this->db->from('m_users')->select('parent_id')->where('uid', $parent_id)->get()->row_array();
            $idArr = $this->recursiveParentArrNew($user['parent_id'], $uid, $idArr, $level);
        }

        return $idArr;

    }

    /**
     * 新商城定时脚本：扫描订单表发送奖励
     */
    public function scanOrderNew()
    {

        $this->load->model('m_order');
        $this->ccs168_mall = $this->load->database('ccs168_mall_new', TRUE);

        /**
         *  开始时间
         */
        $beginDate = '2018-09-30 00:00:00';

        $total = $this->ccs168_mall
            ->from('wst_orders')
            ->where('payTime >=', $beginDate)
            ->where('isPay', 1)
            ->where('orderStatus >=', 0)
            ->where('is_check', 1)
            ->count_all_results();
        var_dump($this->db->last_query());

        $page = 1;
        $pageSize = 100;
        $pageCount = ceil($total / $pageSize);

        echo 'All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        while ($page <= $pageCount) {
            echo 'This ' . $page . ' Page' . "\n";

            $orders = $this->ccs168_mall->select('orderId,user_mobile')
                ->where('payTime >=', $beginDate)
                ->where('isPay', 1)
                ->where('orderStatus >=', 0)
                ->where('is_check', 1)
                ->order_by('payTime', 'ASC')
                ->limit($pageSize)
                ->get('wst_orders')
                ->result_array();

            foreach ($orders as $val) {

                $orderGoods = $this->m_order->get_mall_goods($val['orderId'], trim($val['user_mobile']));
                if ($orderGoods) {
                    foreach ($orderGoods as $key=>$order) {

                        $isDouble = $this->ccs168_mall
                            ->select('goodsId')
                            ->where('goodsId', $order['goods_id'])
                            ->where('isDouble', 1)
                            ->get('wst_goods')
                            ->row_array();

                        if ($isDouble) {
                            $orderPoint = $order['goods_point'] * 2;
                        } else {
                            $orderPoint = $order['goods_point'];
                        }
                        $orderSales = $order['goods_reward'];

                        $uid = $order['uid'];
                        $pid = $order['parent_id'];
                        $order_id = $order['order_id'];
                        $goods_id = $order['goods_id'];

//                        if(isActivityIng($order['pay_time'])){
//                            //11月份买1006商品的酒升级到V1，送398优惠券
//                            if ($goods_id == 1006 && $order['user_level'] == 0) {
//
//                                $this->db->insert('m_mall_coupon',[
//                                    'uid'=>$uid,
//                                    'coupon_id'=>37,
//                                ]);
//
//                                $this->m_goods->addCouponLog($uid,37);
//                            }
//                            //11月份订单的直通车金额超过9488，送9488的优惠券
//                            if($key == 0 && isset($order['train_amount']) && $order['train_amount'] >= 9488){
//                                $this->m_goods->addCouponLog($uid,39);
//                            }
//                        }

                        if(isActivityIngNew($order['pay_time'])){
                            //V3直通车金额超过9488
                            if($key == 0 && isset($order['train_amount']) && $order['train_amount'] >= 9488){
                                $this->m_goods->addCouponLog($uid,39);
                            }
                        }

                        $month = date('Y-m', strtotime($order['pay_time']));
                        //购买了帮扶计划
                        if($goods_id == 2746){
                            $this->m_order->create_help_plan($uid,$pid,$month,$order['user_level'],$order['pay_time'],$order_id);
                            continue;
                        }
                        //激活礼包
                        if(in_array($goods_id,[3600,3757,3665,3768,3782,3802])){
                            $this->m_order->create_active_plan($uid,trim($val['user_mobile']),$order['pay_time'],$order_id,$goods_id);
                            continue;
                        }


                        $sales = $orderSales;
                        $point = $orderPoint;

                        /**
                         * 统计需要包含本次订单销售月销售额 todo
                         */
                        $sale_amount = $this->db->select('sale_amount')
                            ->where('uid', $uid)
                            ->where('year_month', $month)
                            ->get('m_stat_sale_month')
                            ->row_array();
                        if ($sale_amount) {
                            $monthSales = $sale_amount['sale_amount'] + $order['goods_price'];
                        } else {
                            $monthSales = $order['goods_price'];
                        }

                        /**
                         * 月初等级
                         */
                        $monthLevel = 0;
                        $monthLevelRow = $this->db->select('level')
                            ->where('uid', $uid)->where('year_month', $month)
                            ->get('m_stat_level_month')->row_array();
                        if ($monthLevelRow && $monthLevelRow['level'] > 0) {
                            $monthLevel = $monthLevelRow['level'];
                            echo 'Month Begin Level:' . $monthLevel . "\r\n";
                        }

                        $this->db->trans_begin();
                        /**
                         * 流程上一定要记得先传递积分，再算实时等级，再按等级提成
                         */

                        $this->m_order->pointSales($uid, $point, $order_id, $goods_id, $order['pay_time']);

                        /**
                         * 个人消费奖
                         */
                        $this->m_order->shoppingSales($uid, $sales, $order_id, $goods_id, $monthSales, $monthLevel, $order['pay_time'], $order['goods_price']);
                        /**
                         * 直接推荐奖
                         */
                        $this->m_order->parentSales($uid, $pid, $sales, $order_id, $goods_id, $order['pay_time']);

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            continue;
                        } else {
                            $this->db->trans_commit();
                        }
                    }
                    //更新已扫描
                    $res = $this->ccs168_mall->where('orderId', $val['orderId'])->update('wst_orders', ['is_check' => 2]);
                } else {
                    log_message('error', '订单匹配会员ID失败：订单ID：' . $val['orderId'] . '手机号：' . $val['user_mobile'] . "\r\n");
                    echo '订单ID：' . $val['orderId'] . '手机号：' . $val['user_mobile'] . "\r\n";
                }
            }
            $page++;
        }
    }

    /**
     * 发放奖金：待入账到已入账
     */
    public function noToyes()
    {

        $flagTime = date('Y-m-d H:i:s', strtotime('-7 days'));

        /**
         * 排除帮扶计划-待入账的记录
         */
        $total = $this->db->from('m_user_change_reward')
            ->where('create_time <', $flagTime)
            ->where('status', 0)
            ->where('goods_id <>', 2746)
            ->count_all_results();

        $page = 1;
        $pageSize = 500;
        $pageCount = ceil($total / $pageSize);

        echo 'All ' . $pageCount . ' Page,' . $total . ' Rows' . "\n";

        $fieldMap = [
            1 => 'shopping_reward',
            2 => 'm_rec_reward',
            3 => 's_rec_reward',
            4 => 'manage_reward',
            5 => 'plat_reward',
        ];

        while ($page <= $pageCount) {
            echo 'This ' . $page . ' Page' . "\n";

            /**
             * 排除帮扶计划-待入账的记录
             */
            $rewards = $this->db
                ->where('status', 0)
                ->where('create_time <', $flagTime)
                ->where('goods_id <>', 2746)
                ->limit($pageSize)
                ->get('m_user_change_reward')->result_array();

            $this->db->trans_begin();

            foreach ($rewards as $reward) {
                /**
                 * 1.增加月奖励额，2，增加奖金池:暂时没有奖金池，3，status变成1 已入账。4，增加一条已入账消息提醒
                 */
                $year_month = date('Y-m', strtotime($reward['create_time']));
                $monthReward = $this->db
                    ->where('uid', $reward['uid'])
                    ->where('at_date', $year_month)
                    ->get('m_user_month_reward')
                    ->row_array();

                $field = $fieldMap[$reward['type']];
                if ($monthReward) {
                    $this->db
                        ->where('uid', $reward['uid'])
                        ->where('at_date', $year_month)
                        ->set($field, $field . '+' . $reward['amount'], FALSE)
                        ->update('m_user_month_reward');
                } else {

                    $this->db->insert('m_user_month_reward',
                        ['uid' => $reward['uid'], $field => $reward['amount'], 'at_date' => $year_month]);
                }

                $this->db->where('id', $reward['id'])->update('m_user_change_reward', ['status' => 1]);

                //消息提醒
                $content_type = config_item('reward_type')[$reward['type']];
                $this->db->where('uid', $reward['uid'])->update('m_users', ['message_type' => 1]);
                $this->db->insert('m_message', array(
                    'uid' => $reward['uid'],
                    'type' => 1,
                    'param1' => $reward['amount'],
                    'param2' => $reward['type'],
                    'content' => "你有一笔奖金已入账,来自{$content_type},请查收!"
                ));
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            $page++;
        }
    }

    //5、6月份奖金出来后的迁移
    public function insert()
    {

        //新规则产生的奖金
        $new_reward_list = $this->db->where('at_date', '2018-05')->get('m_user_month_reward')->result_array();

        foreach ($new_reward_list as $new_reward) {

            //旧规则产生的奖金
            $old_reward = $this->db->select('user_id,shopping_reward,m_rec_reward,s_rec_reward,manage_reward,plat_reward')
                ->where('user_id', $new_reward['uid'])
                ->get('withdraw_log')->row_array();

            //如果旧规则没有奖金数据，则采用新规则数据
            if ($old_reward == null) {
                log_message('error', 'ID:' . $new_reward['uid'] . "5月份没有拿到旧规则奖金");
                continue;
            }

            //旧规则奖金总和
            $old_total = $old_reward['shopping_reward'] +
                $old_reward['m_rec_reward'] +
                $old_reward['s_rec_reward'] +
                $old_reward['manage_reward'] +
                $old_reward['plat_reward'];

            $new_total = $new_reward['shopping_reward'] +
                $new_reward['m_rec_reward'] +
                $new_reward['s_rec_reward'] +
                $new_reward['manage_reward'] +
                $new_reward['plat_reward'];

            //如果新规则比旧规则奖励多，则采用新规则数据
            if ($new_total > $old_total) {
                log_message('error', 'ID:' . $new_reward['uid'] . "新规则{$new_total},旧规则{$old_total},采用新规则");
                continue;
            }

            //新规则比旧规则少,则更新为旧规则的奖金
            if ($new_total < $old_total) {
                log_message('error', 'ID:' . $new_reward['uid'] . "新规则{$new_total},旧规则{$old_total},采用旧规则");
                $this->db->where('uid', $new_reward['uid'])->where('at_date', '2018-05')->update('m_user_month_reward', array(
                    'shopping_reward' => $old_reward['shopping_reward'],
                    'm_rec_reward' => $old_reward['m_rec_reward'],
                    's_rec_reward' => $old_reward['s_rec_reward'],
                    'manage_reward' => $old_reward['manage_reward'],
                    'plat_reward' => $old_reward['plat_reward'],
                ));
            }
        }
    }

    public function get_all_allow_withdraw()
    {

        $total = 0;
        $list = $this->db->select('uid,mobile,true_name,withdraw_amount')->get('m_users')->result_array();
        foreach ($list as $item) {
            //累计奖金
            $total_reward = $this->m_user_month_reward->get_user_all_reward_total($item['uid']);

            //可提现金额(奖金总额 - 已提现金额)
            $allow_withdraw_amount = $total_reward - $item['withdraw_amount'];
            if ($allow_withdraw_amount > 0) {
                $this->db->insert('temp', array(
                    'uid' => $item['uid'],
                    'mobile' => $item['mobile'],
                    'amount' => $allow_withdraw_amount
                ));
                $total += $allow_withdraw_amount;
            }
        }
        var_dump($total);
    }

    //获取商城上注册了，共聚会联没注册的用户
    public function filter_user()
    {
        $this->mall = $this->load->database('ccs168_mall', TRUE);
        $list = $this->mall->select('mobile')->where('time >', '2018-05-08')->get('i_member')->result_array();
        $not_reg_list = array();
        foreach ($list as $key => $value) {
            $user_info = $this->db->select('uid,mobile')->where('mobile', $value['mobile'])->get('m_users')->row_array();
            if ($user_info == null) {
                if (!in_array($value['mobile'], $not_reg_list)) {
                    $not_reg_list[] = $value['mobile'];
                    echo $value['mobile'] . '<br>';
                }
            }
        }
    }

    public function get_order_list()
    {
        $list = $this->m_order->get_order_list_new(26631, '13506121081');
        var_dump($list);
    }


    public function calcManageReward()
    {
        //开始事务
        $this->db->trans_begin();

        $year_month = date('Y-m',strtotime('-1 month'));
        var_dump($year_month);
        $this->m_order->manageReward($year_month);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function grantManageReward()
    {
        //开始事务
        $this->db->trans_begin();

        $year_month = date('Y-m',strtotime('-1 month'));
        var_dump($year_month);
        $this->m_order->grantManageReward($year_month);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 统计帮扶计划的开拓管理奖励:上线奖励
     * 帮扶计划的开拓管理奖单独发放：每人按2400的利润贡献，第一代拿50%（1200/人），第二代拿25%（600/人），第三代拿12.5%（300/人）；
     * 第四代拿6.25%（150/人）；所有的开拓管理奖都为待入账模式，这个月8号发放三个月的开拓管理奖（分三次变成已入账60% 20% 20%）
     */
    public function calcManageRewardHelpPlan()
    {
        //开始事务
        $this->db->trans_begin();

        $year_month = date('Y-m',strtotime('-1 month'));
        var_dump($year_month).'test';

        $sql = "SELECT uid from m_user_help_plan where 1 and `year_month`='$year_month' and count=1 and is_buy=1 and status=1 and uid<>1880";
        $rows = $this->db->query($sql)->result_array();
        $percent = [
          1=>0.5,
          2=>0.25,
          3=>0.125,
          4=>0.0625,
        ];
        $amount = 2400;

        var_dump(count($rows));

        foreach ($rows as $row){
            /**
             * 会员的推荐人 推四层。
             */
            $parents = $this->db
                ->select('parent_id,level')
                ->where('uid',$row['uid'])
                ->where('level <=',4)
                ->get('m_user_all_parents')
                ->result_array();
            foreach ($parents as $ps){
                $per = $percent[$ps['level']];
                $reward = $per * $amount;

                /**
                 * 判断上线是否完成了$year_month低消，当前月初等级就是$year_month的等级
                 */
                $ym = date('Y-m');
                $level = $this->db->select('level')->where('uid',$ps['parent_id'])->where('year_month',$ym)->get('m_stat_level_month')->row_array();

                if(!$level || $level['level'] < 1) continue;

                $sale = $this->db->select('sale_amount')->where('uid',$ps['parent_id'])->where('year_month',$year_month)->get('m_stat_sale_month')->row_array();

                if($sale['sale_amount'] >= ($level['level']*300)){
                    $this->m_base->I('m_user_help_reward',[
                        'uid'=>$ps['parent_id'],
                        'year_month'=>$year_month,
                        'amount'=>$reward,
                        'child_uid'=>$row['uid'],
                        'level'=>$ps['level'],
                        'status'=>0,
                    ]);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 发放帮扶计划的开拓管理奖励:上线奖励
     */
    public function grantManageRewardHelpPlan()
    {

        $total = $this->db->from('m_user_help_reward')
            ->where('status', 0)
            ->count_all_results();

        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();
        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db
                ->where('status', 0)
                ->limit($pageSize)
                ->get('m_user_help_reward')
                ->result_array();

            foreach ($rows as $row) {

                for ($i=1;$i<=3;$i++){
                    $end = date('Y-m-t 23:59:59', strtotime('+' . ($i - 1) . 'month', strtotime($row['year_month'])));
                    $data = [
                        'uid'=>$row['uid'],
                        'type'=>4,
                        'child_uid'=>$row['child_uid'],
                        'remark'=>'开拓管理奖-帮扶计划',
                        'create_time'=>$end
                    ];
                    if($i==1){
                        $m = $row['amount']*0.6;
                    }else{
                        $m = $row['amount']*0.2;
                    }
                    $data['amount'] = $m;
                    /**
                     * 金额变动
                     */
                    $this->m_base->I('m_user_change_reward',$data);
                }

                /**
                 * 4.更新状态
                 */
                $this->db->where('id', $row['id'])
                    ->update('m_user_help_reward', ['status' => 1]);

            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function calcBusinessReward()
    {
        $year_month = '2018-10';
        $this->m_order->calcBusinessRewardNew($year_month);
    }

    public function grantBusinessReward6()
    {
        $year_month = '2018-06';
        $this->m_order->grantBusinessReward($year_month);
    }

    /**
     * 统计用户月初等级
     * @param $year_month
     */
    public function statLevelMonth($year_month = '')
    {
        if (empty($year_month)) {
            $year_month = date('Y-m');
        }
        $this->db->query("replace into m_stat_level_month (`year_month`,`uid`,`level`) select '{$year_month}',uid,level from m_users where level>0");
    }

    /**
     * 等级和积分 ：统计满足最低消费的会员。每天凌晨10分
     */
    public function statMiniSaleDaily()
    {

        $year_month = date('Y-m');
        $day = date('Ymd');
        $sql = "insert into m_gold_mini_sale_day (day,uid,level,point) select {$day},m.uid,m.level,m.point from m_users m,m_stat_sale_month ms 
                where m.level>0 and ms.year_month='{$year_month}' and ms.uid=m.uid and ms.sale_amount>=300*m.level";
        $this->db->query($sql);
    }

    /**
     * 等级和积分：发放满足最低消费的金价 每天凌晨30分钟 必须确认今日牌价后
     */
    public function grantMiniSaleGoldPrice()
    {

        $total = $this->db->from('m_gold_mini_sale_day')
            ->where('status', 0)
            ->count_all_results();

        $page = 1;
        $pageSize = 500;
        $pageCount = ceil($total / $pageSize);

        echo 'All ' . $pageCount . ' Page,' . $total . ' Rows' . "\n";

        $levelPoint = config_item('level_point');
        $mini_sale_gold = config_item('mini_sale_gold');

        while ($page <= $pageCount) {
            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db
                ->where('status', 0)
                ->limit($pageSize)
                ->get('m_gold_mini_sale_day')->result_array();

            $this->db->trans_begin();

            foreach ($rows as $row) {

                /**
                 * V1每天赠送1元金币等价券，积分超过588的每超过1000积分额外赠送0.1元金币等价券；
                 */
                $leftPoint = $row['point'] - $levelPoint[$row['level']];

                /**
                 * todo 提醒领导是否需要设置一个上限值
                 */
                $gift_gold_price = intval($leftPoint / 1000) * 0.1;

                $gold_price = $mini_sale_gold[$row['level']];


                /**
                 * 1.金价根据今日牌价转换成金币
                 */
                $value = $gift_gold_price + $gold_price;
                $goldPrice = $this->m_common->getGoldPrice();
                $gold = $this->m_common->transferToGold($value, $goldPrice);

                /**
                 * 2.金币变动
                 */
                $this->m_base->I('m_user_change_gold', [
                    'uid' => $row['uid'],
                    'type' => 2,
                    'before_amount' => $value,
                    'gold' => $gold,
                    'gold_price' => $goldPrice,
                    'status' => 1
                ]);

                /**
                 * 3.累计金币
                 */
                $this->db->where('uid', $row['uid'])
                    ->set('gold', 'gold+' . $gold, FALSE)
                    ->update('m_users');

                /**
                 * 4.记录处理成已完成
                 */
                $this->db
                    ->where('uid', $row['uid'])
                    ->where('day', $row['day'])
                    ->update('m_gold_mini_sale_day', ['status' => 1]);

            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            $page++;
        }
    }

    /**
     * 每月1号1小时检测一遍：前提条件 公司填写了分配总数
     * 上个月经营：按团队毛利贡献值分配（每月分配总数公司决定，金币等价券只奖励给有团队的VIP会员，含自己4人及以上算是团队）。
     */
    public function grantBusinessGoldPrice()
    {

        //开始事务
        $this->db->trans_begin();

        $year_month = date('Y-m', strtotime('-1 month'));

        $total = $this->db->from('m_stat_team_profit')
            ->where('year_month', $year_month)
            ->where('gold >', 0)
            ->where('is_gold_grant', 0)
            ->count_all_results();

        $end = date('Y-m-t 23:59:59', strtotime($year_month));

        $page = 1;
        $pageSize = 100;
        $pageCount = ceil($total / $pageSize);

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db->select('uid,gold')
                ->where('year_month', $year_month)
                ->where('gold >', 0)
                ->where('is_gold_grant', 0)
                ->limit($pageSize)
                ->get('m_stat_team_profit')->result_array();

            foreach ($rows as $row) {

                /**
                 * 1.金价根据今日牌价转换成金币
                 */
                $goldPrice = $this->m_common->getGoldPrice();
                $gold = $this->m_common->transferToGold($row['gold'], $goldPrice);

                /**
                 * 2.金币变动
                 */
                $this->m_base->I('m_user_change_gold', [
                    'uid' => $row['uid'],
                    'type' => 4,
                    'before_amount' => $row['gold'],
                    'gold' => $gold,
                    'gold_price' => $goldPrice,
                    'status' => 1,
                    'create_time' => $end
                ]);

                /**
                 * 3.累计金币,升级等级
                 */
                $this->db->where('uid', $row['uid'])
                    ->set('gold', 'gold+' . $gold, FALSE)
                    ->update('m_users');

                /**
                 * 4.更新已发放金额
                 */
                $this->db->where('uid', $row['uid'])
                    ->where('year_month', $year_month)
                    ->update('m_stat_team_profit', ['is_gold_grant' => 1]);
            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }

    //导出臻之选所有用户信息
    public function excelAll()
    {
        $this->mall = $this->load->database('ccs168_mall', true);
        $sql = '';
        $sql .= "select u.id,m.true_name,m.mobile,m.area,m.contact_addr,m.qq,m.last_login";
        $sql .= " FROM i_user AS u,i_member AS m";
        $sql .= " WHERE u.id = m.user_id";

        $new_all_user = array();
        $all_user = $this->mall->query($sql)->result_array();

        $myFile = fopen("Users.txt", "w") or die("Unable to open file!");
        foreach ($all_user as $key => $user) {
            $new_all_user[$key]['uid'] = $user['id'];
            $new_all_user[$key]['mobile'] = $user['mobile'] == null ? '未填写' : $user['mobile'];
            $new_all_user[$key]['true_name'] = $user['true_name'] == null ? '未填写' : $user['true_name'];
            $new_all_user[$key]['address'] = $this->getAddress($user['area'], $user['contact_addr']);
            $new_all_user[$key]['qq'] = $user['qq'] == null ? '未填写' : $user['qq'];
            $new_all_user[$key]['last_login'] = $user['last_login'] == null ? "没有记录" : $user['last_login'];
            $info = $new_all_user[$key]['uid'] . " | " . $new_all_user[$key]['mobile'] . " | " . $new_all_user[$key]['true_name'] . " | " . $new_all_user[$key]['address'] . " | " . $new_all_user[$key]['qq'] . " | " . $new_all_user[$key]['last_login'] . "\n";
            fwrite($myFile, $info);
            echo $key;
        }
    }

    public function exportSale()
    {

        #11月份的月初等级=10月份的等级 ，10月份的消费
        $sql = "SELECT m.uid,m.`level`,ms.sale_amount
FROM m_stat_level_month m,m_stat_sale_month ms
WHERE m.level>0 AND ms.year_month='2019-04' AND m.year_month='2019-05' AND ms.uid=m.uid AND ms.sale_amount>=300*m.level;";
        $logs = $this->db->query($sql)->result_array();

        $filename = "2019-04月份低消数据.csv";
        $fp = fopen($filename, 'a');
        $header = array(
            mb_convert_encoding('会员ID', "GBK", "UTF-8"),
            mb_convert_encoding('等级', "GBK", "UTF-8"),
            mb_convert_encoding('销售额', "GBK", "UTF-8"),
            mb_convert_encoding('奖金', "GBK", "UTF-8"),
            mb_convert_encoding('金币', "GBK", "UTF-8"),
            mb_convert_encoding('开拓管理奖', "GBK", "UTF-8"),
            mb_convert_encoding('大数据等价券', "GBK", "UTF-8"),
            mb_convert_encoding('大数据金币', "GBK", "UTF-8"),
        );
        fputcsv($fp, $header);
        foreach ($logs as $log) {
            $sql = "SELECT sum(gold) as total_gold from m_user_change_gold where create_time>='2019-04-01' and create_time<='2019-04-30 23:59:59'
and uid={$log['uid']} and gold>0";
            $row = $this->db->query($sql)->row_array();
            $gold = $row['total_gold'] ? $row['total_gold'] : 0;

            $sql = "SELECT sum(amount) as amount from m_user_change_reward where create_time>='2019-04-01' and create_time<='2019-04-30 23:59:59'
and uid={$log['uid']} and type!=8 and goods_id<>2746";
            $row = $this->db->query($sql)->row_array();
            $amount = $row['amount'] ? $row['amount'] : 0;

            $sql = "SELECT reward from m_stat_team_profit where `year_month`='2019-04' and uid={$log['uid']}";
            $row = $this->db->query($sql)->row_array();
            $reward = $row['reward'] ? $row['reward'] : 0;

            $data_gold = 0;
            if($log['level']  == 1){
                $data_gold = 20;
            }elseif($log['level']  == 2){
                $data_gold = 40;
            }elseif($log['level']  == 3){
                $data_gold = 60;
            }elseif($log['level']  == 4){
                $data_gold = 80;
            }elseif($log['level']  == 5){
                $data_gold = 100;
            }elseif($log['level']  == 6){
                $data_gold = 120;
            }
            if($reward > 0){
                $data_gold = $data_gold*2;
            }

            $data_gold = $data_gold * 0.6;
            /**
             * 1.金价根据今日牌价转换成金币
             */
            $goldPrice = $this->m_common->getGoldPrice();
            $new_gold = $this->m_common->transferToGold($data_gold, $goldPrice);

            $body = [
                mb_convert_encoding($log['uid'], "GBK", "UTF-8"),
                mb_convert_encoding('V' . $log['level'], "GBK", "UTF-8"),
                mb_convert_encoding($log['sale_amount'], "GBK", "UTF-8"),
                mb_convert_encoding($amount, "GBK", "UTF-8"),
                mb_convert_encoding($gold, "GBK", "UTF-8"),
                mb_convert_encoding($reward, "GBK", "UTF-8"),
                mb_convert_encoding($data_gold, "GBK", "UTF-8"),
                mb_convert_encoding($new_gold, "GBK", "UTF-8"),
            ];

            fputcsv($fp, $body);
        }
        fclose($fp);
        exit;
    }

    /**
     * 修复878产品双倍积分问题
     */
    public function fixProductPoint()
    {
        /**
         * 积分是9488
         */
        $this->m_order->pointSales(3635, 9488, '100045934', 2746, '2018-12-28 16:15:30');
        $this->m_order->upgradeLevel(3635,'2018-12-28 16:15:30',true);
    }

    /* 定时扫描商城订单 */
    public function order_message()
    {
        $this->mall = $this->load->database('ccs168_mall', true);

        $list = $this->mall->select('id,user_mobile,status,pay_status,distribution_status,is_send_message,send_time')
            ->where('is_send_message <', 10)
            ->where('pay_status', 1)
            ->get('i_order')->result_array();

        foreach ($list as $key => $item) {

            //获取该订单用户对应的会员,找不到则直接修改订单状态，不再扫描
            $mobile = $item['user_mobile'];
            $user = $this->db->select('uid,mobile')->where('mobile', $mobile)->get('m_users')->row_array();
            if ($user == null) {
                $this->mall->where('id', $item['id'])->update('i_order', ['is_send_message' => 10]);
                continue;
            }

            //已经发货的订单商品
            $order_goods = $this->mall->select('goods_array')
                ->where('order_id', $item['id'])
                ->where('is_send', 1)
                ->get('i_order_goods')->result_array();
            if ($order_goods == array()) {
                continue;
            }

            //组合商品名
            $goods_name_arr = [];
            foreach ($order_goods as $goods) {
                $goods_attr = json_decode($goods['goods_array'], true);
                $goods_name_arr[] = $goods_attr['name'];
            }
            $goods_name_arr = implode('<br>', $goods_name_arr);

            //还未扫描过的订单,是否已经发货
            if ($item['is_send_message'] == 0) {
                if ($item['distribution_status'] == 1 || $item['distribution_status'] == 2) {
                    $this->db->where('uid', $user['uid'])->update('m_users', ['message_type' => 3]);
                    $this->db->insert('m_message', array(
                        'uid' => $user['uid'],
                        'type' => 3,
                        'param1' => $item['id'],
                        'param2' => "发货时间：" . date('Y-m-d H:i:s'),
                        'content' => "订单：<label>{$goods_name_arr}</label>发货了!"
                    ));
                    $this->mall->where('id', $item['id'])->update('i_order', ['is_send_message' => 1]);
                }
            }

            //已检测过是否发货，这个环节检测是否已经签收
            if ($item['is_send_message'] == 1) {
                if ($item['status'] == 5) {
                    $this->db->where('uid', $user['uid'])->update('m_users', ['message_type' => 3]);
                    $this->db->insert('m_message', array(
                        'uid' => $user['uid'],
                        'type' => 3,
                        'param1' => $item['id'],
                        'param2' => "签收时间：" . date('Y-m-d H:i:s'),
                        'content' => "订单：<label>{$goods_name_arr}</label>已签收!"
                    ));
                    $this->mall->where('id', $item['id'])->update('i_order', ['is_send_message' => 10]);
                }
            }
        }
    }

    /**
     * 前期会员金币的发放
     */
    public function initGold()
    {

        $total = $this->db->from('m_gold_init')
            ->where('status', 0)
            ->count_all_results();

        $page = 1;
        $pageSize = 100;
        $pageCount = ceil($total / $pageSize);

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db
                ->where('status', 0)
                ->limit($pageSize)
                ->get('m_gold_init')->result_array();

            foreach ($rows as $row) {

                /**
                 * 1.金价根据今日牌价转换成金币
                 */
                $goldPrice = 1;
                $gold = $this->m_common->transferToGold($row['gold'], $goldPrice);

                /**
                 * 2.金币变动
                 */
                $this->m_base->I('m_user_change_gold', [
                    'uid' => $row['uid'],
                    'type' => $row['type'],
                    'before_amount' => $row['gold'],
                    'gold' => $gold,
                    'gold_price' => $goldPrice,
                    'status' => 1,
                ]);

                /**
                 * 3.累计金币,升级等级
                 */
                $this->db->where('uid', $row['uid'])
                    ->set('gold', 'gold+' . $gold, FALSE)
                    ->update('m_users');

                /**
                 * 4.更新已发放金额
                 */
                $this->db->where('id', $row['id'])
                    ->update('m_gold_init', ['status' => 1]);
            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 设置商城的销量
     */
    public function setGoodsSales()
    {
        //$this->ccs168_mall = $this->load->database('ccs168_mall_new', TRUE);

//        $rows = $this->ccs168_mall->select('goodsId')
//            ->get('wst_goods')->result_array();
//        foreach ($rows as $row){
//            $saleNum = rand(49,1796);
//            $this->ccs168_mall
//                ->where('goodsId',$row['goodsId'])->update('wst_goods',['saleNum'=>$saleNum]);
//        }


//        $rows = $this->ccs168_mall->select('userId,userPhone')->where('userPhone <> ""')
//            ->get('wst_users')->result_array();
//        var_dump(count($rows));
//        foreach ($rows as $row){
//            $res = $this->db->select('uid,wx_info')->where('mobile', $row['userPhone'])->get('m_users')->row_array();
//            if($res && $res['wx_info']){
//                $wx_info = json_decode($res['wx_info']);
//                if(isset($wx_info->openid)){
//                    $this->ccs168_mall->where('userId', $row['userId'])->update('wst_users', ['wxOpenId' => $wx_info->openid]);
//                }
//            }
//        }

//        $orderMap = [
//            100004391 => 704,
//            100004494 => 528,
//            100005964 => 704,
//            100009276 => 352,
//            100011030 => 176,
//            100011575 => 176,
//            100014202 => 704,
//            100014224 => 352,
//            100014235 => 1056,
//            100014342 => 528,
//            100014353 => 704,
//        ];
//        $orders = $this->db->query("select * from m_user_change_reward where order_id in(
//100004391,
//100004494,
//100005964,
//100009276,
//100011030,
//100011575,
//100014202,
//100014224,
//100014235,
//100014342,
//100014353);")->result_array();
//        $fieldMap = [
//            1 => 'shopping_reward',
//            2 => 'm_rec_reward',
//            3 => 's_rec_reward',
//            4 => 'manage_reward',
//            5 => 'plat_reward',
//        ];
//        foreach ($orders as $order) {
//
//            $amount = $orderMap[$order['order_id']];
//            $reward = price_format($order['percent'] * $amount);
//
//            /**
//             * 已入账逻辑
//             */
//            $year_month = date('Y-m', strtotime($order['create_time']));
//            if ($order['status'] == 1) {
//
//                /**
//                 * 1.入账 2.修改奖金的金额
//                 */
//                $monthReward = $this->db
//                    ->where('uid', $order['uid'])
//                    ->where('at_date', $year_month)
//                    ->get('m_user_month_reward')
//                    ->row_array();
//
//                $field = $fieldMap[$order['type']];
//                if ($monthReward) {
//                    $this->db
//                        ->where('uid', $order['uid'])
//                        ->where('at_date', $year_month)
//                        ->set($field, $field . '+' . $reward, FALSE)
//                        ->update('m_user_month_reward');
//                } else {
//
//                    $this->db->insert('m_user_month_reward',
//                        ['uid' => $order['uid'], $field => $reward, 'at_date' => $year_month]);
//                }
//
//            }
//
//            if ($order['type'] == 1){
//                //3，月消费额，利润额
//                $sale_month_row = $this->db
//                    ->where('uid', $order['uid'])
//                    ->where('year_month', $year_month)
//                    ->get('m_stat_sale_month')
//                    ->row_array();
//                if ($sale_month_row) {
//                    $this->db
//                        ->where('uid',  $order['uid'])
//                        ->where('year_month', $year_month)
//                        ->set('sale_profit', 'sale_profit+' . $amount, FALSE)
//                        ->update('m_stat_sale_month');
//                } else {
//
//                    $this->db->insert('m_stat_sale_month', [
//                        'uid' =>  $order['uid'],
//                        'sale_profit' => $amount,
//                        'year_month' => $year_month
//                    ]);
//                }
//            }
//
//            $this->db->where('id', $order['id'])->set('amount', 'amount+' . $reward, FALSE)->update('m_user_change_reward');
//        }

//        $sql = "select * from m_user_change_reward where  1 and type = 7 and order_id >= 10000001 and child_uid=0 and create_time<='2018-10-23';";
//        $orders = $this->db->query($sql)->result_array();
//        $this->mall = $this->load->database('ccs168_mall_new', TRUE);
//        foreach ($orders as $reward){
//
//            //获取订单商品的总价和利润
//            $orderGoods = $this->mall->from('wst_order_goods wog')->select('wog.goodsId,wog.goodsPrice,wog.goodsNum,wog.goodsPoint')
//                ->join('wst_orders wo','wo.orderId=wog.orderId','left')
//                ->where('wo.orderNo',$reward['order_id'])
//                ->where('wog.goodsId',$reward['goods_id'])
//                ->get()->row_array();
//            $year_month =  date('Y-m',strtotime($reward['create_time']));
//            $goodsPrice = $orderGoods['goodsPrice']*$orderGoods['goodsNum'];
//            $sales  = $orderGoods['goodsPoint'];
//            /**
//             * 特定酒水利润不是积分，而是176 by john 2018-10-24
//             */
//            if($orderGoods['goodsId'] == 1006){
//                $sales  = 176*$orderGoods['goodsNum'];
//            }
//
//            $this->db->where('uid', $reward['uid'])
//                ->set('shopping_amount', 'shopping_amount-' . $goodsPrice, FALSE)
//                ->update('m_users');
//
//            $this->db
//                ->where('uid',$reward['uid'])
//                ->where('year_month',$year_month)
//                ->set('sale_amount','sale_amount-'.$goodsPrice,FALSE)
//                ->set('sale_profit','sale_profit-'.$sales,FALSE)
//                ->update('m_stat_sale_month');
//        }
    }

    /**
     * 修复新商城的利润，不能是双倍积分
     */
    public function fixSales()
    {

        $sql = "SELECT * from m_user_change_reward WHERE 1 and type=1 and order_id<=30000;";
        $orders = $this->db->query($sql)->result_array();

        $this->ccs168_mall = $this->load->database('ccs168_mall', TRUE);
        $returnBack = [
            '27984_744',
            '28066_1057',
            '28251_1450',
            '28278_1926',
            '28293_466',
            '28617_1751',
            '28608_1367',
            '28587_2047',
            '28770_1606',
            '28850_1971',
            '28860_1971',
            '28960_1954',
            '29007_1771',
            '28966_1226',
        ];
        //开始事务
        $this->db->trans_begin();
        foreach ($orders as $order) {
            //订单商品没有退款
            if (!isset($returnBack[$order['order_id'] . '_' . $order['goods_id']])) {
                $suppler = $this->ccs168_mall->select('seller_name')
                    ->where('order_id', $order['order_id'])
                    ->where('goods_id', $order['goods_id'])
                    ->get('i_order_goods')->row_array();

                $row = $this->db->where('supplier', $suppler['seller_name'])->get('m_old_supplier_reward')->row_array();
                if ($row) {
                    $sale = $order['amount'] / $order['percent'];
                    $reward = $sale * 0.02;
                    $this->db->where('id', $row['id'])->set('amount', 'amount+' . $reward, false)->update('m_old_supplier_reward');
                } else {
                    $this->db->insert('m_old_supplier_not', [
                        'supplier' => $suppler['seller_name'],
                        'order_id' => $order['order_id'],
                        'goods_id' => $order['goods_id'],
                    ]);
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 前期会员金币的发放
     */
    public function grantSupplierReward()
    {

        $total = $this->db->from('m_old_supplier_reward')
            ->where('status', 0)
            ->where('amount >', 0)
            ->count_all_results();

        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db
                ->where('status', 0)
                ->where('amount >', 0)
                ->limit($pageSize)
                ->get('m_old_supplier_reward')->result_array();

            foreach ($rows as $row) {

                $user = $this->db->where('mobile', $row['mobile'])->get('m_users')->row_array();

                if ($user) {
                    /**
                     * 金额变动
                     */
                    $this->m_base->I('m_user_change_reward', [
                        'uid' => $user['uid'],
                        'type' => 3,
                        'amount' => $row['amount'],
                        'order_id' => '',
                        'goods_id' => '',
                        'percent' => '',
                        'status' => 1,
                        'create_time' => date('Y-m-d'),
                        'remark' => '臻之选商城'
                    ]);

                    $year_month = date('Y-m');
                    $monthReward = $this->db
                        ->where('uid', $user['uid'])
                        ->where('at_date', $year_month)
                        ->get('m_user_month_reward')
                        ->row_array();

                    $field = 's_rec_reward';
                    if ($monthReward) {
                        $this->db
                            ->where('uid', $user['uid'])
                            ->where('at_date', $year_month)
                            ->set($field, $field . '+' . $row['amount'], FALSE)
                            ->update('m_user_month_reward');
                    } else {

                        $this->db->insert('m_user_month_reward',
                            ['uid' => $user['uid'], $field => $row['amount'], 'at_date' => $year_month]);
                    }

                    /**
                     * 4.更新已发放金额
                     */
                    $this->db->where('id', $row['id'])
                        ->update('m_old_supplier_reward', ['status' => 1]);
                }
            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 发放活动的优惠券
     */
    public function grantCoupon()
    {
        return true;
        $total = $this->db->from('m_mall_coupon')
            ->where('status', 0)
            ->count_all_results();

        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();
        $this->ccs168_mall = $this->load->database('ccs168_mall_new', TRUE);
        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db
                ->where('status', 0)
                ->limit($pageSize)
                ->get('m_mall_coupon')->result_array();

            foreach ($rows as $row) {

                $user = $this->db->where('uid', $row['uid'])->get('m_users')->row_array();

                if (!$user) continue;

                /**
                 * 通过手机号查询商城的user_id
                 */
               $mall_user = $this->ccs168_mall->select('userId')
                   ->where('userPhone',$user['mobile'])->get('wst_users')->row_array();

               if(!$mall_user) continue;

               $this->ccs168_mall->insert('wst_coupon_users',[
                   'shopId'=>1,
                   'couponId'=>$row['coupon_id'],
                   'userId'=>$mall_user['userId'],
                   'createTime'=>date('Y-m-d H:i:s'),
               ]);

                /**
                 * V3升级大礼包
                 */
               if($row['coupon_id'] == 39){
                   $this->m_order->upgradeLevel($row['uid'],date('Y-m-d H:i:s'),true);
               }

                /**
                 * 4.更新状态
                 */
                $this->db->where('id', $row['id'])
                    ->update('m_mall_coupon', ['status' => 1]);

            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }


    /**
     * V3直通车活动
     */
    public function grantV3Level()
    {

        $total = $this->db->from('m_mall_coupon')
            ->where('status', 0)
            ->count_all_results();

        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db
                ->where('status', 0)
                ->limit($pageSize)
                ->get('m_mall_coupon')->result_array();

            foreach ($rows as $row) {
                /**
                 * V3升级大礼包
                 */
                if($row['coupon_id'] == 39){
                    $this->m_order->upgradeLevel($row['uid'],date('Y-m-d H:i:s'),true);
                }

                /**
                 * 4.更新状态
                 */
                $this->db->where('id', $row['id'])
                    ->update('m_mall_coupon', ['status' => 1]);

            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 统计参加帮扶计划的会员是否完成低消,记录低消时间
     */
    public function statHelpPlanReward()
    {
        //前3期未发放的
        $total = $this->db->from('m_user_help_plan')
            ->where('status', 0)
            ->where('count <=', 3)
            ->where('is_buy',1)
            ->where('done_time','0000-00-00 00:00:00')
            ->count_all_results();

        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);
        $date = date('Y-m-d H:i:s');

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();
        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db->from('m_user_help_plan')
                ->where('status', 0)
                ->where('count <=', 3)
                ->where('is_buy',1)
                ->where('done_time','0000-00-00 00:00:00')
                ->limit($pageSize)
                ->get()
                ->result_array();

            foreach ($rows as $row) {

                $sale_amount = $this->db->select('sale_amount')
                    ->where('uid', $row['uid'])
                    ->where('year_month', $row['year_month'])
                    ->get('m_stat_sale_month')
                    ->row_array();
                var_dump($sale_amount);

                $user = $this->db->select('level')->where('uid', $row['uid'])->get('m_users')->row_array();

                //完成900低消 发放500 个人消费奖励 500分享奖励
                if ($sale_amount && $sale_amount['sale_amount'] >= ($user['level']*300) ) {
                    /**
                     * 4.更新状态
                     */
                    $this->db->where('id', $row['id'])
                        ->update('m_user_help_plan', ['done_time' => $date]);
                }
            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 完成低消7天后发放奖励。前3期
     */
    public function grantHelpPlanReward()
    {
        $flagTime = date('Y-m-d H:i:s', strtotime('-7 days'));
        //前3期未发放的
        $total = $this->db->from('m_user_help_plan')
            ->where('status', 0)
            ->where('count <=', 3)
            ->where('is_buy',1)
            ->where('done_time <>','0000-00-00 00:00:00')
            ->where('done_time <',$flagTime)
            ->count_all_results();
        var_dump($this->db->last_query());
        $page = 1;
        $pageSize = 1000;
        $pageCount = ceil($total / $pageSize);
        $date = date('Y-m-d H:i:s');
        $fieldMap = [
            1 => 'shopping_reward',
            2 => 'm_rec_reward',
            3 => 's_rec_reward',
            4 => 'manage_reward',
            5 => 'plat_reward',
        ];

        echo 'Grant All ' . $pageCount . ' page,' . $total . ' Rows' . "\n";

        //开始事务
        $this->db->trans_begin();
        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' . "\n";

            $rows = $this->db->from('m_user_help_plan')
                ->where('status', 0)
                ->where('count <=', 3)
                ->where('is_buy',1)
                ->where('done_time <>','0000-00-00 00:00:00')
                ->where('done_time <',$flagTime)
                ->limit($pageSize)
                ->get()
                ->result_array();

            foreach ($rows as $row) {

                $sale_amount = $this->db->select('sale_amount')
                    ->where('uid', $row['uid'])
                    ->where('year_month', $row['year_month'])
                    ->get('m_stat_sale_month')
                    ->row_array();
                var_dump($sale_amount);

                $user = $this->db->select('level')->where('uid', $row['uid'])->get('m_users')->row_array();

                //完成900低消 发放500 个人消费奖励 500分享奖励
                if ($sale_amount && $sale_amount['sale_amount'] >= ($user['level']*300) ) {
                    /**
                     * 个人消费奖励
                     */
                    $monthStart = date('Y-m-01',strtotime($row['year_month']));
                    $monthEnd = date('Y-m-t 23:59:59',strtotime($row['year_month']));
                    $person_shopping_log = $this->db->select('id,amount')
                        ->where('uid',$row['uid'])
                        ->where('type',1)
                        ->where('status',0)
                        ->where('goods_id',2746)
                        ->where('create_time >=',$monthStart)
                        ->where('create_time <=',$monthEnd)
                        ->where('remark','帮扶计划')
                        ->get('m_user_change_reward')
                        ->row_array();
var_dump($person_shopping_log);
                    if($person_shopping_log){

                        $this->db->where('id', $person_shopping_log['id'])->update('m_user_change_reward', ['status' => 1]);

                        $sale = $person_shopping_log['amount'];
                        $monthReward = $this->db
                            ->where('uid', $row['uid'])
                            ->where('at_date', $row['year_month'])
                            ->get('m_user_month_reward')
                            ->row_array();
                        $field = $fieldMap[1];
                        if ($monthReward) {
                            $this->db
                                ->where('uid', $row['uid'])
                                ->where('at_date', $row['year_month'])
                                ->set($field, $field . '+' . $sale, FALSE)
                                ->update('m_user_month_reward');
                        } else {

                            $this->db->insert('m_user_month_reward',
                                ['uid' => $row['uid'], $field => $sale, 'at_date' => $row['year_month']]);
                        }
                    }

                    /**
                     * 分享奖励
                     */
                    $user = $this->db->select('parent_id')->where('uid', $row['uid'])->get('m_users')->row_array();
                    if (!$user) continue;
                    $pid = $user['parent_id'];

                    $share_log = $this->db->select('id,amount')
                        ->where('uid',$pid)
                        ->where('child_uid',$row['uid'])
                        ->where('type',2)
                        ->where('status',0)
                        ->where('goods_id',2746)
                        ->where('create_time >=',$monthStart)
                        ->where('create_time <=',$monthEnd)
                        ->where('remark','帮扶计划')
                        ->get('m_user_change_reward')
                        ->row_array();
var_dump($share_log);
                    if($share_log){

                        $sale_amount = $this->db->select('sale_amount')
                            ->where('uid', $pid)
                            ->where('year_month', $row['year_month'])
                            ->get('m_stat_sale_month')
                            ->row_array();
                        var_dump($sale_amount);
                        $user = $this->db->select('level')->where('uid', $row['uid'])->get('m_users')->row_array();
                        if ($sale_amount && $sale_amount['sale_amount'] >= ($user['level']*300) ) {

                            $this->db->where('id', $share_log['id'])->update('m_user_change_reward', ['status' => 1]);

                            $share = $share_log['amount'];

                            $monthReward = $this->db
                                ->where('uid', $pid)
                                ->where('at_date', $row['year_month'])
                                ->get('m_user_month_reward')
                                ->row_array();

                            $field = $fieldMap[2];
                            if ($monthReward) {
                                $this->db
                                    ->where('uid', $pid)
                                    ->where('at_date', $row['year_month'])
                                    ->set($field, $field . '+' . $share, FALSE)
                                    ->update('m_user_month_reward');
                            } else {

                                $this->db->insert('m_user_month_reward',
                                    ['uid' => $pid, $field => $share, 'at_date' => $row['year_month']]);
                            }
                        }
                    }
                    /***
                     * 月消费额,月利润额先不计算
                     */

                    /**
                     * 4.更新状态
                     */
                    $this->db->where('id', $row['id'])
                        ->update('m_user_help_plan', ['status' => 1]);
                }else{
                    var_dump($row['uid']);
                }
            }
            $page++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * 核对二期、三期的帮扶计划，开拓管理奖。本月需要满足上月的低消，下线ID购买了当月的分期（1047.3）
     * 奖励入账时间是下月7号 23：59：59
     * 所以这个设定在7号23点执行，不满足抵消的抽回奖励。 by john 2019-01-07 23:35
     */
    public function checkMemberHelpReward()
    {
        $query_date = date('Y-m-t',strtotime('-1 month'));
        $query_date = $query_date . ' 23:59:59';
        $sql = "select * from m_user_change_reward where 1 and type=4 and remark='开拓管理奖-帮扶计划' and `status`=0 and create_time = '$query_date';";
        $orders = $this->db->query($sql)->result_array();

        //开始事务
        $this->db->trans_begin();
        $year_month = date('Y-m',strtotime('-1 month'));
        foreach ($orders as $order) {
            //本月月初等级就是上月的等级
            $ym = date('Y-m');
            $level = $this->db->select('level')->where('uid',$order['uid'])->where('year_month',$ym)->get('m_stat_level_month')->row_array();

            $sale = $this->db->select('sale_amount')->where('uid',$order['uid'])->where('year_month',$year_month)->get('m_stat_sale_month')->row_array();
            //1本人不满足抵消，2奖励来源ID没有购买分期，一律抽回 （按时不实现，有的会员补交。确认后再抽回）
            $help_plan = $this->db->where('uid',$order['child_uid'])->where('year_month',$year_month)->get('m_user_help_plan')->row_array();
            if($sale['sale_amount'] < ($level['level']*300) || $help_plan['is_buy'] == 0 || ($help_plan['status'] == 0 && $help_plan['done_time'] == '0000-00-00 00:00:00')){
                /**
                 * 金额变动
                 */
                $this->m_base->I('m_user_change_reward',[
                    'uid'=>$order['uid'],
                    'type'=>7,//退款
                    'amount'=>-$order['amount'],
                    'order_id'=>$order['order_id'],
                    'goods_id'=>$order['goods_id'],
                    'percent'=>$order['percent'],
                    'status'=>1,
                    'child_uid'=>$order['child_uid'],
                    'create_time'=>date('Y-m-d H:i:s'),
                    'remark'=> $year_month."月没有完成低消，抽回当月帮扶计划开拓管理奖"
                ]);
                $this->db->where('id',$order['id'])->update('m_user_change_reward',['status'=>1]);

                var_dump($order['id']);

            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function checkFix(){
      $sql = "SELECT * from m_user_help_plan where `year_month`='2019-01' and is_buy=1 and `status`=1;";
      $logs = $this->db->query($sql)->result_array();
        $monthStart = date('Y-m-01',strtotime('2019-01'));
        $monthEnd = date('Y-m-t 23:59:59',strtotime('2019-01'));
      foreach ($logs as $log){
          $share_log = $this->db->select('id,amount')
              ->where('child_uid',$log['uid'])
              ->where('type',2)
              ->where('status',0)
              ->where('goods_id',2746)
              ->where('create_time >=',$monthStart)
              ->where('create_time <=',$monthEnd)
              ->where('remark','帮扶计划')
              ->get('m_user_change_reward')
              ->row_array();
          if($share_log){
              $user = $this->db->select('parent_id')->where('uid', $log['uid'])->get('m_users')->row_array();
              $pid = $user['parent_id'];
              var_dump($log['uid'].'---'.$pid);
          }
      }
    }

    /**
     * 核对二期、三期的帮扶计划，开拓管理奖。本月需要满足上月的低消，下线ID购买了当月的分期（1047.3）
     * 奖励入账时间是下月7号 23：59：59
     * 所以这个设定在7号23点执行，不满足抵消的抽回奖励。 by john 2019-01-07 23:35
     */
    public function checkMemberHelpReward2()
    {
        $query_date = date('Y-m-t',strtotime('-1 month'));
        $query_date = $query_date . ' 23:59:59';
        $sql = "select * from m_user_change_reward where 1 and type=4 and remark='开拓管理奖-帮扶计划' and create_time = '$query_date';";
        $orders = $this->db->query($sql)->result_array();

        //开始事务
        $this->db->trans_begin();
        $year_month = date('Y-m',strtotime('-1 month'));
        $amount = 0;
        foreach ($orders as $order) {
            //本月月初等级就是上月的等级
            $ym = date('Y-m');
            $level = $this->db->select('level')->where('uid',$order['uid'])->where('year_month',$ym)->get('m_stat_level_month')->row_array();

            $sale = $this->db->select('sale_amount')->where('uid',$order['uid'])->where('year_month',$year_month)->get('m_stat_sale_month')->row_array();
            //1本人不满足抵消，2奖励来源ID没有购买分期，一律抽回 （按时不实现，有的会员补交。确认后再抽回）
            $help_plan = $this->db->where('uid',$order['child_uid'])->where('year_month',$year_month)->get('m_user_help_plan')->row_array();
            if($sale['sale_amount'] < ($level['level']*300) || $help_plan['is_buy'] == 0 || ($help_plan['status'] == 0 && $help_plan['done_time'] == '0000-00-00 00:00:00')){
               $this->db->insert('m_user_reduce_reward',$order);
            }
        }
        var_dump($amount);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function test(){

        $this->__requestData['year_month'] = '2019-05';
        $this->__requestData['mobile'] = 13011294901;
        $end = date('Y-m-t 23:59:59', strtotime($this->__requestData['year_month']));
        $start = date('Y-m-01', strtotime($this->__requestData['year_month']));

        //查询当月是否购买了礼包，
        $active = $this->db->where('create_time >=',$start)
            ->where('create_time <=',$end)
            ->where('mobile',$this->__requestData['mobile'])
            ->order_by('create_time','asc')
            ->get('m_mall_buy_active')
            ->row_array();

        //兼容1号购买了，31购买的没有起作用。
        if(date("t",strtotime($active['create_time'])) == 31 && date("d",strtotime($active['create_time'])) == 01 ){
            $start = date('Y-m-31', strtotime($this->__requestData['year_month']));
            $end = date('Y-m-31 23:59:59', strtotime($this->__requestData['year_month']));
            $is31Buy = $this->db->where('create_time >=',$start)
                ->where('create_time <=',$end)
                ->where('mobile',$this->__requestData['mobile'])
                ->order_by('create_time','asc')
                ->get('m_mall_buy_active')
                ->row_array();
            if($is31Buy){
                $active = $is31Buy;
            }
        }
        var_dump($active);
    }
}
