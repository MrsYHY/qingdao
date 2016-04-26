<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-21
 * Time: 下午9:02
 */

namespace backend\controllers;


use backend\forms\DeviceForm;
use backend\services\DevicesService;
use common\activeRecords\Devices;
use common\activeRecords\LuckDrawResult;
use common\activeRecords\Zones;
use common\controller\BaseController;
use yii\data\ActiveDataProvider;

use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use common\exceptions\DbException;
use yii\web\UploadedFile;
use common\widgets\ExcelGenerator;

class DevicesController extends BaseController{

    protected  $service;
    public function getService(){
        if ($this->service instanceof DevicesService){
            return $this->service;
        }
        $this->service = new DevicesService();
        return $this->service;
    }

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

    public function actionCreate(){
        $deviceForm = new DeviceForm();
        $deviceForm->setScenario('createDevice');

        if ($deviceForm->submit()) {
            if ($deviceForm->validate()) {
                $service = $this->getService();
                $result = $service->createDevice($deviceForm);
                if ($result === false) {
                    $deviceForm->addErrors($service->getErrors());
                }else {
                    $this->redirect(['view','id'=>$result]);
                }
            }
        }
        return $this->render('device_create',compact('deviceForm'));
    }

    public function actionUpdate($id){
        $model = Devices::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到设备：{$id}对应的记录");
        }
        if ($model->load(\Yii::$app->request->post())) {
            if($model->save()){
                \Yii::$app->cache->flush();
                return $this->redirect(['view','id'=>$model->id]);
            }else {
                throw new DbException("更新此记录失败");
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($id){
        $model = Devices::findByPk($id);
        if( empty($model) ){
            throw new NotFoundHttpException("找不到设备：{$id}对应的记录");
        }
        return $this->render('device_view',compact('model'));
    }


    /**
     * 摇一摇页面添加
     */
    public function actionCreatePage(){
        $deviceForm = new DeviceForm();
        $deviceForm->setScenario('pageCreate');
        if($deviceForm->submit()){
            $deviceForm->pageIconUpload = UploadedFile::getInstance($deviceForm,'pageIconUpload');

            if ($deviceForm->pageIconUpload && $deviceForm->validate()){
                $imagePath = 'uploads/wechat/images/'.time()."_".time().".".$deviceForm->pageIconUpload->extension;
                if ($deviceForm->pageIconUpload->saveAs($imagePath)){
                    $deviceForm->pageIconUrl = 'http://www.qingdao.hikouga.com/uploads/wechat/images/1461384686_1461384686.png';//rtrim(\Yii::$app->urlManager->createAbsoluteUrl($imagePath),'.html');
                    $service = $this->getService();
                    $result = $service->pageCreate($deviceForm);
                    if ($result === false) {
                        $deviceForm->addErrors($service->getErrors());
                    }else{
                        $this->redirect(['page-view','id'=>$result]);
                    }
                }else{
                    $deviceForm->addErrors(['图片保存失败']);
                }
            }
        }

        return $this->render('page_create',compact('deviceForm'));
    }

    /**
     * @param $id 微信返回的page_id
     */
    public function actionPageView($id) {
        $service = $this->getService();
        $page = $service->pageList([$id]);var_dump($page);die;
//        if (empty($page)) {
//            throw new NotFoundHttpException('没有找到该页面，页面id为'.$id);
//        }
        $page [] = ['title'=>'title','page_url'=>'page_url','description'=>'description','comment'=>'comment','icon_url'=>'icon_url'];

        $model = new \stdClass();
        $model->title = $page[0]['title'];
        $model->description = $page[0]['description'];
        $model->page_url = $page[0]['page_url'];
        $model->comment = $page[0]['comment'];
        $model->icon_url = $page[0]['icon_url'];
        return $this->render('page_view',compact('model'));
    }

    /**
     * 只有系统有持久化的的page 才会进行删除
     * @param $id 微信返回的page_id
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPageDelete($id){
        $service = $this->getService();
        $page = $service->getPageIdBySelf($id);
        if (empty($page)) {
            throw new NotFoundHttpException('没有找到该页面，页面id为'.$id);
        }
        $result = $service->pageDelete($id);
        if ($result === true) {
            $this->redirect(['page-list']);
        }
    }

    /**
     * 只有系统有持久化的的page 才会进行修改
     * @param $id 微信返回的page_id
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPageUpdate($id){
        $service = $this->getService();
        $page = $service->getPageIdBySelf($id);
        if (empty($page)) {
            throw new NotFoundHttpException('系统中未找到该页面，页面id为'.$id);
        }
        $page = $service->pageList([$id]);
        if (empty($page)) {
            throw new NotFoundHttpException('微信平台未找到该页面，页面id为'.$id);
        }

        $page [] = ['title'=>'title','page_url'=>'page_url','description'=>'description','comment'=>'comment','icon_url'=>'icon_url'];
        $deviceForm = new DeviceForm();
        $deviceForm->setScenario('pageUpdate');
        $deviceForm->pageTitle = $page[0]['title'];
        $deviceForm->pageDescription = $page[0]['description'];
        $deviceForm->pagePageUrl = $page[0]['page_url'];
        $deviceForm->pageComment = $page[0]['comment'];
        $deviceForm->pageIconUrl = $page[0]['icon_url'];
        if ($deviceForm->submit()) {
            if ($deviceForm->validate()){
                $result = $service->pageUpdate($deviceForm);
                if ($result === false) {
                    $deviceForm->addErrors($service->getErrors());
                }else{
                    $this->request(['page_view','id'=>$id]);
                }
            }
        }
        return $this->render('page_update',compact('deviceForm'));
    }


    /**
     * 摇一摇页面列表
     */
    public function actionPageList(){
        $service = $this->getService();
        $list = $service->pageList();
//        $list [] = ['page_id'=>'page_id','title'=>'title','page_url'=>'page_url','description'=>'description','comment'=>'comment','icon_url'=>'icon_url'];
        $dataProvider = new ArrayDataProvider([
            'key'=>'page_id',
            'allModels'=>$list,
        ]);
        return $this->render('page_list',compact('dataProvider'));
    }

    public function actionExcel(){
        $query = Devices::find();
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
        ]);
        $excelHead = [
            'device_name'=>'设备名称',
            'device_keyword'=>'设备id',
            'zone_id'=>'设备所属大区',
            'sale_name'=>'促销员',
            'shake_num'=>'设备摇奖次数',
            'join_num'=>'设备摇奖人数',
            'shake_zhong_num'=>'设备中奖次数',
            'shake_award_num'=>'设备兑奖次数',
        ];
        $models = $dataProvider->getModels();
        $d = new ExcelGenerator(['excelHead'=>$excelHead,'dataProvider'=>$models,'filename'=>'设备统计','filterCallback'=>function($model){
                $data = [];
                $data [] = $model->device_name;
                $data [] = $model->device_keyword;
                $data [] = Zones::findByPk($model->id)->name;
                $data [] = $model->sale_name;
                $data [] = LuckDrawResult::find()->where(['device_id'=>$model->id])->count();
                $data [] = LuckDrawResult::getsBydeviceId($model->id);
                $data [] = LuckDrawResult::find()->where(['device_id'=>$model->id,'result'=>LuckDrawResult::ZHONG])->count();
                $data [] = LuckDrawResult::find()->where(['device_id'=>$model->id,'result'=>LuckDrawResult::ZHONG,'is_award'=>LuckDrawResult::AWARD])->count();
                return $data;
            }]);
        echo $d->run();
        \Yii::$app->end();
    }


} 