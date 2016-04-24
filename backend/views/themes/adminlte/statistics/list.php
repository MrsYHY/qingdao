<?php

use backend\widgets\adminLte\GridView;
use yii\helpers\Html;
use dosamigos\datepicker\DatePicker;

use backend\widgets\metronic\ActiveForm;
use \common\activeRecords\LuckDrawResult;

use common\activeRecords\AuthItem;


/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '摇奖统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = ActiveForm::begin()?>
            <?php $form->errorSummary($luckDrawForm); ?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <div class="row">
                    <?= $form->field($luckDrawForm,'activity_id')->dropDownList([''=>'全部']+\yii\helpers\ArrayHelper::map(\common\activeRecords\Activitys::find()->all(),'id','title'));?>
                </div>
                <div class="row"><?= $form->field($luckDrawForm,'result')->dropDownList([''=>'全部',LuckDrawResult::ZHONG=>'中奖',LuckDrawResult::NOT_ZHONG=>'没中奖'])?></div>
<!--                <div class="row">--><?php
//                    echo $form->field($luckDrawForm,'start_created_at')->datetimePicker();
//                     ?>
<!--                    --><?php //echo $form->field($luckDrawForm,'end_created_at')->datetimePicker();?>
<!--                </div>-->
                <div class="row"><?= $form->field($luckDrawForm,'is_award')->dropDownList([''=>'全部',LuckDrawResult::AWARD=>'已兑奖',LuckDrawResult::NOT_AWARD=>'未中奖'])?></div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">查询</button>
                    </div>
                </div>
            </div>
            <?php $form->end()?>
        </div>
    </div>
</div>
</div>