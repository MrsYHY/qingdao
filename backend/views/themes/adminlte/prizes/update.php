<?php


use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '修改奖品';
$this->params['breadcrumbs'][] ='活动管理';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = '修改';
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = \backend\widgets\metronic\ActiveForm::begin(['options'=>['class'=>'form-horizontal']])?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <?= $form->field($model,'activity_id')->hiddenInput();?>
                <?= $form->field($model,'activityName')?>
                <?= $form->field($model,'keyword')->dropDownList(\common\activeRecords\Prizes::$prizes)?>
                <?= $form->field($model,'prize_level')->dropDownList([''=>'请选择',1=>'一等奖',2=>'二等奖',3=>'三等奖',4=>'四等奖',5=>'五等奖'])?>
                <?= $form->field($model,'name')?>
                <?= $form->field($model,'num')?>
                <?= $form->field($model,'win_rate')?>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">修改</button>
                    </div>
                </div>
            </div>
            <?php $form->end()?>
        </div>
    </div>
</div>
