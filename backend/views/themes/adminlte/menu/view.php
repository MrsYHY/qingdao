<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = \yii\helpers\Html::a('菜单管理', ['menu/index']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="box">
        <div class="box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $model->name?></h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" >
                        <a href="javascript:history.go(-1);" class="btn btn-default btn-sm ">
                            <i class="fa fa-angle-left"></i><span class="hidden-480">返回 </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'template'=>'<tr><th style="text-align: right">{label}</th><td>{value}</td></tr>',
                'attributes' => [
                    'route',
                    'name',
                    'desc:ntext',
                    ['attribute'=>'display','format'=>'boolean'],
                    'index',
                ],
            ]) ?>
        </div>
</div>

