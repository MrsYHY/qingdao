<?php

use backend\widgets\metronic\ActiveForm;
use common\activeRecords\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Html::a('菜单管理',['menu/index']);
$this->params['breadcrumbs'][] =  Html::a($model->name,['menu/view', 'id' => $model->id]);
$this->params['breadcrumbs'][] = '更新';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $model->name?></h3>
                    <div class="box-tools">
                    </div>
                </div>
            </div>
            <div class="box-body">
            <!-- form start -->
                <?php $form = ActiveForm::begin();?>
                    <div class="box-body">
                        <?= $form->field($model,'route')?>
                        <?= $form->field($model,'name')?>
                        <?= $form->field($model,'parent')->linkDropDownList('parent2',[''=>'请选择']+ArrayHelper::map(Menu::find()->where('parent = 0')->all(),'id','name'))?>
                        <?= $form->field($model,'icon')->iconSelect()?>
                        <?= $form->field($model,'display')->switchCheckBox()?>
                        <?= $form->field($model,'index')->rangeInput()?>
                        <?= $form->field($model,'desc')->textarea()?>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-2 col-md-9">
                                    <button type="submit" class="btn green">修改</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $form->end()?>
            </div>
        </div>
    </div>
</div>

<script>
    $("#menuform-parent").change(function(){
        if(!$(this).val()){
            setParent2Option({'':'请选择'});
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
    $(document).ready(function(){
        $("#menuform-parent").change()
    });
</script>
