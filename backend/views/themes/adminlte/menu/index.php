<?php

use backend\widgets\adminLte\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">菜单列表</h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" >
                        <?= Html::a('<i class="fa fa-plus"></i><span class="hidden-480">添加菜单</span>', ['create'], ['class' => 'btn btn-circle btn-default btn-sm']) ?>

                    </div>
                </div>
            </div>
            <div class="box-body">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'route',
                        'name',
                        //'desc:ntext',
                        ['attribute'=>'childen','format'=>'raw','label'=>'子菜单','value'=>function($data){
                            if(count($data->childen)>0)
                                return Html::a('查看',\yii\helpers\Url::toRoute(['menu/index','parent'=>$data->id]));
                            else
                                return '----';
                        }],
                        ['attribute'=>'icon','format'=>'raw',
                            'value'=>function($model){
                                return $model->icon?Html::tag('i','',['class'=>$model->icon]):'无';
                            }
                        ],
                        [
                            'class'=>'backend\widgets\metronic\grid\SwitchColumn',
                            'attribute'=>'display',

                            'ajaxOptions'=>['url'=>\yii\helpers\Url::toRoute(['menu/ajax-menu-display'])]
                        ],
                        'index',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header'=>'操作'
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
