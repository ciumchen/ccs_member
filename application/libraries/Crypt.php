<?php
/**
 * @copyright (c) 2011 jooyea.cn
 * @file crypt_class.php
 * @brief 加密解密
 * @author RogueWolf
 * @date 2010-12-2
 * @version 0.6
 * @note
 */

/**
 * @class ICrypt
 * @brief ICrypt 加密解密类  (用于商城同步)
 * @note
 */
class Crypt
{
    /**
     * @brief  MD5加密方法
     * @param  string $string  字符串
     * @return string $string  加密后的字符串
     */
    public function md5($string)
    {
        return md5($string);
    }

    /**
     * @brief base64加密方法
     * @param  String $str 字符串
     * @return String $str 加密后字符串
     */
    public function base64encode($str)
    {
        return base64_encode($str);
    }

    /**
     * @brief  base64解密方法
     * @param  String $str  字符串
     * @return String $str  解密后的字符串
     */
    public function base64decode($str)
    {
        return base64_decode($str);
    }

    /**
     * @brief 动态解密函数
     * @param  String $string 字符串
     * @param  String $key    解密私钥
     * @param  Int    $expiry 保留时间，默认为0，即为不限制
     * @return String $str    解密后的字符串
     */
    public  function decode($string, $key='', $expiry=0)
    {
        return $this->code($string,'decode', $key, $expiry);
    }

    /**
     * @brief 动态解密函数
     * @param  String  $string 字符串
     * @param  String  $key    加密私钥
     * @param  Int     $expiry 保留时间，默认为0，即为不限制
     * @return $string String  加密后的字符串
     */
    public function encode($string, $key='', $expiry=0)
    {
        return $this->code($string,'encode', $key, $expiry);
    }
    /**
     * @brief 加密解密算法
     * @param  String  $string  要处理的字符串
     * @param  String  $op      处理方式，加密或者解密，默认为decode即为解密
     * @param  Int     $expiry  保留时间，默认为0即为不限制
     * @return String  $string  处理后的字符串
     */
    private function code($string, $op="decode", $key='', $expiry=0)
    {
        $op=strtolower($op);
        $key_length=10;
        $key=md5($key?$key:"jooyea");
        //生成256长度的密码
        $key_1=md5(substr($key,0,4));
        $key_2=md5(substr($key,4,4));
        $key_3=md5(substr($key,8,4));
        $key_4=md5(substr($key,12,4));
        $key_5=md5(substr($key,16,4));
        $key_6=md5(substr($key,20,4));
        $key_7=md5(substr($key,24,4));
        $key_8=md5(substr($key,28,4));
        $key_e= $key_length ? ($op == 'decode' ? substr($string, 0, $key_length): substr(md5(microtime()), -$key_length)) : '';
        $cryptkey = md5($key_1|$key_e).md5($key_3|$key_e).md5($key_5|$key_e).md5($key_7|$key_e).md5($key_8|$key_e).md5($key_6|$key_e).md5($key_4|$key_e).md5($key_2|$key_e);
        $cryptkey_length = strlen($cryptkey);
        $string = $op == 'decode' ? $this->base64decode(substr($string, $key_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$key_5), 0, 22).$string;
        $string_length = strlen($string);
        $result="";
        //通过循环的方式异或的方式加密，异或方式是加密中常用的一种处理方式
        for($i = 0; $i < $string_length; $i++)
        {
            $number = $i % 256;
            $result .= chr(ord($string[$i]) ^ ($cryptkey[$number]));
        }
        //解码部分
        if($op == 'decode')
        {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 22) == substr(md5(substr($result, 32).$key_5), 0, 22))
            {
                return substr($result, 32);
            } else
            {
                return '';
            }
        }
        else
        {
            return $key_e.str_replace('=', '', $this->base64encode($result));
        }
    }

    //加密函数
    function lock_url($txt,$key='change')
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0,64);
        $ch = $chars[$nh];
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
                $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
        $tmp .= $chars[$j];
        }
        return urlencode($ch.$tmp);
    }

    //解密函数
    function unlock_url($txt,$key='change')
    {
        $txt = urldecode($txt);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch = $txt[0];
        $nh = strpos($chars,$ch);
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = substr($txt,1);
        $tmp = '';
        $i=0;$j=0; $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
                $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
        while ($j<0) $j+=64;
        $tmp .= $chars[$j];
        }
        return base64_decode($tmp);
    }
}
