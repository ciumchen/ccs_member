<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {

    //注册页面
	public function register_page(){
        $getData = $this->_getData;
        $parent_mobile = 0;

        //是否有推荐人ID
        if (isset($getData['pid'])){
            $parent_id = $this->secret->decrypt_url($getData['pid'],SECRET_STR);
            $parent_info = $this->m_users->get_user_info(array('uid'=>$parent_id));
            if ($parent_info){
                $parent_mobile = $parent_info['mobile'];
            }
        }
        $this->_viewData['parent_mobile'] = $parent_mobile;
		parent::view('register_page');
	}

    /* 注册提交 */
    public function check_register(){
        $postData = $this->_postData;
        if (!isset($postData['true_name']) || !isset($postData['mobile']) || !isset($postData['mobile_code']) || !isset($postData['password']) || !isset($postData['confirm_password']) || !isset($postData['parent_mobile'])){
            $this->response(array('code'=>101,'msg'=>"参数缺失",'data'=>array()));
        }
        $postData['login_type'] = 1;
        $ret_data = $this->m_users->checkRegister($postData);
        $this->response($ret_data);
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
