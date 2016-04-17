<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view col-md-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-bag font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">
                        用户信息                </span>
                <span class="caption-helper"><?= $this->title?></span>
            </div>
            <div class="actions">
                <a href="javascript:history.go(-1);" class="btn btn-default btn-circle">
                    <i class="fa fa-angle-left"></i>
                                            <span class="hidden-480">
                                            返回 </span>
                </a>
            </div>
        </div>
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