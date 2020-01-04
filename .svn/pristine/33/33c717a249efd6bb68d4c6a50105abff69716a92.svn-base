<?php

class M_order extends CI_Model
{
    protected $personPercentArr = [];//个人消费比例
    protected $parentPercentArr = [];//直接推荐人比例
    protected $supplierPercent = 0;//供应商比例
    protected $saleArr = [];//最低消费额度
    protected $pointPercentArr = [];//积分比例
    protected $pointArr = [];//积分额度
    public function __construct()
    {
        parent::__construct();

        /**
         * 奖金比列初始化
         */
        $this->personPercentArr = config_item('bonus_percent')['person_comm'];
        $this->parentPercentArr = config_item('bonus_percent')['child_comm'];
        $this->saleArr = config_item('mini_sales');
        $this->pointPercentArr = config_item('child_point');
        $this->pointArr = config_item('level_point');
        $this->supplierPercent = config_item('bonus_percent')['supplier_comm'];
    }

    /**
     * @description 直接推荐奖
     * @param $uid 用户id
     * @param $pid 推荐人id
     * @param int $sales  利润金额 单位：元
     * @param int $order_id 订单id
     */
    public function parentSales($uid,$pid, $sales = 0,$order_id=0,$goods_id,$create_time)
    {
        $pUser = $this->db->from('m_users')
            ->select('level')
            ->where('uid',$pid)
            ->get()
            ->row_array();

        if(!$pUser || $sales < 0 || $pid == 0) return;

        $percent = $this->parentPercentArr[$pUser['level']];

        $cash =  price_format($sales * $percent);

        $cash = $cash < 0.01 ? 0 : $cash;
        if($cash == 0) return;

        /**
         * 金额变动
         */
        $this->m_base->I('m_user_change_reward',[
            'uid'=>$pid,
            'type'=>2,
            'amount'=>$cash,
            'order_id'=>$order_id,
            'goods_id'=>$goods_id,
            'child_uid'=>$uid,
            'percent'=>$percent,
            'status'=>0,
            'create_time'=>$create_time
        ]);
    }

    /**
     * @description 个人消费奖
     * @param $uid 用户id
     * @param int $sales  利润金额 单位：元
     * @param int $order_id 订单id
     */
    public function shoppingSales($uid , $sales = 0,$order_id=0,$goods_id,$monthSales,$monthLevel,$create_time,$goodsPrice)
    {
        $user = $this->db->from('m_users')
            ->select('level,parent_id,shopping_amount')
            ->where('uid',$uid)
            ->get()
            ->row_array();

        if(!$user || $sales < 0 ) return;

        /**
         * 判断这是会员的第一比消费：直推会员完成第一笔消费（不限额度），上线获得5元金币等价券，
         * 并且存在上线
         */
        if($user['shopping_amount'] == 0 && $user['parent_id']){
            /**
             * 1.金价根据今日牌价转换成金币
             */
            $value = config_item('first_shopping');
            $day = date('Ymd',strtotime($create_time));
            $goldPrice = $this->m_common->getGoldPrice($day);
            $gold = $this->m_common->transferToGold($value,$goldPrice);

            /**
             * 2.金币变动
             */
            $this->m_base->I('m_user_change_gold',[
                'uid'=>$user['parent_id'],
                'type'=>3,
                'before_amount'=>$value,
                'gold'=>$gold,
                'gold_price'=>$goldPrice,
                'order_id'=>$order_id,
                'child_uid'=>$uid,
                'status'=>1,
                'create_time'=>$create_time
            ]);

            /**
             * 3.累计金币
             */
            $this->db->where('uid', $user['parent_id'])
                ->set('gold', 'gold+' . $gold, FALSE)
                ->update('m_users');
        }


        /**
         * 月初等级是6，那么此会员如果没有累计消费到1800，那么消费提成按照月初等级6。30%
         *
         * 如果月初等级是1的会员，满足1800，拿最高消费V6的比例。30%
         *
         * 刺激等级低的会员赶紧消费升级到高等级，因为等级高的会员每单都可以拿到高比例。
         */
        if($monthSales >= $this->saleArr[$monthLevel]){
            //会员的消费等级
            $level = (int)($monthSales / 300);

            if($level > 6 ) $level = 6;

            $percent = $this->personPercentArr[$level];
        }else{
            $percent = $this->personPercentArr[$monthLevel];
        }

        $cash =  price_format($sales * $percent);

        $cash = $cash < 0.01 ? 0 : $cash;
        if($cash == 0) return;

        /**
         * 金额变动
         */
        $this->m_base->I('m_user_change_reward',[
            'uid'=>$uid,
            'type'=>1,
            'amount'=>$cash,
            'order_id'=>$order_id,
            'goods_id'=>$goods_id,
            'percent'=>$percent,
            'status'=>0,
            'create_time'=>$create_time
        ]);

        /**
         * 累计消费额
         */
        $this->db->where('uid', $uid)
            ->set('shopping_amount', 'shopping_amount+' . $goodsPrice, FALSE)
            ->update('m_users');

        /***
         * 月消费额,月利润额
         */
        $year_month =  date('Y-m',strtotime($create_time));

        $sale_month_row = $this->db
            ->where('uid',$uid)
            ->where('year_month',$year_month)
            ->get('m_stat_sale_month')
            ->row_array();
        if($sale_month_row){
            $this->db
                ->where('uid',$uid)
                ->where('year_month',$year_month)
                ->set('sale_amount','sale_amount+'.$goodsPrice,FALSE)
                ->set('sale_profit','sale_profit+'.$sales,FALSE)
                ->update('m_stat_sale_month');
        }else{

            $this->db->insert('m_stat_sale_month',[
                'uid'=>$uid,
                'sale_amount'=>$goodsPrice,
                'sale_profit'=>$sales,
                'year_month'=>$year_month
            ]);
        }

    }

    /**
     * 供应商推荐奖 VO 也可以拿0.02
     * @param $uid
     * @param $sales
     * @param $order_id
     */
    public function supplierSales($uid,$sales,$order_id,$goods_id,$create_time){
        $user = $this->db->from('m_users')
            ->select('level')
            ->where('uid',$uid)
            ->get()
            ->row_array();

        if(!$user || $sales < 0) return;

        $percent = $this->supplierPercent;

        $cash =  price_format($sales * $percent);

        $cash = $cash < 0.01 ? 0 : $cash;
        if($cash == 0) return;

        /**
         * 金额变动
         */
        $this->m_base->I('m_user_change_reward',[
            'uid'=>$uid,
            'type'=>3,
            'amount'=>$cash,
            'order_id'=>$order_id,
            'goods_id'=>$goods_id,
            'percent'=>$percent,
            'status'=>0,
            'create_time'=>$create_time
        ]);

    }


    /** 根据月初等级和消费得到奖金的比列
     * @param $monthSales 包含本次订单的月销售额
     * @param $monthLevel 用户月初的等级
     * @param string $item_bonus
     * @return float
     */
    private function getPercent($monthSales,$monthLevel,$item_bonus = 'person_comm'){

        //会员的消费等级
        $level = (int)($monthSales / 300);

        if($level > 6 ) $level = 6;

        $percentArr = config_item('bonus_percent')[$item_bonus];

        $saleArr = config_item('mini_sales');

        /**
         * 月初等级是6，那么此会员如果没有累计消费到1800，那么消费提成只能按照20%
         *
         * 如果月初等级是1的会员，满足1800.可以拿V6的比例。
         *
         * 这个玩法刺激等级低的会员赶紧消费，等级高的会员想拿高比例必须消费。看来花了很多心思。
         */

        /**
         *  不是高等级的阶段性升级
          while ($level > 0){
            if($monthSales >= $saleArr[$level]){
                $percent = $percentArr[$level];
                break;//满足条件退出
            }
            $level--;
        }*/

        if($monthSales >= $saleArr[$monthLevel]){
            $percent = $percentArr[$level];
        }else{
            $percent = 0.2;
        }


        return $percent;
    }

    /**
     * 积分奖励：个人积分100%->上线积分1/2->上上线积分1/4
     * （单位 ：元） todo 小龚 积分*比例有小数。
     * @param $uid
     * @param int $point
     * @param int $order_id
     */
    public function pointSales($uid, $point = 0, $order_id = 0,$goods_id,$create_time){

        /**
         * 会员的推荐人，上推荐人
         */
        $parents = $this->db->from('m_user_all_parents')
            ->select('parent_id')
            ->where('uid',$uid)
            ->where('level <=',3)
            ->get()
            ->result_array();

        if($point <= 0) return;

        $parent_ids = [] ;
        $parent_ids[] = $uid;
        /**
         * 有的会员推荐人ID是0，就没有$parents
         */
        if($parents){
            foreach ($parents as $parent){
                $parent_ids[] = $parent['parent_id'];
            }
        }

        /**
         * 拼接 uid，推荐人，上推荐人 ，上上推荐人 一维数组
         */

        foreach($parent_ids as $k => $pid) {

            if($pid == 0) continue;

            $percent = $this->pointPercentArr[$k];

            $po  =  price_format($point*$percent);

            $po = $po < 0.01 ? 0 : $po;
            if($po == 0) continue;

            /**
             * 积分变动
             */
            $this->m_base->I('m_user_change_point',[
                'uid'=>$pid,
                'type'=>1,
                'point'=>$po,
                'percent'=>$percent,
                'order_id'=>$order_id,
                'goods_id'=>$goods_id,
                'child_uid'=> $pid == $uid ? '' : $uid,
                'create_time'=>$create_time
            ]);

            /**
             * 添加积分消息提醒 1.个人消费，2.他人消费
             */
            $content_type = $pid == $uid ? '个人消费' : '他人消费';
            $this->db->where('uid',$pid)->update('m_users',['message_type'=>4]);
            $this->db->insert('m_message',array(
                'uid'=>$pid,
                'type'=>4,
                'param1'=>$po,
                'param2'=>$content_type,
                'content'=>"你有一笔积分已入账,来自{$content_type},请查收!"
            ));


            /**
             * 积分累加
             */
            $this->db->where('uid', $pid)
                ->set('point', 'point+' . $po, FALSE)
                ->update('m_users');

            /**
             * 积分到达数量 且 有3个直推人。更新用户等级
             */
            $this->upgradeLevel($pid,$create_time);
        }
    }

    /**
     * 升级等级:需求修改只有v1升级到V2才需要3个直推
     */
    public function upgradeLevel($pid,$create_time,$isForce=false){

        $uRow = $this->m_base->S('m_users',['uid'=>$pid],'level,parent_id,point');

        //最高等级不用更新了
        if($uRow['level'] == 6) return;

        //用户的下个等级
        $next_level = $uRow['level'] + 1;
        $need_point = $this->pointArr[$next_level];


        //满足下个等级的要求
        if($uRow['point'] >= $need_point)
        {
            /**
             * 积分$uRow['point'] 可能远比下一等级$need_point大。需要从最高等级开始算。
             */
            $init_level = 6;
            while ($init_level){
                if($uRow['point'] >= $this->pointArr[$init_level]){
                    $level = $init_level;
                    break;//满足条件退出
                }
                $init_level--;
            }

            /**
             *
            1.积分满足后，还需要团队有3个低一级别的会员。例如：A会员当前等级V2，消费后满足9488积分可以升级到V3，还需要A团队下面有3个V2等级的会员才能升级到V3.
            2.如果A会员满足要求能升级到V3，需要检测A会员的整个上线是否达到升级标准（A会员的推荐人，推荐人的推荐人。。。）因为A的等级变动，A会员整个上线都要重新检测。连锁性很强。
            3.老的升级制度V2等级开始需要3个直推。新规则是否需要从V2开始需要有3个低一级别的会员.
             */
            if($create_time >= '2018-10-14')
            {
                if($level >= 2) {
                    $find_level = $level - 1;
                    $childCount = $this->m_users->get_level_count($pid,$find_level);

                    /**
                     * 1026会员目前等级是V3，但是积分是V5，查找$find_level是V4.如果没有V4，应该继续找V3. by john 2018-11-27
                     */
                    if ($isForce !== true) {//不是强制积分升级
                        while ($childCount < 3 && $uRow['level'] < $level && $level >= 3) {
                            $level = $level - 1;//关键重新定位等级
                            $find_level = $level - 1;
                            $childCount = $this->m_users->get_level_count($pid, $find_level);
                        }
                    }

                    if($childCount < 3 )
                    {
                        if($uRow['level'] == 0){
                            $level = 1; //如果当前等级是0，满足积分，可以升级到V1
                        }
                        else
                        {
                            if($isForce !== true){ //不是强制积分升级
                                return;
                            }
                        }
                    }
                }
            }else{
                if($level >= 2 && $uRow['level'] <=1) {
                    $childCount = $this->m_base->C('m_users',['parent_id'=>$pid]);
                    if($childCount < 3 )
                    {
                        if($uRow['level'] == 0){
                            $level = 1; //没有3个直推人,最好等级是1
                        }
                        else
                        {
                            if($isForce !== true){ //不是强制积分升级
                                return;
                            }
                        }
                    }
                }
            }

            //等级没有提高，忽略。
            if($level <= $uRow['level']){
                return;
            }

            /**
             * 如果存在上线，直推人增加金币
             */
            if($uRow['parent_id']){

                //$jumpCount = $level - $uRow['level'];//将升等级和现有等级相差几级

                /**
                 * 避免重复发放升级金币,根据会员的等级变动最大等级。$level - $userLevel['u_level']
                 */
                $userLevel = $this->db->query("select MAX(new_level) as u_level from m_user_change_level where 1 and uid=$pid")->row_array();
                $u_level = $userLevel['u_level'] ? $userLevel['u_level'] : 0;
                $jumpCount = $level - $u_level;
                if($jumpCount >= 1){
                    /**
                     * 1.金价根据今日牌价转换成金币
                     */
                    $value = config_item('upgrade_level') * $jumpCount;
                    $day = date('Ymd',strtotime($create_time));
                    $goldPrice = $this->m_common->getGoldPrice($day);
                    $gold = $this->m_common->transferToGold($value,$goldPrice);

                    /**
                     * 2.金币变动
                     */
                    $this->m_base->I('m_user_change_gold',[
                        'uid'=>$uRow['parent_id'],
                        'type'=>3,
                        'before_amount'=>$value,
                        'gold'=>$gold,
                        'gold_price'=>$goldPrice,
                        'child_uid'=>$pid,
                        'status'=>1,
                        'create_time'=>$create_time
                    ]);

                    /**
                     * 3.累计金币,升级等级
                     */
                    $this->db->where('uid', $uRow['parent_id'])
                        ->set('gold', 'gold+' . $gold, FALSE)
                        ->update('m_users');
                }
            }

            $this->m_base->U('m_users',['uid'=>$pid],['level'=>$level]);

            /**
             * 4.等级变动
             */
            $this->m_base->I('m_user_change_level',[
                'uid'=>$pid,
                'old_level'=>$uRow['level'],
                'new_level'=>$level,
                'create_time'=>$create_time
            ]);

            if($create_time >= '2018-10-14') {
                /**
                 * 5.当A会员的等级变动，需要检测A的整个上线推荐树，是否满足升级
                 */
                $this->m_users->check_parent_upgrade_level($pid);
            }

            /**
             * 11月份 升级到 V2 奖励1988优惠券
             */
//            if(isActivityIng($create_time) && $level == 2){
//                $this->m_goods->addCouponLog($pid,38);
//            }

        }

    }


    /**
     * 订单退款检测降级操作
     */
    public function degradeLevel($pid,$create_time){

        $wallet = $this->m_base->S('m_users',['uid'=>$pid],'point');
        /**
         * 现有积分对应的等级
         */
        $level = 0;
        $init_level = 6;
        while ($init_level){
            if($wallet['point'] >= $this->pointArr[$init_level]){
                $level = $init_level;
                break;//满足条件退出
            }
            $init_level--;
        }

        $uRow = $this->m_base->S('m_users',['uid'=>$pid],'level');
        //只有 当前等级 大于 积分对应的等级。 才做降级操作
        if($uRow['level'] > $level) {
            $this->m_base->U('m_users',['uid'=>$pid],['level'=>$level]);

            /**
             * 等级变动
             */
            $this->m_base->I('m_user_change_level',[
                'uid'=>$pid,
                'old_level'=>$uRow['level'],
                'new_level'=>$level,
                'create_time'=>$create_time
            ]);
        }
    }

    /**
     * 订单商品退款
     */
    public function refundOrderGoods($order_id,$goods_id,$remark){
        //1.订单的积分抽回，重新计算用户的等级
        $where = ['order_id'=>$order_id,'type'=>1,'goods_id'=>$goods_id];
        $field = 'uid,point,child_uid,percent';
        $points  = $this->m_base->S('m_user_change_point',$where,$field,true);
        $create_time = date('Y-m-d H:i:s');
        foreach ($points as $point){
            /**
             * 积分变动
             */
            $this->m_base->I('m_user_change_point',[
                'uid'=>$point['uid'],
                'type'=>2,
                'point'=>-$point['point'],
                'percent'=>$point['percent'],
                'order_id'=>$order_id,
                'goods_id'=>$goods_id,
                'child_uid'=>$point['child_uid'],
                'create_time'=>$create_time
            ]);

            /**
             * 减去积分
             */
            $this->db->where('uid', $point['uid'])
                ->set('point', 'point-' . $point['point'], FALSE)
                ->update('m_users');

            /**
             * 重新检测用户的等级
             */
            $this->degradeLevel($point['uid'],$create_time);
        }

        //2.区分订单是否已入账，入账的抽回奖金，还有用户的各个奖金统计
        $where = ['order_id'=>$order_id,'goods_id'=>$goods_id];
        $field = 'id,uid,amount,child_uid,percent,status,type,create_time,order_id,goods_id';
        $rewards  = $this->m_base->S('m_user_change_reward',$where,$field,true);
        $fieldMap = [
            1=>'shopping_reward',
            2=>'m_rec_reward',
            3=>'s_rec_reward',
            4=>'manage_reward',
            5=>'plat_reward',
        ];
        foreach ($rewards as $reward){

            $year_month =  date('Y-m',strtotime($reward['create_time']));

            //如果是个人消费奖
            if($reward['type'] == 1){

                //如果是帮扶计划的退款，不减去消费额
                if($goods_id != 2746){
                    /**
                     * 减去 - 消费额,月消费额、月利润额 -- 改成新商城的数据连接，计算订单商品金额和利润 by john 2018-10-24
                     */
                    $this->mall = $this->load->database('ccs168_mall_new', TRUE);

                    //获取订单商品的总价和利润
                    $orderGoods = $this->mall->from('wst_order_goods wog')->select('wog.goodsId,wog.goodsPrice,wog.goodsNum,wog.goodsPoint,wog.goodsReward')
                        ->join('wst_orders wo','wo.orderId=wog.orderId','left')
                        ->where('wo.orderNo',$reward['order_id'])
                        ->where('wog.goodsId',$reward['goods_id'])
                        ->get()->row_array();
                    $goodsPrice = $orderGoods['goodsPrice']*$orderGoods['goodsNum'];
                    /**
                     * 本身已经乘以数量，积分、利润不能乘以数量 by john 2018-10-24
                     */
                    $sales  = $orderGoods['goodsReward'];
                    /**
                     * 特定酒水利润不是积分，而是176 by john 2018-10-24
                     */
                    //if($orderGoods['goodsId'] == 1006){
                    //    $sales  = 176*$orderGoods['goodsNum'];
                    //}

                    $this->db->where('uid', $reward['uid'])
                        ->set('shopping_amount', 'shopping_amount-' . $goodsPrice, FALSE)
                        ->update('m_users');

                    $this->db
                        ->where('uid',$reward['uid'])
                        ->where('year_month',$year_month)
                        ->set('sale_amount','sale_amount-'.$goodsPrice,FALSE)
                        ->set('sale_profit','sale_profit-'.$sales,FALSE)
                        ->update('m_stat_sale_month');
                }
            }

            /**
             * 金额变动
             */
            $this->m_base->I('m_user_change_reward',[
                'uid'=>$reward['uid'],
                'type'=>7,//退款
                'amount'=>-$reward['amount'],
                'order_id'=>$order_id,
                'goods_id'=>$goods_id,
                'percent'=>$reward['percent'],
                'status'=>1,
                'child_uid'=>$reward['child_uid'],
                'create_time'=>$create_time,
                'remark'=>$remark
            ]);

            //未入账的奖金
            if($reward['status'] == 0){

                /**
                 * 未入账的记录 改成 已入账。 平账上面的金额变动
                 */
                $this->db->where('id',$reward['id'])->update('m_user_change_reward',['status'=>1]);
            }
            //已入账奖金
            elseif($reward['status'] == 1)
            {
                $udpate_field = $fieldMap[$reward['type']];
                $this->db
                    ->where('uid',$reward['uid'])
                    ->where('at_date',$year_month)
                    ->set($udpate_field,$udpate_field.'-'.$reward['amount'],FALSE)
                    ->update('m_user_month_reward');
            }
            else
            {
                continue;
            }
        }

    }

    /**
     * 获取订单数据
     * @param $filter
     * @param int $perPage
     * @return mixed
     */
    public function get_order_list($filter, $perPage = 10,$type = '') {

        $this->mall = $this->load->database('ccs168_mall', TRUE);
        $this->mall->select('id,order_no,real_amount,user_mobile,pay_status,distribution_status,status');
        $this->mall->from('i_order');
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'user_id':
                    $user_info = $this->db->select('mobile')->where('uid',$v)->get('m_users')->row_array();
                    $this->mall->where('user_mobile',$user_info['mobile']);
                    break;
                default:
                    $this->mall->where($k, $v);
                    break;
            }
        }
        if($type == 'count(*)'){
            return $this->mall->count_all_results();
        }else{
            return $this->mall->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        }
    }


    /**
     * 能够组团的人数
     * 开括管理奖
     *
     * If Tp>=Cs(n) then
        奖励=M/N* P3r
      Else
         奖励=0
     */
    public function manageReward($year_month){

        $this->statManageNum($year_month);

        /**
         * 当期销售平台产生的总毛利
         */
        $sp = $this->db
            ->select_sum('sale_profit')
            ->where('year_month',$year_month)
            ->get('m_stat_sale_month')
            ->row_array();
        /**
         * P3r： 当期前三项奖金发放后剩余的奖金，计算公式：
            P3r=Sp*88%--Σ个人消费奖--Σ直接推荐奖
         */
        $begin = date('Y-m-01 00:00:00',strtotime($year_month));
        $end = date('Y-m-t 23:59:59',strtotime($year_month));
        $grant = $this->db
            ->select_sum('amount')
            ->where_in('type',[1,2])
            ->where('create_time >=',$begin)
            ->where('create_time <=',$end)
            ->where('goods_id <>',2746)//不包含帮扶计划产品的奖金
            ->get('m_user_change_reward')
            ->row_array();
        $p3r = $sp['sale_profit']*0.88 - $grant['amount'];

        /**
         * 记录每个月的总利润，已发放的利润，p3r=管理奖的奖金
         */
        $data['p3r'] = $p3r;
        $data['month_profit'] = $sp['sale_profit'];
        $data['grant_profit'] = $grant['amount'];
        $data['p3r'] = $p3r;
        $data['year_month'] = $year_month;
        $this->db->replace('m_stat_company_month',$data);
        /**
         * 统计每个会员的团队利润
         */
        $this->statManageProfit($year_month);
        /**
         * 计算会员团队利润权重，得出发放的奖金
         */
        $this->calcManageProfit($year_month,$p3r);
    }

    /**
     * 计算权重，按权重得到奖金
     * @param $year_month
     * @param $p3r
     */
    public function calcManageProfit($year_month,$p3r){
        $total = $this->db->from('m_stat_team_profit')
            ->where('year_month',$year_month)
            ->count_all_results();

        $all_profit = $this->db->select_sum('team_profit')
            ->where('year_month',$year_month)
            ->get('m_stat_team_profit')
            ->row_array();

        $page = 1;
        $pageSize = 10;
        $pageCount = ceil($total / $pageSize);

        echo 'Calc All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' ."\n";

            $start = ($page - 1) * $pageSize;

            $stats=$this->db->select('uid,team_profit')
                ->where('year_month',$year_month)
                ->limit($pageSize,$start)
                ->get('m_stat_team_profit')->result_array();

            foreach ($stats as $stat){

                if($stat['team_profit'] <= 0) continue;
                /**
                 * 当月销售额，月初等级
                 */
                $monthSale  = $this->db
                    ->select('sale_amount')
                    ->where('uid',$stat['uid'])
                    ->where('year_month',$year_month)
                    ->get('m_stat_sale_month')
                    ->row_array();

                $monthLevelRow = $this->db->select('level')
                    ->where('uid',$stat['uid'])->where('year_month',$year_month)
                    ->get('m_stat_level_month')->row_array();
                if($monthLevelRow && $monthLevelRow['level'] > 0){
                    $monthLevel = $monthLevelRow['level'];
                }else{
                    $monthLevel = 0;
                }

                $data = [];
                if($monthSale['sale_amount'] >= $this->saleArr[$monthLevel]){

                    $percent = $stat['team_profit']/$all_profit['team_profit'];
                    $cash = price_format($percent * $p3r);

                    $cash = $cash < 0.01 ? 0 : $cash;
                    if($cash == 0) continue;

                    $data['reward'] = $cash;
                    $data['percent'] = $percent;
                }
                else{
                    $data['remark'] = '月销售：' . ($monthSale ? $monthSale['sale_amount'] : '0').';消费任务：'.$this->saleArr[$monthLevel];
                }

                $this->db->where('uid',$stat['uid'])
                    ->where('year_month',$year_month)
                    ->update('m_stat_team_profit',$data);

            }
            $page ++;
        }
    }

    /**
     * 发放管理奖 待入账状态
     * @param $year_month
     */
    public function grantManageReward($year_month){

        $total = $this->db->from('m_stat_team_profit')
            ->where('year_month',$year_month)
            ->where('reward >',0)
            ->where('is_grant',0)
            ->count_all_results();

        $end = date('Y-m-t 23:59:59',strtotime($year_month));

        $page = 1;
        $pageSize = 100;
        $pageCount = ceil($total / $pageSize);

        echo 'Stat All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' ."\n";

            $members=$this->db->select('uid,reward')
                ->where('year_month',$year_month)
                ->where('reward >',0)
                ->where('is_grant',0)
                ->limit($pageSize)
                ->get('m_stat_team_profit')->result_array();

            foreach ($members as $member){
                /**
                 * 金额变动
                 */
                    $this->m_base->I('m_user_change_reward',[
                        'uid'=>$member['uid'],
                        'type'=>4,
                        'amount'=>$member['reward'],
                        'status'=>0,
                        'create_time'=>$end
                    ]);

                    /*$monthReward = $this->db
                        ->where('uid',$member['uid'])
                        ->where('at_date',$year_month)
                        ->get('m_user_month_reward')
                        ->row_array();

                    if($monthReward){
                        $this->db
                            ->where('uid',$member['uid'])
                            ->where('at_date',$year_month)
                            ->set('manage_reward',$member['reward'],FALSE)
                            ->update('m_user_month_reward');
                    }else{

                        $this->db->insert('m_user_month_reward',
                            ['uid'=>$member['uid'],'manage_reward'=>$member['reward'],'at_date'=>$year_month]);
                    }*/

                $this->db->where('uid',$member['uid'])
                    ->where('year_month',$year_month)
                    ->update('m_stat_team_profit',['is_grant'=>1]);
            }
            $page ++;
        }
    }

    /**
     * 统计团队利润
     */
    public function statManageProfit($year_month){

        $total = $this->db->from('m_stat_team_profit')
            ->where('year_month',$year_month)
            ->count_all_results();

        $page = 1;
        $pageSize = 100;
        $pageCount = ceil($total / $pageSize);

        echo 'Stat All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' ."\n";

            $start = ($page - 1) * $pageSize;

            $members=$this->db->select('uid')
                ->where('year_month',$year_month)
                ->limit($pageSize,$start)
                ->get('m_stat_team_profit')->result_array();

            foreach ($members as $member){
                /**
                 * 统计符合管理奖旗下团队所有利润
                 */
                $childs = $this->db->select('uid,level')
                    ->where('parent_id',$member['uid'])
                    ->get('m_user_all_parents')
                    ->result_array();
                $total_profit = 0;
                foreach ($childs as $child){
                    $profit = $this->db
                        ->select('sale_profit')
                        ->where('uid',$child['uid'])
                        ->where('year_month',$year_month)
                        ->get('m_stat_sale_month')
                        ->row_array();
                    $percent = pow(2,$child['level']);
                    $total_profit += ($profit['sale_profit']/$percent);
                }

                $data['team_profit'] = $total_profit;
                /**
                 * 更新进数据库
                 */
                $this->db->where('uid',$member['uid'])
                    ->where('year_month',$year_month)
                    ->update('m_stat_team_profit',$data);
            }
            $page ++;
        }
    }

    /**
     * 统计符合管理奖的会员
     * @param $year_month
     */
    public function statManageNum($year_month){
        if(empty($year_month)){
            $year_month = date('Y-m');
        }

        $this->db->query("REPLACE
                          INTO `m_stat_team_profit`
                          (`uid`,`year_month`)
                          SELECT
                            `m_users`.`uid`,'{$year_month}'
                          FROM
                            `m_users`,
                            (SELECT
                              `parent_id`, COUNT(*) 'team_count'
                            FROM
                              `m_users`
                            GROUP BY
                              `parent_id`
                            HAVING
                              `team_count` >= 3) tmp_count
                          WHERE
                            `m_users`.`uid` = `tmp_count`.`parent_id` AND `m_users`.`level` >= 1
                          ORDER BY `m_users`.`uid` ASC;");
    }

    /**
     * 统计经营奖
     */
    public function calcBusinessReward($year_month){
        /**
         * 统计各个等级拿奖的人数
         */
        $sql = "SELECT
                  `m`.`level`, COUNT(*) as count
                FROM
                  `m_user_month_reward` mr,
                  `m_users` m
                WHERE
                  `m`.`uid` = `mr`.`uid` AND `mr`.`at_date` = '{$year_month}' AND `level` > 0
                GROUP BY
                  `level`";
        $levelInfo = $this->db->query($sql)->result_array();
        $T = 0;
        $total = 0;
        foreach ($levelInfo as &$item){
            switch($item['level']) {
                case 1:
                    $item['t_count'] = $item['count'];
                    break;
                case 2:
                    $item['t_count'] = intval($item['count']*(50/46));
                    break;
                case 3:
                    $item['t_count'] = intval($item['count']*(54/46));
                    break;
                case 4:
                    $item['t_count'] = intval($item['count']*(58/46));
                    break;
                case 5:
                    $item['t_count'] = intval($item['count']*(62/46));
                    break;
                case 6:
                    $item['t_count'] = intval($item['count']*(66/46));
                    break;
                default:
                    break;
            }
            $T += $item['t_count'];
            $total += $item['count'];
        }

        /**
         * 当期销售平台产生的总毛利
         */
        $sp = $this->db
            ->select_sum('sale_profit')
            ->where('year_month',$year_month)
            ->get('m_stat_sale_month')
            ->row_array();
        $grantProfit = $sp['sale_profit'] * 0.05;
        $v1 = $grantProfit / $T;
        $profitMap  = [
            1=>$v1,
            2=>$v1*(50/46),
            3=>$v1*(54/46),
            4=>$v1*(58/46),
            5=>$v1*(62/46),
            6=>$v1*(66/46),
        ];

        foreach ($levelInfo as $v){
            $this->db->replace('m_stat_business',[
                'year_month'=>$year_month,
                'level'=>$v['level'],
                'reward'=>price_format($profitMap[$v['level']]),
                'count'=>$v['count'],
                't_count'=>$v['t_count'],
                'month_profit'=>$sp['sale_profit'],
                'percent'=>0.05,
                'amount'=>$grantProfit,
            ]);
        }

        $page = 1;
        $pageSize = 10;
        $pageCount = ceil($total / $pageSize);

        echo 'All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' ."\n";

            $start = ($page - 1) * $pageSize;

            $sql = "SELECT
                  mr.uid,`m`.`level`
                FROM
                  `m_user_month_reward` mr,
                  `m_users` m
                WHERE
                  `m`.`uid` = `mr`.`uid` AND `mr`.`at_date` = '$year_month' AND `level` > 0 limit {$start},{$pageSize}";
            $members = $this->db->query($sql)->result_array();

            foreach ($members as $member){

                /**
                 * 当月销售额，月初等级
                 */
                $monthSale  = $this->db
                    ->select('sale_amount')
                    ->where('uid',$member['uid'])
                    ->where('year_month',$year_month)
                    ->get('m_stat_sale_month')
                    ->row_array();

                $monthLevelRow = $this->db->select('level')
                    ->where('uid',$member['uid'])->where('year_month',$year_month)
                    ->get('m_stat_level_month')->row_array();
                if($monthLevelRow && $monthLevelRow['level'] > 0){
                    $monthLevel = $monthLevelRow['level'];
                }else{
                    $monthLevel = 0;
                }

                $data = [];
                if($monthSale['sale_amount'] >= $this->saleArr[$monthLevel]){

                    $reward  = $profitMap[$member['level']];
                    $cash = price_format($reward);

                    $cash = $cash < 0.01 ? 0 : $cash;
                    if($cash == 0) continue;

                    $data['uid'] = $member['uid'];
                    $data['year_month'] = $year_month;
                    $data['reward'] = $cash;
                    $data['level'] = $member['level'];
                    $data['is_grant'] = 0;
                    $this->db->insert('m_stat_business_detail',$data);

                }else{
                    $data['uid'] = $member['uid'];
                    $data['year_month'] = $year_month;
                    $data['reward'] = 0;
                    $data['level'] = $member['level'];
                    $data['is_grant'] = 0;
                    $data['remark'] = '月销售：'. ($monthSale ? $monthSale['sale_amount'] : '0').';消费任务：'.$this->saleArr[$monthLevel];
                    $this->db->insert('m_stat_business_detail',$data);
                }
            }
            $page ++;
        }

    }

    /**
     * 统计经营奖
     */
    public function calcBusinessRewardNew($year_month){
        /**
         * 统计各个等级拿奖的人数,满足最低消费才可以拿奖
         */
        $sql = "SELECT m.level,COUNT(*) as count
FROM m_stat_level_month m,m_stat_sale_month ms
WHERE m.level>0 AND ms.year_month='{$year_month}' AND m.year_month='{$year_month}' AND ms.uid=m.uid AND ms.sale_amount>=300*m.level
GROUP BY m.`level`;";


        $levelInfo = $this->db->query($sql)->result_array();
        $T = 0;
        $total = 0;
        foreach ($levelInfo as &$item){
            switch($item['level']) {
                case 1:
                    $item['t_count'] = $item['count'];
                    break;
                case 2:
                    $item['t_count'] = $item['count']*2;
                    break;
                case 3:
                    $item['t_count'] = $item['count']*4;
                    break;
                case 4:
                    $item['t_count'] = $item['count']*8;
                    break;
                case 5:
                    $item['t_count'] = $item['count']*16;
                    break;
                case 6:
                    $item['t_count'] = $item['count']*32;
                    break;
                default:
                    break;
            }
            $T += $item['t_count'];
            $total += $item['count'];
        }

        /**
         * 经营奖数字金币的50%
         */
        $total_gold = 10000;
        $grantProfit = $total_gold*0.5;
        $v1 = $grantProfit / $T;
        $profitMap  = [
            1=>$v1,
            2=>$v1*2,
            3=>$v1*4,
            4=>$v1*8,
            5=>$v1*16,
            6=>$v1*32,
        ];

        foreach ($levelInfo as $v){
            $this->db->replace('m_stat_business',[
                'year_month'=>$year_month,
                'level'=>$v['level'],
                'reward'=>price_format($profitMap[$v['level']]),
                'count'=>$v['count'],
                't_count'=>$v['t_count'],
                'month_profit'=>$total_gold, //月经营奖50%
                'percent'=>0.5,
                'amount'=>$grantProfit,
            ]);
        }

        $page = 1;
        $pageSize = 10;
        $pageCount = ceil($total / $pageSize);

        echo 'All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' ."\n";

            $start = ($page - 1) * $pageSize;

            $sql = "SELECT m.uid,m.`level`
FROM m_stat_level_month m,m_stat_sale_month ms
WHERE m.level>0 AND ms.year_month='{$year_month}' AND m.year_month='{$year_month}' AND ms.uid=m.uid AND ms.sale_amount>=300*m.level limit {$start},{$pageSize}";

            $members = $this->db->query($sql)->result_array();

            foreach ($members as $member){

                /**
                 * 当月销售额，月初等级
                 */

                $data = [];

                $reward  = $profitMap[$member['level']];
                $cash = price_format($reward);

                $cash = $cash < 0.01 ? 0 : $cash;
                if($cash == 0) continue;

                $data['uid'] = $member['uid'];
                $data['year_month'] = $year_month;
                $data['reward'] = $cash;
                $data['level'] = $member['level'];
                $data['is_grant'] = 0;
                $this->db->insert('m_stat_business_detail',$data);
            }
            $page ++;
        }

    }

    /**
     * 发放经营奖
     * @param $year_month
     */
    public function grantBusinessReward($year_month){

        $total = $this->db->from('m_stat_business_detail')
            ->where('year_month',$year_month)
            ->where('reward >',0)
            ->where('is_grant',0)
            ->count_all_results();

        $end = date('Y-m-t 23:59:59',strtotime($year_month));

        $page = 1;
        $pageSize = 100;
        $pageCount = ceil($total / $pageSize);

        echo 'Stat All ' . $pageCount . ' page,' .$total . ' Rows' ."\n" ;

        while ($page <= $pageCount) {

            echo 'This ' . $page . ' Page' ."\n";

            $members=$this->db->select('uid,reward,year_month')
                ->where('year_month',$year_month)
                ->where('reward >',0)
                ->where('is_grant',0)
                ->limit($pageSize)
                ->get('m_stat_business_detail')->result_array();

            log_message('error',print_r($members,1));

            foreach ($members as $member){
                /**
                 * 金额变动
                 */
                $this->m_base->I('m_user_change_reward',[
                    'uid'=>$member['uid'],
                    'type'=>5,
                    'amount'=>$member['reward'],
                    'status'=>1,
                    'create_time'=>$end
                ]);

                $monthReward = $this->db
                    ->where('uid',$member['uid'])
                    ->where('at_date',$member['year_month'])
                    ->get('m_user_month_reward')
                    ->row_array();

                if($monthReward){
                    $this->db
                        ->where('uid',$member['uid'])
                        ->where('at_date',$member['year_month'])
                        ->set('plat_reward',$member['reward'],FALSE)
                        ->update('m_user_month_reward');
                }else{

                    $this->db->insert('m_user_month_reward',
                        ['uid'=>$member['uid'],'plat_reward'=>$member['reward'],'at_date'=>$member['year_month']]);
                }

                $res = $this->db->where('uid',$member['uid'])
                    ->where('year_month',$member['year_month'])
                    ->update('m_stat_business_detail',['is_grant'=>1]);
                log_message('error',$member['uid'].'+++++++++'.print_r($res,1));
            }
            $page ++;
        }
    }

    /******************************* 直接从商城获取订单数据 start ******************************/


    public function get_order_list_new($order_id,$mobile){
        $user_info = $this->m_users->get_user_info(array('mobile'=>$mobile));
        if(empty($user_info)){/** 如果奖金系统没有注册会员，返回空 */
            return [];
        }
        $this->mall = $this->load->database('ccs168_mall', TRUE);

        $this->mall->select('id,user_mobile,order_no,create_time,pay_time,pay_status');
        $this->mall->where('id',$order_id);
        $order_info = $this->mall->get('i_order')->row_array();

        //获取订单商品数据，以商品为单位发奖
        $this->mall->select('goods_id,goods_price,goods_nums,seller_name,goods_point,');
        $this->mall->where('order_id',$order_id);
        $order_goods_list = $this->mall->get('i_order_goods')->result_array();

        $new_list = array();
        foreach ($order_goods_list as $key=>$goods){
            $new_list[$key]['order_id'] = $order_info['id'];
            $new_list[$key]['order_no'] = $order_info['order_no'];
            $new_list[$key]['uid'] = $user_info['uid'];
            $new_list[$key]['parent_id'] = $user_info['parent_id'];
            $new_list[$key]['mobile'] = $mobile;
            $new_list[$key]['pay_status'] = $order_info['pay_status'];
            $new_list[$key]['create_time'] = $order_info['create_time'];
            $new_list[$key]['pay_time'] = $order_info['pay_time'];
            $new_list[$key]['seller_mobile'] = $goods['seller_name'];
            $new_list[$key]['seller_parent_id'] = $this->m_users->get_supplier_recommend_id($goods['seller_name']);
            $new_list[$key]['goods_id'] = $goods['goods_id'];
            $new_list[$key]['goods_nums'] = $goods['goods_nums'];
            $new_list[$key]['goods_price'] = $goods['goods_price'] * $goods['goods_nums'];
            $new_list[$key]['goods_point'] = $goods['goods_point'] * $goods['goods_nums'];
        }
        return $new_list;
    }

    public function get_mall_goods($order_id,$mobile){
        $user_info = $this->m_users->get_user_info(array('mobile'=>$mobile));
        if(empty($user_info)){/** 如果奖金系统没有注册会员，返回空 */
            return [];
        }
        $this->mall = $this->load->database('ccs168_mall_new', TRUE);

        $this->mall->select('user_mobile,orderNo,payTime');
        $this->mall->where('orderId',$order_id);
        $order_info = $this->mall->get('wst_orders')->row_array();

        //获取订单商品数据，以商品为单位发奖
        $this->mall->select('goodsId,goodsPrice,goodsNum,goodsPoint,goodsReward');
        $this->mall->where('orderId',$order_id);
        $order_goods_list = $this->mall->get('wst_order_goods')->result_array();

        $new_list = array();
        $train_amount = 0;
        foreach ($order_goods_list as $key=>$goods){
            $new_list[$key]['order_id'] = $order_info['orderNo'];
            $new_list[$key]['uid'] = $user_info['uid'];
            $new_list[$key]['user_level'] = $user_info['level'];
            $new_list[$key]['parent_id'] = $user_info['parent_id'];
            $new_list[$key]['mobile'] = $mobile;
            $new_list[$key]['pay_time'] = $order_info['payTime'];
            $new_list[$key]['goods_id'] = $goods['goodsId'];
            $new_list[$key]['goods_nums'] = $goods['goodsNum'];
            $new_list[$key]['goods_price'] = $goods['goodsPrice'] * $goods['goodsNum'];
            //商城已经乘以了数量，不能再相乘了。
            $new_list[$key]['goods_point'] = $goods['goodsPoint'];
            $new_list[$key]['goods_reward'] = $goods['goodsReward'];
            //查询直通车的商品的订单金额
            $isTrain = $this->mall->from('wst_goods')->where('goodsId',$goods['goodsId'])->where('isTrain',1)->count_all_results();
            if($isTrain){
                $train_amount += $new_list[$key]['goods_price'];
            }
        }
        $new_list[0]['train_amount'] = $train_amount;
        return $new_list;
    }

    /* 根据订单ID 获取订单编号 */
    public function get_order_no($order_id){
        $this->mall = $this->load->database('ccs168_mall', TRUE);
        $order_info = $this->mall->select('order_no')->where('id',$order_id)->get('i_order')->row_array();
        if (!$order_info){
            return $order_id;
        }
        return $order_info['order_no'];
    }

    public function create_help_plan($uid,$pid,$month,$level,$pay_time,$order_id)
    {
            //查看是否第一次购买
            $count = $this->m_base->C('m_user_help_plan',['uid'=>$uid]);
            if($count){
                //更新当月的帮扶计划
                $this->m_base->U('m_user_help_plan',['uid'=>$uid,'year_month'=>$month],['is_buy'=>1,'order_id'=>$order_id]);
            }else{
                //第一次参加只能V0到V2等级的会员可以参加
                if($level >= 3){
                    return;
                }
                //生成分期计划
                for ($i=1;$i<=10;$i++){
                    $y_m = date('Y-m', strtotime('+' . ($i - 1) . 'month', strtotime($pay_time)));
                    $plan = [
                        'uid' => $uid,
                        'year_month' => $y_m,
                        'count' => $i
                    ];
                    if ($i == 1) {
                        $plan['is_buy'] = 1;
                        $plan['order_id'] = $order_id;
                    }
                    if ($i <= 3) {
                        $plan['profit'] = 1739.46;
                        /**
                         * 生成3期个人消费、分享奖励（待入账）
                         */
                        $date = date('Y-m-d H:i:s', strtotime('+' . ($i - 1) . 'month', strtotime($pay_time)));
                        $sale = 500;
                        $this->m_base->I('m_user_change_reward',[
                            'uid'=>$uid,
                            'type'=>1,
                            'amount'=>$sale,
                            'order_id'=>$order_id,
                            'goods_id'=>2746,
                            'percent'=>1,
                            'status'=>0,
                            'create_time'=>$date,
                            'remark'=>'帮扶计划'
                        ]);
                        $share = $i == 3 ? 700 : 500;
                        $this->m_base->I('m_user_change_reward',[
                            'uid'=>$pid,
                            'type'=>2,
                            'amount'=>$share,
                            'order_id'=>$order_id,
                            'goods_id'=>2746,
                            'child_uid'=>$uid,
                            'percent'=>1,
                            'status'=>0,
                            'create_time'=>$date,
                            'remark'=>'帮扶计划'
                        ]);
                    }
                    $this->m_base->I('m_user_help_plan',$plan);
                }
                /**
                 * 积分是9488
                 */
                $this->m_order->pointSales($uid, 9488, $order_id, 2746, $pay_time);
                $this->m_order->upgradeLevel($uid,$pay_time,true);

            }
    }

    public function create_active_plan($uid,$mobile,$pay_time,$order_id,$goods_id)
    {
        $this->m_base->I('m_mall_buy_active',[
            'uid'=>$uid,
            'mobile'=>$mobile,
            'order_id'=>$order_id,
            'goods_id'=>$goods_id,
            'create_time'=>$pay_time
        ]);
    }
}