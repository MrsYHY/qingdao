<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '大区列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title?></h3>
        <div class="box-tools">
            <div class="input-group input-group-sm" >
                <a class="btn btn-circle btn-default btn-sm" href="<?= \yii\helpers\Url::toRoute('create')?>">
                    <i class="fa fa-plus"></i><span class="hidden-480">添加大区</span>
                </a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'columns' => [
//                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',

                ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
            ],
        ]); ?>
    </div>
</div>
</div>
