<?php

/**
 * 发送登录注册短信
 * @param $phone 手机号
 * @param $mobile_code  验证码
 * @param $template_id  模板ID
 * @return bool
 */
function sendLoginSms($phone,$mobile_code,$template_id){

    //AppID,1400开头
    $appid = 1400082658;

    // 短信应用SDK AppKey
    $appkey = "25ca223e4fef21937ab46b8612d4fb21";

    // 短信模板ID
    $templateId = $template_id;

    //签名
    $smsSign = "供聚会联";

    //验证码
    $code = $mobile_code;

    //重置密码
    if ($templateId == '156085'){
        $params = array($code);
    }else{

        //提示有效期
        $minute = 5 ;
        $params = array($code,$minute);
    }

    $rst = sendQcloudSms($appid,$appkey,"86", $phone, $templateId,$params, $smsSign, "", "");
    $res = json_decode($rst,true);
    if($res['result']==0){
        return true;
    }else{
        return false ;
    }
}

function sendQcloudSms($appid,$appkey,$nationCode, $phoneNumber, $templId = 0, $params, $sign = "", $extend = "", $ext = ""){


    $random = rand(100000, 999999);
    $curTime = time();
    $url = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms";
    $wholeUrl = $url . "?sdkappid=" . $appid . "&random=" . $random;

    // 按照协议组织 post 包体
    $data = new \stdClass();
    $tel = new \stdClass();
    $tel->nationcode = "".$nationCode;
    $tel->mobile = "".$phoneNumber;

    $data->tel = $tel;
    $data->sig = calculateSigForTempl($appkey, $random,$curTime, $phoneNumber);
    $data->tpl_id = $templId;
    $data->params = $params;
    $data->sign = $sign;
    $data->time = $curTime;
    $data->extend = $extend;
    $data->ext = $ext;

    return sendCurlPost($wholeUrl, $data);
}

function calculateSigForTempl($appkey, $random,$curTime, $phoneNumbers)
{
    $phoneNumbers = array($phoneNumbers);

    $phoneNumbersString = $phoneNumbers[0];
    for ($i = 1; $i < count($phoneNumbers); $i++) {
        $phoneNumbersString .= ("," . $phoneNumbers[$i]);
    }

    return hash("sha256", "appkey=".$appkey."&random=".$random
        ."&time=".$curTime."&mobile=".$phoneNumbersString);
}



function sendCurlPost($url, $dataObj)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $ret = curl_exec($curl);
    if (false == $ret) {
        // curl_exec failed
        $result = "{ \"result\":" . -2 . ",\"errmsg\":\"" . curl_error($curl) . "\"}";
    } else {
        $rsp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $rsp) {
            $result = "{ \"result\":" . -1 . ",\"errmsg\":\"". $rsp
                . " " . curl_error($curl) ."\"}";
        } else {
            $result = $ret;
        }
    }

    curl_close($curl);

    return $result;
}














