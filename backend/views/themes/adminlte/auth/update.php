<?php

use yii\helpers\Html;

use common\activeRecords\AuthItem;
/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '修改权限';
$this->params['breadcrumbs'][] ='权限管理';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = '修改';
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = \backend\widgets\metronic\ActiveForm::begin(['options'=>['class'=>'form-horizontal']])?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <?= $form->field($model,'name',['inputTemplate'=>'<div class="col-md-4">{input}</div>'])->label(null,['class'=>'col-md-3 control-label'])?>
                <?= $form->field($model,'type',['inputTemplate'=>'<div class="col-md-4">{input}</div>'])->dropDownList(array(''=>'请选择')+AuthItem::$typeData)->label(null,['class'=>'col-md-3 control-label'])?>
                <?= $form->field($model,'description',['inputTemplate'=>'<div class="col-md-4">{input}</div>'])->label(null,['class'=>'col-md-3 control-label'])?>
                <?= $form->field($model,'data',['inputTemplate'=>'<div class="col-md-4">{input}</div>'])->label(null,['class'=>'col-md-3 control-label'])?>
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
