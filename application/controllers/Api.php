<?php
    defined('BASEPATH') OR exit('No direct script access allowed');


    class Api extends MY_Controller {

        public function __construct()
        {
            parent::__construct();
        }

        //微信授权登录
        public function wechat(){
            $wx = config_item('wx');
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
            $url .= 'appid='.$wx['appID'];
            $url .= '&redirect_uri=' . urlencode($wx['redirect_uri']);
            $url .= '&response_type=code';
            $url .= '&scope=snsapi_userinfo';
            $url .= '&#wechat_redirect';
            header('Location:'.$url);
        }

        //获取微信用户资料
        public function wechat_user_info(){

            $wx = config_item('wx');
            $code = $_GET['code'];
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
            $url .= 'appid='.$wx['appID'];
            $url .= '&secret='.$wx['appSecret'];
            $url .= '&code='.$code;
            $url .= '&grant_type=authorization_code';

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );


            $data = curl_exec($ch);
            $data = json_decode($data,true);
            curl_close($ch);

            $access_token = $data['access_token'];
            $openid = $data['openid'];
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
            $res = curl_exec($ch);
            curl_close($ch);

            $wx_info = json_decode($res,true);

            //更新用户信息
            $this->db->where('uid',$this->_userInfo['uid'])->update('m_users',array(
                'image_url'=>$wx_info['headimgurl'],
                'wx_info'=>json_encode($wx_info)
            ));

            $this->ccs168_mall = $this->load->database('ccs168_mall_new', TRUE);

            $res = $this->ccs168_mall->select('userId')->where('userPhone', $this->_userInfo['mobile'])->get('wst_users')->row_array();
            if($res){
                $this->ccs168_mall->where('userId', $res['userId'])->update('wst_users', ['wxOpenId' => $wx_info['openid']]);
            }

            header('Location:'.'/account_manage/index');

        }

        /**
         * 微信解绑操作
         */
        public function wechat_remove(){

            $this->db->where('mobile',$this->_userInfo['mobile'])->update('m_users',['wx_info'=>'']);

            $this->ccs168_mall = $this->load->database('ccs168_mall_new', TRUE);

            $res = $this->ccs168_mall->select('userId')->where('userPhone', $this->_userInfo['mobile'])->get('wst_users')->row_array();
            if($res){
                $this->ccs168_mall->where('userId', $res['userId'])->update('wst_users', ['wxOpenId' => '']);
            }

            $this->response(array('code'=>0,'msg'=>'解绑微信成功','data'=>[]));
        }


    }
