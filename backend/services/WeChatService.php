<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-22
 * Time: 下午9:57
 */

namespace backend\services;


use common\services\BaseService;

class WeChatService extends BaseService{

    /*
     * 一系列微信需要持久化的数据关键字
     *
     */
    const SHAKE_PAGE    = 'SHAKE_PAGE';//摇一摇页面

    static $wechatKeyword = [
        self::SHAKE_PAGE=>'摇一摇页面',
    ];
} 