<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = '添加菜单';
$this->params['breadcrumbs'] = ['label' => '菜单管理', 'url' => 'menu/index'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-body form">
            <?php $form = \backend\widgets\metronic\ActiveForm::begin();?>
            <!-- BEGIN FORM-->
            <div class="form-body">
                <?= $form->field($model,'route')?>
                <?= $form->field($model,'name')?>
                <?= $form->field($model,'parent')->linkDropDownList('parent2',['0'=>'请选择']+\yii\helpers\ArrayHelper::map(\common\activeRecords\Menu::find()->where('parent = 0')->all(),'id','name'))?>
                <?= $form->field($model,'icon')->iconSelect()?>
                <?= $form->field($model,'display')->switchCheckBox()?>
                <?= $form->field($model,'index')->rangeInput()?>
                <?= $form->field($model,'desc')->textarea()?>
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

    $("#menuform-parent").change(function(){
        if(!$(this).val()){
            setParent2Option({'0':'请选择'});
            return;
        }
        $.ajax({
            url:'<?= \yii\helpers\Url::toRoute(['menu/ajax-get-childen-menu'])?>',
            data:{'parent_id':$(this).val()},
            dataType:'json',
            success:function(response){
                setParent2Option(response.data);
            }
        });
    });

    function setParent2Option(data)
    {
        $("#menuform-parent2").html("");
        for(var field in data){
            var option = $("<option></option>")
            option.val(field).text(data[field]);
            $("#menuform-parent2").append(option);
        }

    }
    var option = $("<option></option>").val("0").text("请选择")
    $("#menuform-parent2").append(option);
</script>
