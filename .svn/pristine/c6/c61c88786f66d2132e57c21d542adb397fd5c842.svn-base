<?php

/* 邮箱验证 */
if(!function_exists('is_email')){
    function is_email($str){
        $is_bool = preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $str);
        return $is_bool;
    }
}

/* 手机验证 */
if(!function_exists('is_phone')){
    function is_phone($str){
        $is_bool = preg_match('/^1[23456789]{1}\d{9}$/',$str);
        return $is_bool;
    }
}

/* 姓名验证 */
if(!function_exists('is_real_name')){
    function is_real_name($str){
        $is_bool = preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$str);
        return $is_bool;
    }
}

/* 身份证验证 */
if(!function_exists('is_identity_no')){
    function is_identity_no($str){
        $is_bool = preg_match('/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/',$str);
        return $is_bool;
    }
}


/* 字符串转义 */
if(!function_exists('string_escapes')){
    function string_escapes($data){
        if (is_array($data)){
            foreach ($data as $k=>&$v)$v = trim(addslashes($v));
        }else{
            $data = trim(addslashes($data));
        }
        return $data;
    }
}


/* 生成用户的唯一token */
if(!function_exists('create_token')){
    function create_token(){
        return sha1(time().rand(1000,9999).'dw!@#');
    }
}

/*  生成密码 */
if(!function_exists('create_password')){
    function create_password($input,$token){
        return md5($input.$token.'!@#dw');
    }
}

/* 验证密码 */
if(!function_exists('identify_password')){
    function identify_password($input,$token,$password){
        return $password == md5($input.$token.'!@#dw') ? true : false;
    }
}

/* 两个时间段的天数差 */
if (!function_exists('diff_days')) {
    function diff_days($old_date,$current_date){
        $old_date = strtotime($old_date);
        $current_date = strtotime($current_date);
        $days = round(($current_date - $old_date)/3600/24) ;
        return $days;
    }
}

/* 异步请求 */
function async_request($url) {
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_TIMEOUT,1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/* 获取用户真实IP */
function get_real_ip()
{
    $ip = false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!preg_match("/^(10│172.16│192.168)./", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/***
 * 多维数组排序
 * @param $data 数组
 * @param $sort_order_field 排序字段
 * @param int $sort_order   排序类型(倒序或顺序)
 * @param int $sort_type    数组排序
 * @return mixed
 */
function array_multi_sort($data,$sort_order_field,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){
    if ($data == null){
        return array();
    }
    foreach($data as $val){
        $key_arrays[]=$val[$sort_order_field];
    }
    array_multisort($key_arrays,SORT_ASC,SORT_NUMERIC,$data);
    return $data;
}


/* 数组(字符串)合并成参数 */
function array_combine_param($data){
    $param = '';
    if (is_array($data)) {
        foreach ($data as $key=>$value) {
            if (count($data) > 1){
                $param .= "{$key}=".$value."&";
            }else{
                $param .= "{$key}=".$value;
            }
        }
    }
    return rtrim($param,"&");
}


/* 保存文件 */
function save_file($file,$name,$dir){
    $success =  move_uploaded_file($file['tmp_name'],$dir.$name);
    return $success;
}

//获取标签内的内容
function filter_tag_content($leftTag,$rightTag,$content){
    preg_match("/$leftTag([^<]*)$rightTag/i", $content, $match);
    log_message('error',json_encode($match));
    if (isset($match[1])){
        return $match[1];
    }
    return false;
}

/**
 * 获取URL的参数
 * @param $url
 * @return array
 */
function get_url_param($url){
    $param_list = array();

    //没有合法参数
    if (!strstr($url,'?') || !strstr($url,'=')){
        return $param_list;
    }
    $param_str = substr($url, strpos($url, '?')+1);
    $param_arr = explode('&',$param_str);
    foreach ($param_arr as $value){
        $param_arr_sub = explode('=',$value);
        $param_list[$param_arr_sub[0]] = $param_arr_sub[1];
    }
    return $param_list;
}


/* 分割中文字符串*/
function mb_str_split($str){
    return preg_split('/(?<!^)(?!$)/u', $str);
}


/* 获取字符串长度 */
function get_str_length($str){
    $length = 0;
    $str_arr = mb_str_split($str);
    foreach($str_arr as $v){
        $length += 1;
    }
    return $length;
}

/*  添加参数到URL  */
if(!function_exists('add_params_to_url')){
    function add_params_to_url(&$url,$params){
        $params_url = '';
        foreach($params as $k=>$v){
            if($k=='page'){
                continue;
            }
            $params_url.='&'.$k.'='.$v;
        }
        if(!strpos($url, '?')){
            $params_url = '?'.substr($params_url,1);
        }
        $url.=$params_url;
    }
}

/**
 * 得到公共的域名，cookie路径使用
 * @return string
 */
function get_public_domain() {
    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
    $host = preg_replace('/:\d+/',"",$host);
    $arrHost = explode('.', $host);
    $arrHostCount = count($arrHost);
    return $arrHostCount > 1 ? $arrHost[$arrHostCount - 2] . '.' . $arrHost[$arrHostCount - 1] : '';
}

function price_format($amount) {
    return number_format($amount, 2, '.', '');
}

/* 获取真实IP */
function getRealIp()
{
    $ip = false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!preg_match("/^(10│172.16│192.168)./", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/* 根据原图地址 获取商品缩略图 */
function getGoodsThumbImg($old_img_src){
    $thumb_src = 'http://image.591zzx.com/pic/thumb/img/upload';
    $arr = explode('/',$old_img_src);
    $year = $arr[1];
    $month = $arr[2];
    $day = $arr[3];
    $img_name = $arr[4];
    $postfix = '/w/200/h/180';
    $thumb_src .= "@_@{$year}@_@{$month}@_@{$day}@_@{$img_name}{$postfix}";
    return $thumb_src;
}

/* 传入等级，返回所需积分 */
function getLevelNeedPoint($level){
    switch ($level){
        case 1:return 588;break;
        case 2:return 3488;break;
        case 3:return 9488;break;
        case 4:return 17988;break;
        case 5:return 28988;break;
        case 6:return 42488;break;
        default:return 0;break;
    }
}

/*
    * 金币等价券规则，返回对应天数对应的金币
    * @param int($total_day) 天数
    * @return $before_amount    金币等价券
    *
    */
function getSignGold($total_day,$last_sign_time){

    /**
     * 判断,昨天,今天是否签到 都没有签到就是漏签了，连续签到的天数从0开始. by john
     */
    if (date('Y-m-d') != date('Y-m-d',strtotime($last_sign_time.'+1 day'))
    && date('Y-m-d',strtotime($last_sign_time)) != date('Y-m-d')
    ){
        $total_day = 0;
    }

    switch ($total_day)
    {
        # 连续签到1天至7天可获得0.1金币
        case $total_day >= 1 && $total_day <= 7:
            $before_amount = 0.1;
            break;
        # 连续签到8天至14天可获得0.2金币
        case $total_day > 7 && $total_day < 15:
            $before_amount = 0.2;
            break;
        # 连续签到15天及以上可获得0.3金币
        case $total_day >= 15:
            $before_amount = 0.3;
            break;
        default:
            $before_amount = 0.1;
            break;
    }
    return $before_amount;
}

function isActivityIng($create_time)
{
    if ($create_time >= '2018-11-01 00:00:00' && $create_time <= '2018-11-30 23:59:59') {
        return true;
    }
    return false;
}

function isActivityIngNew($create_time)
{
    if ($create_time >= '2019-01-15 00:00:00' && $create_time <= '2020-01-15 23:59:59') {
        return true;
    }
    return false;
}


/**
 * @param $url
 * @return bool
 */
 function checkSign($params,$ticket){
    if(empty($params)){
        return false;
    }
    if(!isset($params['sign'])){
        return false;
    };
    $sign = $params['sign'];
    $paramsStr = getCheckSignStr($params);
    if(!$paramsStr){
        return false;
    }
    //第一次加密
    $paramsSortStrMD5 = strtoupper(md5($paramsStr));
    //第二次加密
    $paramsStr = $paramsSortStrMD5.$ticket;
    $checkSign = strtoupper(md5($paramsStr));
    if($sign != $checkSign){
        return false;
    }
    return true;
}

 function getCheckSignStr($params){
    ksort($params);//字典序升序
    $paramsSortStr = '';
    if(!empty($params) && is_array($params)){
        $i = 0;
        $arraySize = sizeof($params);
        foreach ($params as $key => $value){
            $i++;
            if($key == 'sign'){
                continue;
            }
            if($i < $arraySize){
                $paramsSortStr .= $key . '=' . $value . '&';
            }else{
                $paramsSortStr .= $key . '=' . $value;
            }
        }
    }
    return $paramsSortStr;
}

/**
 * 随机生成32位字符串
 * @return string
 */
 function create_guid(){
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);
    $dec_hex = dechex($a_dec* 1000000);
    $sec_hex = dechex($a_sec);
     $dec_hex = ensure_length($dec_hex, 5);
     $sec_hex = ensure_length($sec_hex, 6);
    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '';
    $guid .= create_guid_section(4);
    $guid .= '';
    $guid .= create_guid_section(4);
    $guid .= '';
    $guid .= create_guid_section(4);
    $guid .= '';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);
    return $guid;
}

/**
 * @param $string
 * @param $length
 */
 function ensure_length($string, $length){
    $strlen = strlen($string);
    if($strlen < $length)
    {
        $string = str_pad($string,$length,"0");
    }
    else if($strlen > $length)
    {
        $string = substr($string, 0, $length);
    }
    return $string;
}

/**
 * @param $characters
 * @return string
 */
function create_guid_section($characters){
    $return = "";
    for($i=0; $i<$characters; $i++)
    {
        $return .= dechex(mt_rand(0,15));
    }
    return $return;
}






