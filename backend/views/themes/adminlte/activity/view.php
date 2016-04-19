<?php

use backend\widgets\metronic\DetailView;
use common\activeRecords\AuthItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '活动详情';
$this->params['breadcrumbs'][] = '活动管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title?></h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" >
                        <a class="btn btn-circle btn-default btn-sm" href="<?= \yii\helpers\Url::toRoute('prize-create')?>">
                            <i class="fa fa-plus"></i><span class="hidden-480">添加奖品</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="portlet green-meadow box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>活动详细
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?= yii\widgets\DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        //'id',
                                        'title',
                                        'content',
                                        'start_time',
                                        'end_time'
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="portlet blue-hoki box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>奖品
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?= \backend\widgets\metronic\GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'name',
                                        'prize_level',
                                        'num',
                                        'win_rate'
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
