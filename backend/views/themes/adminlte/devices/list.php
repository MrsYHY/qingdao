<?php

use backend\widgets\adminLte\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "设备列表";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title?></h3>
        <div class="box-tools">
            <div class="input-group input-group-sm" >
                <a class="btn btn-circle btn-default btn-sm" href="<?= \yii\helpers\Url::toRoute('create')?>">
                    <i class="fa fa-plus"></i><span class="hidden-480">添加设备</span>
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
                            'device_name',
                            'device_keyword',
                            'sale_name',
                            'shake_num',
                            ['attribute'=>'join_num','label'=>'参与人数','value'=>function($model){return \common\activeRecords\LuckDrawResult::getsBydeviceId($model->id);}],
                            ['attribute'=>'zone_id','value'=>function($model){
                                    $zone = \common\activeRecords\Zones::findByPk($model->zone_id);
                                    if (!empty($zone)){return $zone->name;}else{return '未知所在大区';}
                                }],
                            ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{view} {update} {delete}'],
                        ],
                    ]); ?>
                    <?php $pajax->end();?>
                </div>
            </div>
        </div>
    </div>
</div>