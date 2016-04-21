<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-21
 * Time: 下午9:02
 */

namespace backend\controllers;


use common\activeRecords\Devices;
use common\controller\BaseController;
use yii\data\ActiveDataProvider;

use yii\web\NotFoundHttpException;
use common\exceptions\DbException;

class DevicesController extends BaseController{

    public function actionList(){
        $dataProvider = new ActiveDataProvider(
            [
                'query'=>Devices::find(),
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


    public function actionDelete($id){
        $model = Devices::findByPk($id);
        if(empty($model)){
            throw new NotFoundHttpException("找不到设备：{$id}对应的记录");
        }
        if(!$model->delete()){
            throw new DbException("删除设备：{$id}对应的记录失败");
        }
        return $this->redirect(['list']);
    }

    public function actionUpdate($id){
        $model = Devices::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到设备：{$id}对应的记录");
        }
        if ($model->load(\Yii::$app->request->post())) {
            if($model->save()){
                \Yii::$app->cache->flush();
                return $this->redirect(['list']);
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