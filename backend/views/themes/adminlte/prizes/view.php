<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '奖品详情';
$this->params['breadcrumbs'][] = '活动管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view col-md-12">
    <div class="portlet light">
        <div class="portlet-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    ['attribute'=>'activityName','value'=>\common\activeRecords\Activitys::findByPk($model->activity_id)->title],
                    'name',
                    ['attribute'=>'prize_level','value'=>\common\activeRecords\Prizes::$prizeLevel[$model->prize_level]],
                    'num',
                    'win_rate',
                ],
            ]) ?>
        </div>
    </div>
</div>