<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-23
 * Time: 下午9:57
 */

namespace backend\services;


use backend\forms\LuckDrawResultForm;
use common\activeRecords\LuckDrawResult;
use common\services\BaseService;

class StatisticsService extends BaseService{

    public function searchForLuckDrawResult(LuckDrawResultForm $luckDrawResultForm){
        $dataProvider = LuckDrawResult::search($luckDrawResultForm->getAttributes());
        return $dataProvider;
    }
} 