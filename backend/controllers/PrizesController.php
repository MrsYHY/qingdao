<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午10:48
 */

namespace backend\controllers;

use common\activeRecords\Activitys;
use common\activeRecords\Prizes;
use common\controller\BaseController;
use yii\web\NotFoundHttpException;
use common\exceptions\DbException;
use yii\data\ActiveDataProvider;

class PrizesController extends BaseController{

    public function actionCreate($activity_id){

        $activity = Activitys::findByPk($activity_id);
        if ( empty($activity) ){
            throw new NotFoundHttpException("找不到指定的活动记录，此活动id为".$activity_id);
        }

        $model = new Prizes();
        $model->activity_id = $activity->id;
        $model->activityName = $activity->title;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {

                if($model->save()){
                    return $this->redirect(['activity/view','id'=>$model->activity_id]);
                }
            }
        }


        return $this->render('create',compact('model'));

    }

    public function actionView($id){
        $model = Prizes::findOne($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到奖品：{$id}对应的记录");
        }
        return $this->render('view',compact('model'));

    }

    public function actionUpdate($id){
        $model = Prizes::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到奖品：{$id}对应的记录");
        }
        $model->activityName = Activitys::findByPk($model->activity_id)->title;
        if ($model->load(\Yii::$app->request->post())) {
            if($model->save()){
                \Yii::$app->cache->flush();
                return $this->redirect(['activity/view', 'id' => $model->activity_id]);
            }else {
                throw new DbException("更新此记录失败");
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id){
        $model = Prizes::findByPk($id);
        if(empty($model)){
            throw new NotFoundHttpException("找不到奖品：{$id}对应的记录");
        }
        $activity_id = $model->activity_id;
        if(!$model->delete()){
            throw new DbException("删除奖品：{$id}对应的记录失败");
        }
        return $this->redirect(['activity/view','id'=>$activity_id]);
    }

} 