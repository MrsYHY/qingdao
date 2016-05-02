<?php
namespace common\util;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-28
 * Time: 下午10:49
 */

class Tool{

    /**
     * 递归unset掉含有空格的元素
     * @param $arr array
     */
    public static function arrayFilter(&$arr){
        if(!is_array($arr)){
            return false;
        }
        foreach ($arr as $_key => $_arr){
            if($_arr === ''){
                unset($arr[$_key]);
            }else if(is_array($arr[$_key])){
                if(array_filter($arr[$_key],function($v){if($v === ''){return false;}return true;}) === array()){
                    unset($arr[$_key]);
                }else{
                    self::arrayFilter($arr[$_key]);
                }
            }
        }
    }
}