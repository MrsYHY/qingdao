<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-23
 * Time: 上午11:35
 */

namespace common\rewritingDependency;


use callmez\wechat\sdk\MpWechat;

class ReWrite_MpWechat extends MpWechat{

    /**
     * 上传文件请使用该类来解决curl版本兼容问题
     * @param $filePath
     * @return \CURLFile|string
     */
    public  function uploadFile($filePath)
    {
        // php 5.5将抛弃@写法,引用CURLFile类来实现 @see http://segmentfault.com/a/1190000000725185
        return class_exists('\CURLFile') ? new \CURLFile($filePath) : '@' . $filePath;
    }

} 