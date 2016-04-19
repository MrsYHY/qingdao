<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午7:57
 */

namespace backend\controllers;

use backend\components\SearchModel;
use common\activeRecords\Prizes;
use yii\web\NotFoundHttpException;
use common\exceptions\DbException;

use \common\activeRecords\Activitys;

use common\controller\BaseController;
use yii\data\ActiveDataProvider;

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

    /**
     * 奖品创建
     */
    public function actionPrizeCreate(){

    }

    public function actionView($id){
        $model = Activitys::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到活动：{$id}对应的记录");
        }
        $dataProvider = new ActiveDataProvider([
            'query'=>$model->getPrize(),
//            'pagination' => [
//                'pageSize' => 10,
//            ],
        ]);

        return $this->render('view',compact('model','dataProvider'));
    }

    public function actionDelete(){

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



} 