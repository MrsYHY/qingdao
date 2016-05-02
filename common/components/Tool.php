<?php
/**
 * 
 * @author: shenchao
 * @since: 2016-04-05
 */

namespace common\components;


class Tool {

    public static function toUTF8($str){
        $encode = mb_detect_encoding($str, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        if($encode !== "UTF-8"){
            $str = iconv($encode,"UTF-8",$str);
        }
        return $str;
    }


    public static function randAbc($length=""){//返回随机字符串
        $str="abcdefghijklmnopqrstuvwxyz0123456789";
        $str = str_shuffle($str);
        return substr($str,0,$length);
    }

}