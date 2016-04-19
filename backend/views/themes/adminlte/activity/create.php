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

$this->title = Yii::t('backend','activity list');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="activity-create">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'start_time')->widget(
            DatePicker::className(), [
            // inline too, not bad
            'inline' => true,
            // modify template for custom rendering
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]); ?>
        <?= $form->field($model, 'end_time')->widget(
            DatePicker::className(), [
            // inline too, not bad
            'inline' => true,
            // modify template for custom rendering
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]); ?>
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'content')->textarea(['row'=>10]) ?>
    
        <div class="form-group">
            <?= Html::submitButton('创建活动', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- activity-create -->
