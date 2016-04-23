<?php

use backend\widgets\metronic\ActiveForm;

use common\activeRecords\AuthItem;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '页面添加';
$this->params['breadcrumbs'][] ='设备管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
            <?php $form->errorSummary($deviceForm); ?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <div class="row">
                    <?= $form->field($deviceForm,'pageTitle')?>
                </div>
                <div class="row"><?= $form->field($deviceForm,'pageDescription')?></div>
                <div class="row"><?= $form->field($deviceForm,'pagePageUrl')?></div>
                <div class="row"><?= $form->field($deviceForm,'pageIconUpload')->fileInput()?></div>
                <div class="row"><?= $form->field($deviceForm,'pageComment')?></div>
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