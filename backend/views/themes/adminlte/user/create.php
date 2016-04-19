<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create col-md-12">

        <div class="portlet-body form">
            <?= $this->render('_form', [
                'model' => $model,
                'authList'=>$authList
            ]) ?>
        </div>
    </div>
</div>