<?php

use backend\widgets\metronic\ActiveForm;

use common\activeRecords\AuthItem;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '奖品创建';
$this->params['breadcrumbs'][] ='活动管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = ActiveForm::begin()?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <?= $form->field($model,'activity_id')->hiddenInput();?>
                <?= $form->field($model,'activityName')?>
                <?= $form->field($model,'keyword')->dropDownList(\common\activeRecords\Prizes::$prizes)?>
                <?= $form->field($model,'prize_level')->dropDownList([''=>'请选择',0=>'特等奖',1=>'一等奖',2=>'二等奖',3=>'三等奖',4=>'四等奖',5=>'五等奖'])?>
                <?= $form->field($model,'name')?>
                <?= $form->field($model,'num')?>
                <?= $form->field($model,'win_rate')?>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">增加</button>
                    </div>
                </div>
            </div>
            <?php $form->end()?>
        </div>
    </div>
</div>
<script>
    $("#authitem-wait").change(function(){
        $("#authitem-name").val($("#authitem-wait").val());
        $("#authitem-type").val(2);
    });
</script>