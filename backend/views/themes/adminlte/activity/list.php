<?php

use backend\widgets\adminLte\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend','activity list');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title?></h3>
        <div class="box-tools">
            <div class="input-group input-group-sm" >
                <a class="btn btn-circle btn-default btn-sm" href="<?= \yii\helpers\Url::toRoute('create')?>">
                    <i class="fa fa-plus"></i><span class="hidden-480">添加活动</span>
                </a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php $pajax = \backend\widgets\Pjax::begin()?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'id',
                            'title',
                            'start_time',
                            'end_time',
                            ['attribute'=>'shakeNum','label'=>'摇一摇总数','value'=>function($model){return \common\activeRecords\LuckDrawResult::find()->where(['activity_id'=>$model->id])->count();}],
                            ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
                        ],
                    ]); ?>
                    <?php $pajax->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>