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

}