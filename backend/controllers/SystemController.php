<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 下午1:16
 */
namespace backend\controllers;

use common\controller\BaseController;

class SystemController extends BaseController{

    public function actionSystemDictionaryManage(){
        $this->render('sysDictionaryManage',[]);
    }
}