<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\activeRecords\Activitys */
/* @var $form ActiveForm */


/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '创建大区';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="activity-create">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name') ?>

        <div class="form-group">
            <?= Html::submitButton('创建活动', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div><!-- activity-create -->
</div>
