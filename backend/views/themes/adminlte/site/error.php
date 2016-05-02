<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title =  '发生异常';
$this->params['breadcrumbs'][] = ['label' => '发送异常'];
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($exception->getMessage())) ?>
    </div>
    <p>
       <?= nl2br($exception->getTraceAsString()) ?>
    </p>

</div>
