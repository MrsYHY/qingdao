<?php
/**
 * User: yoyo
 * Date: 15-5-29
 * Time: 下午10:56
 */
$this->title = '数据汇总';
$this->params['breadcrumbs'] = [$this->title];
$start = date("Y-m-d 00:00:00",time());
$end = date("Y-m-d 23:59:59",time());
?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo \common\activeRecords\Activitys::find()->count();?></h3>
                <p>活动数</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <?=\yii\helpers\Html::a('导出报表',\yii\helpers\Url::to(['activity/excel']),['class'=>'small-box-footer'])?>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?=\common\activeRecords\Devices::find()->count()?></h3>

                <p>设备数</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-gear-outline"></i>
            </div>
            <?=\yii\helpers\Html::a('导出报表',\yii\helpers\Url::to(['devices/excel']),['class'=>'small-box-footer'])?>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=\common\activeRecords\LuckDrawResult::find()->where("created_at>='".$start."'")->andWhere("created_at<='".$end."'")->count();?></h3>

                <p>每天摇一摇总数</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">每天摇一摇总数<i class="fa"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=\common\activeRecords\TerminalUser::find()->where('sign_in_num>=0')->count();?></h3>

                <p>总参与人数</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">总参与人数<i class="fa"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=\common\activeRecords\LuckDrawResult::getJoinNumEveryDay();?></h3>

                <p>每天参与人数</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
                        <a href="#" class="small-box-footer">每天参与人数 <i class="fa "></i></a>
        </div>
    </div>
</div>
<div class="row">

</div>