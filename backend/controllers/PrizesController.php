<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午10:48
 */

namespace backend\controllers;

use common\activeRecords\Prizes;
use common\controller\BaseController;

class PrizesController extends BaseController{

    public function actionCreate(){

        $model = new Prizes();

        return $this->render('create',compact('model'));

    }

    public function actionView(){

    }

    public function actionUpdate(){

    }

    public function actionDelete(){

    }

} 