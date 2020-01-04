<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $user_info = $this->_userInfo;

        //累计奖金
        $total_reward = $this->m_user_month_reward->get_user_all_reward_total($user_info['uid']);

        //推荐人信息
        /*$user_info['parent_info'] = $this->m_users->get_user_info(
            array('uid'=>$user_info['parent_id']),
            'username,mobile,true_name'
        );*/

        // 今日牌价

        //获取本月(本期)奖金金额
        $month_reward = $this->m_user_month_reward->get_user_month_reward_total($user_info['uid'],date('Y-m'));

        //待入账金额
        $wait_reward = $this->m_user_change_reward->get_reward_amount(array(
            'uid'=>$user_info['uid'],
            'status'=>0,
        ));

        //可提现金额(奖金总额 - 已提现金额)
        $allow_withdraw_amount = price_format($total_reward - $user_info['withdraw_amount'] - $user_info['transfer_amount']);
        if ($allow_withdraw_amount < 0){
            $allow_withdraw_amount = 0;
        }

        //直推会员人数
        $child_count = $this->m_base->C('m_users',['parent_id'=>$user_info['uid']]);

        //直推会员销售额
        $child_amount = $this->m_users->get_child_amount($user_info['uid']);

        //全部团队人数
        $all_child_count = $this->db
            ->from('m_user_all_parents')
            ->where('parent_id',$user_info['uid'])
            ->count_all_results();

        //全部团队销售额
        $all_child_amount = $this->m_users->get_all_child_amount($user_info['uid']);

        /**
         * 金币收入的各项统计
         */
        $goldDetail = $this->db->query("select type,sum(gold) value from m_user_change_gold where uid={$user_info['uid']} group by type;")->result_array();
        $showGold = [];
        $total_gold=0;
        $typeArr = [1,2,3,4,5,6,9,10,11,12];
        foreach ($goldDetail as $val){
            in_array($val['type'],$typeArr) && $total_gold += $val['value'];
            $showGold[$val['type']] = $val['value'];
        }
        unset($goldDetail);
        $this->_viewData['showGold'] = $showGold;
        $this->_viewData['total_gold'] = $total_gold;

        /**
         * 今日牌价
         */
        $day = date('Ymd');
        $goldPrice = $this->m_base->S('m_gold_price_day',['day'=>$day],'gold_price');
        $this->_viewData['goldPrice'] = $goldPrice ? $goldPrice['gold_price'] : '计算中...';

        $this->_viewData['user_info'] = $user_info;                                 //用户信息
        $this->_viewData['allow_withdraw_amount'] = $allow_withdraw_amount;         //可提现金额
        $this->_viewData['total_reward'] = price_format($total_reward+$wait_reward);       //累计奖金
        $this->_viewData['wait_reward'] = price_format($wait_reward);                             //待入账奖金

        $month_wait_reward = $this->m_user_change_reward->get_reward_amount(array(
            'uid'=>$user_info['uid'],
            'status'=>0,
            'start'=>date('Y-m'),
        ));
        $this->_viewData['month_reward'] = price_format($month_reward+$month_wait_reward);                             //本期奖金

        $this->_viewData['child_count'] = $child_count;                             //直推会员数
        $this->_viewData['child_amount'] = $child_amount;                         //直推会员销售额

        $this->_viewData['all_child_count'] = $all_child_count;                     //团队总人数
        $this->_viewData['all_child_amount'] = $all_child_amount;                   //团队总销售额

        # 帮扶计划
        $count = $this->m_base->C('m_user_help_plan',['uid'=>$user_info['uid']]);
        $this->_viewData['count'] = $count;


        if(config_item('is_new_mall')){
            $domain = config_item('new_mall_site');

            $mall_param = config_item('mall_param');
            $secret_key = $mall_param['secret_key'];

            $arr['mobile']= $user_info['mobile'];
            $arr['password'] = $user_info['password'];
            $arr['create_time'] = time();
            $string = json_encode($arr);
            $key = $this->crypt->lock_url($string,$secret_key);
            $hash_string = base64_encode($key);

            $sync_link = 'http://'.$domain.'/mobile/users/api_login?t='.$hash_string;

            $this->_viewData['order_url'] = $sync_link."&redirect=http://{$domain}/mobile/orders/index.html#ok";
            $this->_viewData['coupons_url'] = $sync_link."&redirect=http://{$domain}/addon/coupon-users-moindex.html";
            $this->_viewData['history_url'] = $sync_link."&redirect=http://{$domain}/mobile/goods/history.html";
            $this->_viewData['collection_url'] = $sync_link."&redirect=http://{$domain}/mobile/favorites/goods.html";
            $this->_viewData['auction_url'] = $sync_link."&redirect=http://{$domain}/addon/auction-users-moauction.html";
            $this->_viewData['money_url'] = $sync_link."&redirect=http://{$domain}/addon/auction-users-momoney.html";
            $this->_viewData['addon_url'] = $sync_link."&redirect=http://{$domain}/addon/integral-goods-molists.html";
        }else {
            $this->_viewData['order_url'] = '/order';
            $this->_viewData['coupons_url'] = '/coupons';
            $this->_viewData['history_url'] = '/history';
            $this->_viewData['collection_url'] = '/collection';
            $this->_viewData['auction_url'] = "";
            $this->_viewData['money_url'] = "";
            $this->_viewData['addon_url'] = '/addon';
        }
        $this->_viewData['isAlert'] = false;
        if(isset($this->_getData['isAlert'])){
            $this->_viewData['isAlert'] = true;
        }

        parent::view('index');
    }


    /* 创建分享海报和链接 */
    public function create_share(){
        $user_info = $this->_userInfo;
        $enc_uid = $this->secret->encrypt_url($user_info['uid'],SECRET_STR);
        $recommend_link = $this->_domain.'/register/register_page?pid='.$enc_uid;
        $this->_viewData['recommend_link'] = $recommend_link;
        parent::view('create_share');
    }

    //生成推荐链接二维码
    public function qr_code(){

        //输出图片
        header('Content-type:image/png');
        include_once APPPATH .'/third_party/qrcode/phpqrcode.php';

        $user_info = $this->_userInfo;
        $enc_uid = $this->secret->encrypt_url($user_info['uid'],SECRET_STR);
        $recommend_link = $this->_domain.'/register/register_page?pid='.$enc_uid;

        $size = 10;
        $errorCorrectionLevel = 'L';//容错级别

        //清理缓存
        ob_clean();

        //生成二维码图片
        QRcode::png($recommend_link, false, $errorCorrectionLevel, $size, 2);
    }

    /* 跳转到商城 */
    public function go_to_mall(){
        $user_info = $this->_userInfo;
        $goods_id = isset($_GET['goods_id']) ? $_GET['goods_id'] : '';
        $sync_link = $this->m_users->get_sync_link($user_info['uid'],$goods_id);
        redirect($sync_link);
    }

    /* 创建图形验证码 */
    public function create_img_code(){

        //创建图像资源(x,y),颜色填充用imagefill
        $image = imagecreatetruecolor(100, 30);

        //背景颜色为白色
        $color = imagecolorallocate($image, 250, 250, 250);
        imagefill($image, 20, 20, $color);
        $code = '';
        for($i = 0;$i < 4; $i++){
            $fontSize = 15;
            $x = rand(5,10) + $i * 100 / 4;
            $y = rand(5, 15);
            $data = 'abcdefghijkmnpqrstuvwxyz123456789';
            $star = rand(0, strlen($data));
            $string = substr($data,$star,1);
            if($string == false){
                $string = rand(1,9);
            }
            $code.=$string;
            $color = imagecolorallocate($image,rand(0,120), rand(0,120), rand(0,120));
            imagestring($image,$fontSize,$x,$y,$string,$color);
        }
        $_SESSION['code'] = $code;//存储在session里
        for($i = 0;$i < 80; $i++){
            $pointColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
            imagesetpixel($image, rand(0, 100), rand(0, 30), $pointColor);
        }

        //增加干扰项
//        for($i = 0;$i < 2;$i++){
//            $linePoint=imagecolorallocate($image, rand(150, 255), rand(150, 255), rand(150, 255));
//            imageline($image, rand(10, 50), rand(10, 20), rand(80,90), rand(15, 25), $linePoint);
//        }
        ob_clean();
        header('Content-type:image/png');
        imagepng($image);
        imagedestroy($image);
    }
}
