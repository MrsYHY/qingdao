<?php
/**
 * 抽奖api
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:46
 */

namespace backend\controllers;


use backend\services\ApiLuckDrawService;
use common\controller\BaseRestController;

class ApiLuckDrawController extends  BaseRestController{

    protected $service;
    public function getService(){
        if ( $this->service instanceof ApiLuckDrawService ){
            return $this->service;
        }
        $this->service = new ApiLuckDrawService();
        return $this->service;
    }


    /**
     * 抽奖操作接口
     */
    public function actionLuckDraw(){

    }


    /**
     * 中奖纪录
     */
    public function actionGetWinRecord(){

    }


} 