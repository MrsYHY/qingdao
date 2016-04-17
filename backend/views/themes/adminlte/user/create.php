<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create col-md-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-basket font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">
                           <?= Html::encode($this->title) ?>
                    </span>
                <span class="caption-helper"><?= Html::encode($this->title) ?></span>
            </div>
            <div class="actions">
                <a href="javascript:history.go(-1);" class="btn btn-default btn-circle">
                    <i class="fa fa-angle-left"></i>
                    <span class="hidden-480">返回 </span>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            <?= $this->render('_form', [
                'model' => $model,
                'authList'=>$authList
            ]) ?>
        </div>
    </div>
</div>