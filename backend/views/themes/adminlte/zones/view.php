<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\activeRecords\Zones */

$this->title = '大区查看';
$this->params['breadcrumbs'][] = '大区管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zones-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

</div>
