<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-23
 * Time: 下午9:34
 */

namespace backend\controllers;


use backend\components\SearchModel;
use backend\forms\LuckDrawResultForm;
use backend\services\StatisticsService;
use common\activeRecords\Activitys;
use common\activeRecords\Devices;
use common\activeRecords\LuckDrawResult;
use common\activeRecords\Prizes;
use common\activeRecords\TerminalUser;
use common\controller\BaseController;
use common\forms\BaseForm;
use common\widgets\ExcelGenerator;

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
        $page = $this->request('page');
//        var_dump($this->request());die;
        if ($luckDrawForm->submit()) {
            if ($luckDrawForm->validate()) {
                $service = $this->getService();//echo $this->request('excel');die;
                if ($this->request('excel') === 'excel'){
                    $service->excel($luckDrawForm);
                }else{
                    $dataProvider = $service->searchForLuckDrawResult($luckDrawForm);
                    return $this->render('list_search',compact('dataProvider','luckDrawForm'));
                }
            }
        }
        return $this->render('list',compact('luckDrawForm'));
    }
    public function actionExport() {
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        $service = $this->getService();
        $luckDrawForm = new LuckDrawResultForm();
        $luckDrawForm->submit();

    }
} 