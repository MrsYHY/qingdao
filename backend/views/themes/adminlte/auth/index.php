<?php

use backend\widgets\metronic\GridView;
use common\activeRecords\AuthItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限列表';
$this->params['h1'] = '权限管理';
$this->params['breadcrumbs'][] = $this->title;
\backend\assets\DataTablesAsset::register($this);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">权限列表</h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm">
                        <?= Html::a('<i class="fa fa-plus"></i><span class="hidden-480">添加权限</span>', ['create'], ['class' => 'btn btn-default btn-sm']) ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="get">
                                <div class="dataTables_filter">
                                    <input type="hidden" name="r" value="auth/index">
                                    <div class="form-group">
                                        <label>名字：</label>
                                        <?= Html::input('text','AuthItem[name]',$filterModel->name,['class'=>'form-control  input-sm'])?>
                                    </div>
                                    <div class="form-group">
                                        <label>类型：</label>
                                        <?= Html::dropDownList('AuthItem[type]',$filterModel->type,[''=>'请选择']+AuthItem::$typeData,['class'=>'form-control form-filter input-sm'])?>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php $pajax = \backend\widgets\Pjax::begin()?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable" role="grid" >
                            <thead>
                            <tr>
                                <th>名字</th>
                                <th>类型</th>
                                <th>描述</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($dataProvider->getCount() == 0): ?>
                                <tr><td colspan="4">暂无数据</td></tr>
                            <?php else:?>
                                <?php foreach($dataProvider->getModels() as $model):?>
                                    <tr>
                                        <td><?= $model->name?></td>
                                        <td><?=AuthItem::$typeData[$model->type]?></td>
                                        <td><?= $model->description?></td>
                                        <td><?=
                                            Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['auth/view','name'=>$model->name],['data-pjax'=>0]).
                                            Html::a('<span class="glyphicon glyphicon-pencil"></span>',['auth/update','name'=>$model->name],['data-pjax'=>0]).
                                            Html::a('<span class="glyphicon glyphicon-tasks"></span>',['auth/assign-auth','name'=>$model->name],['data-pjax'=>0]).
                                            Html::a('<span class="glyphicon glyphicon-trash"></span>',['auth/delete','name'=>$model->name],['data-pjax'=>0,'data-confirm'=>'您确定要删除此项吗？','data-method'=>"post"])
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                <?= \yii\widgets\LinkPager::widget([
                                    'pagination'=>$dataProvider->getPagination(),
                                ])?>
                            </div>
                        </div>
                    </div>
                    <?php $pajax->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

