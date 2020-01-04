<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api_test extends CI_Controller {
    //private $sign = 'Y0TqwKNbAU878tHxaq2tRD959Kk36hprDIJ1uk4QERcdGO+1wtK7CYZC3LoxfjxtE3RtQQYzB05qqPnlqtCARQ==';

    function __construct() {
        parent::__construct();

        ignore_user_abort();
        set_time_limit(0);
        
        header("Content-type: text/html; charset=utf-8"); 
    }

    //curl post提交数据
    private function curl_post($url, $data) {

        $url = 'http://h5.ccs168.cn/'.$url; //https用的是443端口
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
        		
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        		
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        	CURLOPT_HTTPHEADER => array(), //局域网测试，需要设置虚拟域名，不然会自动绕开本地hosts,备注：非外网ip，没测试通过
        );

        $ch = curl_init($url);               
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error\n";
        } else {
            echo $result;
        }
    }    
    
    //curl post提交数据
    private function curl_get($url, $data) {        
        $url_data = 'http://192.168.0.115/'.$url.'?sign=Y0TqwKNbAU878tHxaq2tRD959Kk36hprDIJ1uk4QERcdGO+1wtK7CYZC3LoxfjxtE3RtQQYzB05qqPnlqtCARQ==';
        
        foreach($data as $k=>$v){
            $url_data.='&'.$k.'='.$v;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host:www.dev.com'));
        $result = curl_exec($ch);
               
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
    	if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error\n";
        } else {
            echo $result;
        }
    }

public function index() {
        $data = array(
                'mobile' => '13652388723',
                'parent_mobile' => '13652388722',
                'password' => '123456',
                'year_month' => '2019-03',
                'year_month_day' => '2019-03-16',
        );

        $ticket = 'qmSO2w9IQckmhFcxarAgim2ZRzXxiTYQ';
        $data['nonce'] = create_guid();
        //$data['nonce'] = 'aa50e71cdef34997418b5c80b04459ef';
        $data['timestamp'] = time();

        $paramsStr = getCheckSignStr($data);
        if(!$paramsStr){
            return false;
        }
        //第一次MD5加密
        $paramsSortStrMD5 = strtoupper(md5($paramsStr));

        //第二次密钥后MD5加密
        $paramsStr = $paramsSortStrMD5.$ticket;
        $sign = strtoupper(md5($paramsStr));
        $data['sign'] = $sign;

        $this->curl_post(('Api_member/getMemberIsBuy'), $data);
        //$this->curl_post(('Api_member/register'), $data);
    }
    
    function test_supplier() {
        $data = array(
                'id' => '1285',
                'supplier' => '1312312',
                'address' => '10001111',
                'contact' => '1380100220',
                'telephone' => 1,
                'cellphone' => 6,
                'portal' => 156,
                'email' => 'yuan',
                'qq' => '15919477650',
                'wechat' => '15919477650',
                'aliim' => '21321',
                'invoice_type' => '1',
                'paid_tax' => '4565',
                'payment_cycle' => '1',
                'company_account_bank' => '1',
                'company_account_bank_addr' => '2015-09-01',
                'company_account' => 'uuiui',
                'company_account_name' => '1',
                'company_account_licence_pic' => '0',
                'person_account_bank' => 'usd',
                'person_account_bank_addr' => '1',
                'person_account' => '1',
                'person_account_name' => '15000',
                'person_account_licence_pic' => '0',
                'qualification_info_pic' => '0',
                'operator_id' => '15000',
                'country_code' => '0',
                'secondary_code' => '15000',
                'third_code' => '0',
                'is_shipper' => '1',
                'purchaser_id' => '1100',
                'currency' => 'usd',
                'recommend_id' => '0',
                'status' => '0',
        );
        
        //$this->curl_post(base_url('api/goods/api_test'), $data);

        $this->curl_post('api/misc/supplier_add', $data);
    }
    
    //test
    public function test_order() {
        $data = array(
                    'order_id' => '45545454p6',
                    'order_prop' => 0,
                    'attach_id' => '10001111',
                    'customer_id' => '1380100220',
                    'shopkeeper_id' => 1,
                    //'comm_id' => 6,
                    'area' => 156,
                    'consignee' => 'yuan',
                    'phone' => '15919477650',
                    'reserve_num' => '15919477650',
                    'address' => '中国 广东省 深圳市 南山区 2505',
                    'country_address' => '',
                    'zip_code' => '',
                    'customs_clearance' => '',
                    'deliver_time_type' => '1',
                    'expect_deliver_date' => '2015-09-01',
                    'remark' => 'uuiui',
                    'need_receipt' => '1',
                    'payment_type' => '0',
                    'currency' => 'usd',
                    'currency_rate' => '1',
                    'discount_type' => '1',
                    'goods_amount' => '15000',
                    //'purchase_amount' => '0',
                    'deliver_fee' => '0',
                    'order_amount' => '15000',
                    'format_paid_amount' => '0',
                    'goods_amount_usd' => '15000',
                    'deliver_fee_usd' => '0',
                    'discount_amount_usd' => '25000',
                    'order_amount_usd' => '1100',
                    'order_profit_usd' => '1110',
                    'created_at' => '2016-03-16 17:15:43',
                    'notify_num' => '0',
                    'txn_id' => '0',
                    'pay_time' => '2015-08-28 17:15:43',
                    'store_code' => 'CNSZ',
                    'freight_info' => '',
                    'deliver_time' => '2015-08-28 17:15:43',
                    'receive_time' => '2015-08-28 17:15:43',
                    'status' => '4',
                    //'goods_count' => '2',
                    'updated_at' => '2015-08-28 17:15:43',
                    'is_export_lock' => '0',
                    'is_doba_order' => '0',
                    'doba_supplier_id' => '0',
                    'supplier_id' => '1000',
                    'shipper_id' => '1000',
                    'order_type' => '1',
                    'score_year_month' => '201508', 
                    'goods_list' => array(
                            array(
                                'goods_sn_main' => '30900909',
                                'goods_sn' => '30900909-1',
                                'goods_name' => 'Kelly More 赋活锁龄套餐A K711 + K712 + K716',
                                'supplier_id' => '1000',
                                'store_code' => 'CNSZ',
                                'cate_id' => '52',
                                'goods_attr' => '',
                                'goods_number' => '2',
                                'market_price' => '254.00',
                                'goods_price' => '150.00',
                                'is_doba_goods' => '0',
                             ),
                            array(
                                    'goods_sn_main' => '30900901',
                                    'goods_sn' => '30900901-1',
                                    'goods_name' => 'Kelly More 赋活锁龄套餐A K711 + K712 + K716',
                                    'supplier_id' => '1000',
                                    'store_code' => 'CNSZ',
                                    'cate_id' => '52',
                                    'goods_attr' => '',
                                    'goods_number' => '2',
                                    'market_price' => '254.00',
                                    'goods_price' => '150.00',
                                    'is_doba_goods' => '0',
                            ),
                            ),
                );

        //$this->curl_post(base_url('api/goods/api_test'), $data);
        $data['goods_list'] = serialize($data['goods_list']);
        $this->curl_post('api/order/order_add', $data);
    }
    

}

