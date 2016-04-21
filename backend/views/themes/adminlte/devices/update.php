<?php


use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '修改设备';
$this->params['breadcrumbs'][] ='设备管理';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = '修改';
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = \backend\widgets\metronic\ActiveForm::begin(['options'=>['class'=>'form-horizontal']])?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <?= $form->field($model, 'user_id') ?>
                <?= $form->field($model, 'content')->textarea(['row'=>10]) ?>
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
