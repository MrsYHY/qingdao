<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\activeRecords\Zones */

$this->title = '大区创建';
$this->params['breadcrumbs'][] = '大区管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zones-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
