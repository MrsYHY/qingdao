<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午7:57
 */

namespace backend\controllers;

use backend\components\SearchModel;
use common\activeRecords\Devices;
use common\activeRecords\LuckDrawResult;
use common\activeRecords\Prizes;
use common\activeRecords\Zones;
use yii\redis\ActiveRecord;
use yii\web\NotFoundHttpException;
use common\exceptions\DbException;

use \common\activeRecords\Activitys;

use common\controller\BaseController;
use yii\data\ActiveDataProvider;

use common\widgets\ExcelGenerator;

class ActivityController extends BaseController{

    /**
     * 活动列表
     */
    public function actionList(){

        $dataProvider = new ActiveDataProvider(
            [
                'query'=>Activitys::find(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        return $this->render(
            'list',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * 添加活动
     */
    public function actionCreate()
    {
        $model = new Activitys();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->save()){
                    return $this->redirect(['list']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionView($id){
        $model = Activitys::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到活动：{$id}对应的记录");
        }
        $dataProvider = new ActiveDataProvider([
            'query'=>$model->getPrize(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view',compact('model','dataProvider'));
    }

    public function actionDelete($id){
        $model = Activitys::findByPk($id);
        if(empty($model)){
            throw new NotFoundHttpException("找不到活动：{$id}对应的记录");
        }
        if(!$model->delete()){
            throw new DbException("删除活动：{$id}对应的记录失败");
        }
        return $this->redirect(['list']);
    }

    public function actionUpdate($id){
        $model = Activitys::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到活动：{$id}对应的记录");
        }
        if ($model->load(\Yii::$app->request->post())) {
            if($model->save()){
                \Yii::$app->cache->flush();
                return $this->redirect(['view', 'id' => $model->id]);
            }else {
                throw new DbException("更新此记录失败");
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionExcel(){
        $dataProvider = LuckDrawResult::findBySql('select * from luck_draw_result left join activitys on activitys.id=luck_draw_result.activity_id')->all();
        $excelHead = [
            'activity_name'=>'活动名称',
            'start_time'=>'活动开始时间',
            'end_time'=>'活动结束时间',
            'device_name'=>'设备名称',
            'device_id'=>'设备id',
            'zone_id'=>'设备所属大区',
            'shake_num'=>'活动总摇奖次数',
            'join_num'=>'活动总参与人数',
            'shake_zhong_num'=>'活动中奖总次数',
            'shake_award_num'=>'活动兑奖总次数',
        ];
        $d = new ExcelGenerator(['excelHead'=>$excelHead,'dataProvider'=>$dataProvider,'filename'=>'活动统计','filterCallback'=>function($model){
                $data = [];
                $data [] = $model->title;
                $activity = Activitys::findByPk($model->activity_id);
                $data [] = empty($activity->start_time) ? '没有设置' : $activity->start_time;
                $data [] = empty($activity->end_time) ? '没有设置' : $activity->end_time;
                $device = Devices::findByPk($model->device_id);
                if (empty($device)){
                    $data [] = '';
                    $data [] = '';
                    $data [] = '';
                }else{
                    $data [] = $device->device_name;
                    $data [] = $device->device_keyword;
                    $zone = Zones::findByPk($device->zone_id);
                    if (empty($zone)){
                        $data [] = '';
                    }else{
                        $data [] = $zone->name;
                    }
                }


                $data [] = LuckDrawResult::find()->where(['device_id'=>$model->device_id,'activity_id'=>$model->activity_id])->count();
                $data [] = LuckDrawResult::find()->where(['device_id'=>$model->device_id,'activity_id'=>$model->activity_id])->select(['user_id'])->distinct()->count();
                $data [] =  LuckDrawResult::find()->where(['device_id'=>$model->device_id,'activity_id'=>$model->activity_id,'result'=>LuckDrawResult::ZHONG])->count();
                $data [] = LuckDrawResult::find()->where(['device_id'=>$model->device_id,'activity_id'=>$model->activity_id,'result'=>LuckDrawResult::ZHONG,'is_award'=>LuckDrawResult::AWARD])->count();
                return $data;
            }]);
        echo $d->run();
        \Yii::$app->end();
    }




} 