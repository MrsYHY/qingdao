<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '设备详情';
$this->params['breadcrumbs'][] = '设备管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view col-md-12">
    <div class="portlet light">
        <div class="portlet-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'device_keyword',
                    'device_name',
                    'sale_name',
                    'shake_num',
                    ['attribute'=>'zone_id','value'=>\common\activeRecords\Zones::findByPk($model->zone_id)->name]
                ],
            ]) ?>
        </div>
    </div>
</div>