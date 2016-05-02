<?php

use backend\widgets\metronic\ActiveForm;

use common\activeRecords\AuthItem;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '增加权限资源';
$this->params['breadcrumbs'][] ='权限管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = ActiveForm::begin()?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <div class="form-group field-authitem-type required ">
                    <label class="col-md-2 control-label" for="authitem-wait">未添加的权限</label>
                    <div class="col-md-4">
                        <?php if(count($routes)>0)?>
                        <?= Html::dropDownList('auths','',[''=>'请选择']+$routes,['id'=>'authitem-wait','class'=>'form-control'])?>
                    </div>
                </div>
                <?= $form->field($model,'name')?>
                <?= $form->field($model,'type')->dropDownList(array(''=>'请选择')+AuthItem::$typeData)?>
                <?= $form->field($model,'description')?>
                <?= $form->field($model,'data')?>
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