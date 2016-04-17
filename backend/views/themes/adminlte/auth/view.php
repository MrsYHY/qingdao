<?php

use backend\widgets\metronic\DetailView;
use common\activeRecords\AuthItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '权限管理';
//$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['auth/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="portlet green-meadow box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>权限详细
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?= yii\widgets\DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        'name',
                                        array('name'=>'type','label'=>'类别','value'=>AuthItem::$typeData[$model->type]),
                                        'description:ntext',
                                        'data:ntext',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="portlet blue-hoki box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>子权限
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?= \backend\widgets\metronic\GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'child0.name',
                                        array('attribute'=>'child0.type','value'=>function($data){return AuthItem::$typeData[$data->child0->type];}),
                                        'child0.description:ntext',
                                    ],
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
