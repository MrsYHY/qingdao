<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '页面详情';
$this->params['breadcrumbs'][] = '设备管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view col-md-12">
    <div class="portlet light">
        <div class="portlet-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    'page_url',
                    'icon_url',
                    'description',
                    'comment',
                ],
            ]) ?>
        </div>
    </div>
</div>