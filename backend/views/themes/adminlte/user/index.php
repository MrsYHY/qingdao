<?php

use backend\widgets\adminLte\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend','user manage');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title?></h3>
        <div class="box-tools">
            <div class="input-group input-group-sm" >
                <a class="btn btn-circle btn-default btn-sm" href="<?= \yii\helpers\Url::toRoute('create')?>">
                    <i class="fa fa-plus"></i><span class="hidden-480">添加用户</span>
                </a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php $pajax = \backend\widgets\Pjax::begin()?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            'id',
                            'username',
                            //'auth_key',
                            //'password_hash',
                            //'password_reset_token',
                            'email:email',
                            // 'role',
                            [
                                'attribute'=>'status',
                                'format'=>'raw',
                                'value'=>function($model){
                                    $css = $model->status==\common\activeRecords\User::STATUS_ACTIVE?'success':'danger';
                                    return "<span class=\"label label-{$css}\">".\common\activeRecords\User::$statusData[$model->status].'</span>';
                                },
                                'filter'=>Html::dropDownList('SearchModel[status]',$searchModel->model->status,[''=>'请选择']+\common\activeRecords\User::$statusData,['class'=>'form-control']),
                            ],
                            //'created_at',
                            // 'updated_at',
                            ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
                        ],
                    ]); ?>
                    <?php $pajax->end();?>
                </div>
            </div>
        </div>
    </div>
</div>