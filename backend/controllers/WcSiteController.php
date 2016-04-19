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

} 