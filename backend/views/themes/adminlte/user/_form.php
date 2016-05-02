<?php

use backend\widgets\metronic\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form backend\widgets\metronic\ActiveForm */
?>
    <?php $form = ActiveForm::begin(); ?>
    <?php $form->errorSummary($model); ?>
    <div class="form-body">

        <?= $form->field($model, 'username')->textInput(['maxlength' => 255,'disabled'=>$model->isNewRecord?false:true])->error() ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'rePassword')->passwordInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'authList')->multiSelect($authList)?>

    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>


