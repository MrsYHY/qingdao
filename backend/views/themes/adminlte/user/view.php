<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view col-md-12">
    <div class="portlet light">
        <div class="portlet-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                  'username',
                  'email:email',
                  [
                      'label'=>'状态',
                      'value'=>\common\activeRecords\User::$statusData[$model->status],
                  ],
                  'created_at:date',
                  'updated_at:date',
                ],
            ]) ?>
        </div>
    </div>
</div>