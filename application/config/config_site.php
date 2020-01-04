<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 数据库配置 */
/*
$config['db_hostname'] = '119.29.87.200';
$config['db_username'] = 'ccs168';
$config['db_password'] = 'ccs168@$^';
$config['db_database'] = 'ccs168';
*/
$config['db_hostname'] = '123.207.254.249';
$config['db_username'] = 'root';
$config['db_password'] = 'Kaifazhe1';
$config['db_database'] = 'ccs168';

/* 数据库配置(原供聚会联数据库) */
$config['db_hostname_two'] = '123.207.254.249';
$config['db_username_two'] = 'root';
$config['db_password_two'] = 'Kaifazhe1';
$config['db_database_two'] = "gongjuhuilian";

/**
 * 商城数据库
 */
$config['db_hostname_mall'] = '123.207.254.249';
$config['db_username_mall'] = 'root';
$config['db_password_mall'] = 'Kaifazhe1';
$config['db_database_mall'] = 'new_wdm150804';



/* 网站配置 */
if (filter_input(INPUT_SERVER, 'HTTPS'))
{
    $config['domain'] = 'https://'.filter_input(INPUT_SERVER, 'HTTP_HOST'); //主域名
}
else
{
    $config['domain'] = 'http://'.filter_input(INPUT_SERVER, 'HTTP_HOST'); //主域名
}

$config['bonus_percent'] = [ //"等级"=>"奖金比例"

    'person_comm' =>[ //个人消费比例
        0=>0.20,
        1=>0.20,
        2=>0.22,
        3=>0.24,
        4=>0.26,
        5=>0.28,
        6=>0.30
    ],
    'child_comm'=>[ //直接推荐比例
        0=>0.20,
        1=>0.26,
        2=>0.28,
        3=>0.30,
        4=>0.32,
        5=>0.34,
        6=>0.36
    ],
    'supplier_comm'=>0.02 //供应商比例
] ;
$config['mini_sales'] = [  //"等级"=>"最低消费"
    0=>0,
    1=>300,
    2=>600,
    3=>900,
    4=>1200,
    5=>1500,
    6=>1800
] ;

$config['child_point'] = [ //上线得到的积分比例
    0=>1, //自己100%
    1=>0.5,//上线
    2=>0.25,//上上线
    3=>0.125//上上上线
] ;
$config['level_point'] = [ //升级等级积分
    1=>588,
    2=>3488,
    3=>9488,
    4=>17988,
    5=>28988,
    6=>42488,
] ;

//奖金类型
$config['reward_type'] = [
    1=>'个人消费奖',
    2=>'分享奖',
    3=>'供应商推荐奖',
    4=>'开拓和管理奖',
    5=>'经营奖',
    7=>'商品退货',
];

//奖金状态
$config['reward_status'] = [
    0=>'待入账',
    1=>'已入账'
];


//用户状态
$config['user_status'] = [
    1=>'正常',
    2=>'冻结'
];

//提现状态
$config['withdraw_status'] = [
    1=>'待处理',
    2=>'已处理',
    3=>'已驳回'
];

//提现到
$config['withdraw_to'] = array(
    1=>'支付宝',
    2=>'银行卡'
);

//后台权限
$config['admin_role'] = [
    1=>'超级管理员',
    2=>'客服'
];
//管理员账号状态
$config['admin_status'] = [
    1=>'正常',
    2=>'冻结'
];

/* 商城同步参数 */
$config['mall_param'] = [
    'name'=>'臻之选',
    'domain'=>'mall.591zzx.com',
    'cookie_pre'=>'',
    'cookie_key'=>'',
    'secret_key'=>'5c1f5927bfc2992f831ce9ed31d4f12d',
    'is_open'=>'1',
    'status'=>'enabled',
    'create_time'=>'2018-04-29 14:45:40',
    'modify_time'=>'2018-06-01 19:23:14',
    'sort'=>'999'
];

/* 消息类型 */
$config['message_type'] = [
    1=>"奖金",
    2=>"团队",
    3=>'订单',
    4=>'积分',
    10=>'系统',
    100=>'客服'
];

//微信公众号参数
$config['wx'] = array(
    'appID'=>'wxccbd5e4790510e4c',
    'appSecret'=>'a3b6c9d545a76e6c7a2dcf99f60d2acb',
    'redirect_uri'=>'http://h5.ccs168.cn/api/wechat_user_info'
);

#切换新商城
$config['is_new_mall'] = true;
$config['new_mall_site'] = 'testmall.ccs168.cn';
$config['old_mall_site'] = 'mall.591zzx.com';