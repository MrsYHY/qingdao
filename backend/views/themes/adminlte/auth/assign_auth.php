<?php

use common\activeRecords\AuthItem;
use common\activeRecords\AuthItemChild;

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分配权限';
$this->params['breadcrumbs'][] = '权限管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="table-container">
                    <?= \backend\widgets\metronic\GridView::widget([
                        'id'=>'auth_table',
                        'dataProvider' => $dataProvider,
                        'filterModel'=>$authModel,
                        'columns' => [
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'name'=>'authCheckSelect',
                                'checkboxOptions'=>function( $model, $key, $index, $this){
                                        return  [
                                            'data-submit-method'=>'post',
                                            'data-ajax-submit' => 'true',
                                            'data-params' => '{"name":"'.$model->name.'"}',
                                            'checked'=>AuthItemChild::find()->where('child = :child and parent = :parent',[':child'=>$model->name,':parent'=>$_REQUEST['name']])->exists()?'checked':'',
                                            'data-ajax-url' => Url::toRoute(['auth/ajax-link-auth','id'=>$_REQUEST['name']])
                                        ];
                                }
                            ],
                            'name',
                            [
                                'attribute'=>'type',
                                'filter'=>Html::dropDownList('AuthItem[type]',$authModel->type,[''=>'请选择']+AuthItem::$typeData,['class'=>'form-control']),
                                'value'=>function($data){return AuthItem::$typeData[$data->type];}
                            ],
                            [
                                'attribute'=>'description',
                                'filter'=>''
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<js
 $(document).off('click.yiiGridView',"#auth_table input[name='authCheckSelect_all']").on('click.yiiGridView', "#auth_table input[name='authCheckSelect_all']",
    function () {
        $("#auth_table").find("input[name='authCheckSelect[]']:enabled").attr('checked', this.checked);
        $("#auth_table").find("input[name='authCheckSelect[]']:enabled").change();
 });

js;
$this->registerJs($js,\yii\web\View::POS_READY)
?>