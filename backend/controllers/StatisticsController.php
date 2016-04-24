<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-23
 * Time: 下午9:34
 */

namespace backend\controllers;


use backend\forms\LuckDrawResultForm;
use backend\services\StatisticsService;
use common\activeRecords\LuckDrawResult;
use common\controller\BaseController;
use common\forms\BaseForm;

class StatisticsController extends BaseController{

    protected $service;
    public function getService(){
        if ($this->service instanceof StatisticsService) {
            return $this->service;
        }
        $this->service = new StatisticsService();
        return $this->service;
    }
    /**
     * 摇奖统计
     */
    public function actionLuckDrawList(){

        $luckDrawForm = new LuckDrawResultForm();
        if ($luckDrawForm->submit()) {
            if ($luckDrawForm->validate()) {
                $service = $this->getService();
                $dataProvider = $service->searchForLuckDrawResult($luckDrawForm);
                return $this->render('list_search',compact('dataProvider','luckDrawForm'));
            }
        }
        return $this->render('list',compact('luckDrawForm'));
    }
} 