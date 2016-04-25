<?php

use yii\helpers\Html;
use backend\widgets\metronic\ActiveForm;
use \common\activeRecords\LuckDrawResult;




/* @var $this yii\web\View */
/* @var $searchModel backend\components\SearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '页面列表';
$this->params['breadcrumbs'][] = '设备管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title?></h3>
        <?php $form = ActiveForm::begin(['method'=>'get'])?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($luckDrawForm,'activity_id')->dropDownList([''=>'全部']+\yii\helpers\ArrayHelper::map(\common\activeRecords\Activitys::find()->all(),'id','title'));?>
            </div>
            <div class="col-md-6">
                <div class="row"><?= $form->field($luckDrawForm,'result')->dropDownList([''=>'全部',LuckDrawResult::ZHONG=>'中奖',LuckDrawResult::NOT_ZHONG=>'没中奖'])?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            <?= $form->field($luckDrawForm,'is_award')->dropDownList([''=>'全部',LuckDrawResult::AWARD=>'已兑奖',LuckDrawResult::NOT_AWARD=>'未中奖'])?>
            </div>
            <div class="col-md-6">
                <?= $form->field($luckDrawForm,'device_id')->dropDownList([''=>'全部']+\yii\helpers\ArrayHelper::map(\common\activeRecords\Devices::find()->all(),'id','device_keyword'));?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-5 col-md-3">
                <button type="submit" class="btn btn-primary" name="excel" value="">查询</button><button type="submit" style="margin-left: 20px;" name="excel"class=" btn btn-primary" value="excel">excel导出</button>
            </div>
        </div>
        <?php $form->end()?>
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
                    <?= \backend\widgets\metronic\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'id',
                            [
                            'attribute'=>'activity_id',
                            'value'=>function($model){return \common\activeRecords\Activitys::findByPk($model->activity_id)->title;}
                            ],
                            [
                                'attribute'=>'result',
                                'value'=>function($model){return $model->result == LuckDrawResult::ZHONG ? '中奖':'未中奖';}
                            ],
                            [
                                'attribute'=>'user_id',
                                'value'=>function($model){return \common\activeRecords\TerminalUser::findByPk($model->user_id)->terminal_user_token;}
                            ],
                            [
                                'attribute'=>'prize_id',
                                'value'=>function($model){
                                        if ($model->result == LuckDrawResult::NOT_ZHONG){return '';}
                                        return \common\activeRecords\Prizes::findByPk($model->prize_id)->name;
                                    }
                            ],
                            [
                                'attribute'=>'prize_level',
                                'value'=>function($model){
                                        if ($model->result == LuckDrawResult::NOT_ZHONG){return '';}
                                        return \common\activeRecords\Prizes::$prizeLevel[$model->prize_level];
                                    }
                            ],
                            'created_at',
                            [
                                'attribute'=>'is_award',
                                'value'=>function($model){
                                        if($model->result == LuckDrawResult::NOT_ZHONG){return '';}
                                        if($model->is_award == LuckDrawResult::AWARD){
                                            return '已兑奖';
                                        }elseif($model->is_award == LuckDrawResult::NOT_AWARD){
                                            return '未兑奖';
                                        }
                                        return '';
                                        }
                            ],
                            'win_code'
                        ],
                    ]);?>
                    <?php $pajax->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>