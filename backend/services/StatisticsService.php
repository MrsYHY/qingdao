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
use common\activeRecords\Activitys;
use common\activeRecords\Devices;
use common\activeRecords\Prizes;
use common\activeRecords\TerminalUser;
use common\widgets\ExcelGenerator;

class StatisticsService extends BaseService{

    public function searchForLuckDrawResult(LuckDrawResultForm $luckDrawResultForm,$isExcel = false){
        $dataProvider = LuckDrawResult::search($luckDrawResultForm->getAttributes(),$isExcel);
        return $dataProvider;
    }

    public function excel($luckDrawForm){
        $dataProvider = $this->searchForLuckDrawResult($luckDrawForm,true);
        $excelHead = [
            'id'=>'统计id',
            'activity_id'=>'活动',
            'device_id'=>'设备id',
            'user_id'=>'用户',
            'result'=>'摇奖结果',
            'prize_id'=>'奖品',
            'prize_level'=>'奖品等级',
            'is_award'=>'是否兑奖',
            'win_code'=>'兑奖码',
            'created_at'=>'摇奖时间'
        ];
        $models = $dataProvider->getModels();
        $d = new ExcelGenerator(['excelHead'=>$excelHead,'dataProvider'=>$models,'filename'=>'摇奖统计','filterCallback'=>function($model){
                $data = [];
                $data [] = $model->id;
                $data [] = Activitys::findByPk($model->activity_id)->title;
                $data [] = Devices::findByPk($model->device_id)->device_keyword;
                $data [] = TerminalUser::findByPk($model->user_id)->terminal_user_token;
                $data [] = $model->result == LuckDrawResult::ZHONG ? '中奖' : '没中奖';
                $prize = Prizes::findByPk($model->prize_id);

                if (empty($prize)) {
                    $data [] = '无';
                    $data [] = '无';
                }else{
                    $data [] = $prize->name;
                    $data [] = Prizes::$prizeLevel[$prize->prize_level];
                }

                if ($model->is_award == LuckDrawResult::AWARD){
                    $data [] = '已兑奖';
                }else if($model->is_award == LuckDrawResult::NOT_AWARD) {
                    $data [] ='未兑奖';
                }else{
                    $data [] = '无';
                }

                $data [] = $model->win_code;
                $data[] = $model->created_at;
                return $data;
            }]);
        echo $d->run();
        \Yii::$app->end();
    }
} 