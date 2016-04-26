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
        $query = Activitys::find();
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
        ]);
        $excelHead = [
            'activity_name'=>'活动名称',
            'device_name'=>'设备名称',
            'device_id'=>'设备id',
            'zone_id'=>'设备所属大区',
            'shake_num'=>'活动总摇奖次数',
            'join_num'=>'活动总参与人数',
            'shake_zhong_num'=>'活动中奖总次数',
            'shake_award_num'=>'活动兑奖总次数',
        ];
        $models = $dataProvider->getModels();
        $data = [];
        foreach ($models as $model){
            $luckDrawResult = $model->luckDrawResult;
            if (empty($luckDrawResult)) {
                $d [] = $model->title;
                $d [] = '';
                $d [] = '';
                $d [] = '';
                $d [] = '';
                $d [] = '';
                $d [] = '';
                $d [] = '';
                $data [] = $d;
            }else {
                foreach ($luckDrawResult as $l) {
                    $d [] = $model->title;
                    $device = Devices::findByPk($l->device_id);
                    $d [] = $device->device_name;
                    $d [] = $device->device_keyword;
                    $d [] = Zones::findByPk($device->zone_id)->name;
                    $d [] = LuckDrawResult::find()->where(['device_id'=>$l->device_id,'activity_id'=>$model->id])->count();
                    $d [] = LuckDrawResult::find()->where(['device_id'=>$l->device_id,'activity_id'=>$model->id])->select(['user_id'])->distinct()->count();
                    $d [] =  LuckDrawResult::find()->where(['device_id'=>$l->device_id,'activity_id'=>$model->id,'result'=>LuckDrawResult::ZHONG])->count();
                    $d [] = LuckDrawResult::find()->where(['device_id'=>$l->device_id,'activity_id'=>$model->id,'result'=>LuckDrawResult::ZHONG,'is_award'=>LuckDrawResult::AWARD])->count();
                    $data [] = $d;
                }
            }
        }
        $d = new ExcelGenerator(['excelHead'=>$excelHead,'dataProvider'=>$data,'filename'=>'活动统计']);
        echo $d->run();
        \Yii::$app->end();
    }




} 