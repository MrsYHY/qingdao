<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:52
 */

namespace backend\controllers;


use backend\services\WcSiteService;
use common\controller\BaseController;

class WcSiteController extends BaseController{

    protected $service;
    public function getService(){
        if ( $this->service instanceof WcSiteService ){
            return $this->service;
        }
        $this->service = new WcSiteService();
        return $this->service;
    }

    public function actionWeChat(){

    }

    public function actionIndex(){
        $this->layout = 'weixin';
        return $this->render('index');
    }

    /**
     * 摇奖请求接口
     */
    public function actionLuckDraw(){
        $service = $this->getService();
        $params = $this->request();

        //判断是否关注

        //判断活动是否合法

        //判断设备id是否合法


    }

    /**
     * 摇奖结果页
     */
    public function actionLuckDrawResult(){

    }
    

} 