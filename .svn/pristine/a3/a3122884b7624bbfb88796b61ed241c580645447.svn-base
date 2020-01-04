<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require_once APPPATH . '/libraries/REST_Controller.php';


class Api_member extends REST_Controller {

    private $__requestData = [];
    private $__ticket = 'qmSO2w9IQckmhFcxarAgim2ZRzXxiTYQ';
	public function __construct()
	{
		parent::__construct();
		
		//签名检测
        $this->__requestData = $this->input->post();

        log_message('error','API:'.print_r($this->__requestData,1));

        if(!isset($this->__requestData['timestamp']) || !$this->__requestData['timestamp']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数timestamp', 'data' => []), 200);
        }
        if(!isset($this->__requestData['nonce']) || !$this->__requestData['nonce']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数nonce', 'data' => []), 200);
        }
        if(!isset($this->__requestData['sign']) || !$this->__requestData['sign']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数sign', 'data' => []), 200);
        }

        $isPass = checkSign($this->__requestData,$this->__ticket);
        if($isPass == false){
            $this->response(array('code' => 1,'msg' => '验签失败', 'data' => []), 200);
        }

        if($this->__requestData['timestamp'] < (time()-60)){
            $this->response(array('code' => 1,'msg' => '请求失效', 'data' => []), 200);
        }

        /*if(strlen($this->__requestData['nonce']) != 32 ){
            $this->response(array('code' => 1,'msg' => '随机数长度必须32位', 'data' => []), 200);
        }*/
        $nonce = $this->db->where('nonce',$this->__requestData['nonce'])->order_by('id','desc')->get('m_api_nonce')->row_array();
        if($nonce && $nonce['create_time'] >= (time()-60)){
            $this->response(array('code' => 1,'msg' => '随机数60秒内重复了', 'data' => []), 200);
        }
        $this->db->insert('m_api_nonce',[
            'nonce'=>$this->__requestData['nonce'],
            'create_time'=>time(),
        ]);
	}

	/* api注册  */
	function register_post()
	{

        if(!isset($this->__requestData['mobile']) || !$this->__requestData['mobile']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数mobile', 'data' => []), 200);
        }
        if(!isset($this->__requestData['parent_mobile']) || !$this->__requestData['parent_mobile']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数parent_mobile', 'data' => []), 200);
        }
        if(!isset($this->__requestData['password']) || !$this->__requestData['password']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数password', 'data' => []), 200);
        }

        $result = $this->checkRegister($this->__requestData);
		$this->response($result, 200);
	}

    /**
     * 查询会员是否完成礼包，激活任务
     */
	public function getMemberIsBuy_post(){
        if(!isset($this->__requestData['mobile']) || !$this->__requestData['mobile']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数mobile', 'data' => []), 200);
        }
        if(!isset($this->__requestData['year_month']) || !$this->__requestData['year_month']){
            $this->response(array('code' => 1,'msg' => '缺少必要参数year_month', 'data' => []), 200);
        }


        if(isset($this->__requestData['year_month_day'])  && $this->__requestData['year_month_day']){

            //查询当月是否购买了礼包，
            $active = $this->db->where('create_time >=',$this->__requestData['year_month_day'])
                ->where('mobile',$this->__requestData['mobile'])
                ->order_by('create_time','asc')
                ->get('m_mall_buy_active')
                ->row_array();
        }else{
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
        }



        if(!$active){
            $result =  array('code' => 100,'msg' => '没有购买礼包', 'data' => []);
        }else{
            $result =  array('code' => 0,'msg' => '当月已购买礼包', 'data' => ['payDate'=>$active['create_time']]);
        }

        $this->response($result, 200);
    }

    /**
     * 验证注册信息
     * @param $param
     * @return array
     */
    private function checkRegister($param){

        //验证手机号
        if (is_phone($param['mobile']) == ''){
            return array('code'=>1,'msg'=>'请输入正确的手机号码','data'=>array());
        }

        //是否已存在
        $mobile = $param['mobile'];
        $user_info = $this->m_users->get_user_info(array('mobile'=>$mobile));
        if ($user_info){
            return array('code'=>0,'msg'=>'该手机号已经存在，无需注册。','data'=>array('uid'=>$user_info['uid']));
        }

        //验证推荐人手机号
        $parent_mobile = $param['parent_mobile'];
        $parent_info = $this->m_users->get_user_info(array('mobile'=>$parent_mobile));
        if (!$parent_info){
            return array('code'=>1,'msg'=>'推荐人不存在','data'=>array());
        }
        if ($parent_mobile == $mobile){
            return array('code'=>1,'msg'=>'推荐人不能为自己','data'=>array());
        }

        //验证密码
        if (strlen($param['password']) < 6 || strlen($param['password']) > 20){
            return array('code'=>1,'msg'=>'密码长度在6-20位之间','data'=>array());
        }

        /***************************************  创建账户 *********************************************/

        $this->db->trans_begin();

        $insert_attr = array();
        $insert_attr['mobile'] = $mobile;
        $insert_attr['username'] = 'p'.rand(100000,999999);
        $insert_attr['token'] = create_token();
        $insert_attr['password'] = md5($param['password']);
        $insert_attr['parent_id'] = $parent_info['uid'];
        $insert_attr['true_name'] = '';
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

        $this->ccs168_mall = $this->load->database('ccs168_mall_new', TRUE);
        $data['createTime'] = date('Y-m-d H:i:s');
        $data["loginSecret"] = '';
        $data['loginPwd'] = md5($param['password']);
        $data['userPhone'] = $mobile;
        $data['loginName'] = $mobile;
        $data['userName'] = $mobile;
        $data['userName'] = $mobile;
        $data['wxOpenId'] = '';

        $this->ccs168_mall->insert('wst_users',$data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('code'=>1,'msg'=>'网络开小差了,请重试一次','data'=>array());
        } else {
            $this->db->trans_commit();
            return array('code'=>0,'msg'=>'注册成功','data'=>array('uid'=>$uid));
        }
    }

	
}