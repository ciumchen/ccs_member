<?php
/**
 * 验证码Model
 */
class M_user_mobile_code extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查询验证码
     * @param array  $where 查询条件
     * @param string $field 查询字段
     * @return mixed
     */
    public function get_mobile_code($where,$field = "*"){
        $code_info = $this->db->select($field)->where($where)->order_by('id','desc')->get('m_user_mobile_code')->row_array();
        return $code_info;
    }

    /* 发送短信 */
    public function send_sms($param){

        //验证手机号
        if (!is_phone($param['mobile'])) {
            return array('code'=>1001,'msg'=>'请输入正确的手机号码','data'=>array());
        }

        //随机六位数验证码
        $mobile_code = rand(100000,999999);

        //查询用户信息
        $mobile = $param['mobile'];
        $user_info = $this->m_users->get_user_info(array('mobile'=>$mobile),'uid');

        //1.注册类型
        if ($param['sms_type'] == REGISTER) {
            $template = '107630';
            if ($user_info) {
                return array('code'=>1001,'msg'=>'手机号码已被注册','data'=>array());
            }
        }

        //2.短信登录类型
        if ($param['sms_type'] == SMS_LOGIN){
            $template = '107630';
            if ($user_info == null) {
                return array('code'=>1001,'msg'=>'该手机号码还未注册','data'=>array());
            }
        }

        if($param['sms_type'] == RESET_PASSWORD){
            $template = '156085';
            if ($user_info == null) {
                return array('code'=>1001,'msg'=>'该手机号码还未注册','data'=>array());
            }
        }

        if($param['sms_type'] == SET_PAY_PASSWORD){
            $template = '156085';
            if ($user_info == null) {
                return array('code'=>1001,'msg'=>'该手机号码还未注册','data'=>array());
            }
        }

        //发送验证码,并添加到数据库
        if (sendLoginSms($mobile,$mobile_code,$template)){
            $expire_time = date('Y-m-d H:i:s',time() + 600);
            $this->db->insert('m_user_mobile_code',array(
                'mobile'=>$mobile,
                'code'=>$mobile_code,
                'code_type'=>$param['sms_type'],
                'expire_time'=>$expire_time
            ));
            return array('code'=>100,'msg'=>'验证码已发送','data'=>array());
        }
        return array('code'=>102,'msg'=>'验证码发送失败，请重试','data'=>array());
    }
}
