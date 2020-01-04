<?php

/* 加密、解密库 */
class Secret {
    function keyED($txt,$encrypt_key)
    {
        $encrypt_key =    md5($encrypt_key);
        $ctr=0;
        $tmp = "";
        for($i=0;$i<strlen($txt);$i++)
        {
            if ($ctr==strlen($encrypt_key))
                $ctr=0;
            $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
            $ctr++;
        }
        return $tmp;
    }

    function encrypt($txt,$key)
    {
        $encrypt_key = md5(mt_rand(0,100));
        $ctr=0;
        $tmp = "";
        for ($i=0;$i<strlen($txt);$i++)
        {
            if ($ctr==strlen($encrypt_key))
                $ctr=0;
            $tmp.=substr($encrypt_key,$ctr,1) . (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
            $ctr++;
        }
        return $this->keyED($tmp,$key);
    }


    function decrypt($txt,$key){
        $txt = $this->keyED($txt,$key);
        $tmp = "";
        for($i=0;$i<strlen($txt);$i++)
        {
            $md5 = substr($txt,$i,1);
            $i++;
            $tmp.= (substr($txt,$i,1) ^ $md5);
        }
        return $tmp;
    }



    /* 加密函数 */
    function encrypt_url($url,$key){
        return rawurlencode(base64_encode($this->encrypt($url,$key)));
    }


    /* 解密 */
    function decrypt_url($url,$key){
        return $this->decrypt(base64_decode(rawurldecode($url)),$key);
    }

}

