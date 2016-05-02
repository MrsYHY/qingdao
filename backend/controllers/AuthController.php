<?php

namespace backend\controllers;

use backend\services\AuthService;

use common\controller\BaseController;
use common\activeRecords\AuthItem;
use common\activeRecords\AuthItemChild;
use common\exceptions\DbException;


use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * AuthController implements the CRUD actions for AuthItem model.
 */
class AuthController extends BaseController
{

    protected $service;

    public function getService(){
        if($this->service instanceof AuthService){
            return $this->service;
        }
        $this->service = new AuthService();
        return $this->service;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $authItem = new AuthItem();
        $dataProvider  = $authItem->search(Yii::$app->request->get());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterModel'=>$authItem
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($name)
    {
        $service = $this->getService();
        $authItem = AuthItem::findByPk($name);
        $dataProvider = null;
        if(empty($authItem)){
            throw new NotFoundHttpException("找不到权限：{$name}对应的记录");
        }else{
            $dataProvider = $service->view($authItem);
        }
        return $this->render('view', [
            'model' => $authItem,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $routes = $this->readAuth();
        $exist = AuthItem::find()->select('name')->createCommand()->queryAll(\PDO::FETCH_COLUMN);
        $routes = array_diff($routes,$exist);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'name' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'routes'=>$routes
            ]);
        }
    }

    private function readAuth()
    {
        $dir = Yii::$app->getBasePath().'/controllers/';
        $routes = [];
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if($file=='.'||$file=='..')
                    continue;
                if(is_dir($dir.$file)){
                    $dh2 = opendir($dir.$file);
                    while (($file2 = readdir($dh2)) !== false) {
                        if(!is_file($dir.$file.'\\'.$file2))
                            continue;
                        $contoller = str_replace('Controller','',$file2);
                        $content = file_get_contents($dir.$file2);
                        preg_match_all('/public\s+function\s+action([^\s(]{2,})[\s(]/',$content,$actions);
                        foreach($actions[1] as $route){
                            $route = trim(preg_replace("/([A-Z])/", '-$1',$route),'-');
                            $route = strtolower($file.'/'.$contoller.'/'.$route);
                            $routes[$route] =  $route;
                        }
                    }
                }else{
                    if($file === 'SiteController.php')
                        continue;
                    $contoller = str_replace('Controller.php','',$file);
                    $contoller = ltrim(preg_replace("/([A-Z])/", '-$1',$contoller),'-');
                    $content = file_get_contents($dir.$file);
                    preg_match_all('/public\s+function\s+action([^\s(]{2,})[\s(]/',$content,$actions);
                    foreach($actions[1] as $route){
                        //str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
                        $route = trim(preg_replace("/([A-Z])/", '-$1',$route),'-');
                        $route = strtolower($contoller.'/'.$route);
                        $routes[$route] =  $route;
                    }
                }

            }
            closedir($dh);
        }
        return $routes;
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $model = AuthItem::findByPk($name);
        if(empty($model)){
            throw new NotFoundHttpException("找不到权限：{$name}对应的记录");
        }
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->cache->flush();
                return $this->redirect(['view', 'name' => $model->name]);
            }else {
                throw new DbException("更新此记录失败");
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($name)
    {
        $model = AuthItem::findByPk($name);
        if(empty($model)){
            throw new NotFoundHttpException("找不到权限：{$name}对应的记录");
        }
        if(!$model->delete()){
            throw new DbException("删除权限：{$name}对饮的记录失败");
        }
        Yii::$app->cache->flush();
        return $this->redirect(['index']);
    }

    public function actionAssignAuth($name)
    {
        $model = AuthItem::findByPk($name);
        if(empty($model)){
            throw new NotFoundHttpException("找不到权限：{$name}对应的记录");
        }
        $auth = new AuthItem();
        if(isset($_GET['AuthItem']))
            $auth->setAttributes($_GET['AuthItem']);
        $query = AuthItem::find();
        $query->where('name != :self',[':self'=>$name]);
        if (!empty($auth->name))
            $query->andWhere(['like','name',$auth->name]);
        if($auth->type)
            $query->andWhere(['type'=>$auth->type]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>['defaultPageSize'=>10]
        ]);
        return $this->render('assign_auth',[
            'dataProvider'=>$dataProvider,
            'authModel'=>$auth,
            'parent'=>$name
        ]);
    }

    public function actionAjaxLinkAuth()
    {
        $result = new \stdClass();
        $result->code = -1;
        $result->message = '未知错误';
        $id = $this->request("id");
        $isLink = $this->request("isSelect");//是否授予角色、权限或资源
        $auth = $this->request("name");
        if($isLink === "true")
        {
            $itemChild = new AuthItemChild();
            $itemChild->child = $auth;
            $itemChild->parent = $id;
            $error = $itemChild->getFirstErrors();
            if(!$itemChild->save()){
                $result->message = $error;
                $this->failByJson($result);
            }
            Yii::$app->cache->flush();
        }else{
            $itemChild = AuthItemChild::findOne(['child' =>$auth, 'parent'=> $id]);
            if($itemChild == null){
                $result->message = "找不到对应的记录";
                $this->failByJson($result);
            }
            if(!$itemChild->delete()){
               $result->message = "移除授权的权限项失败";
                $this->failByJson($result);
            }
            Yii::$app->cache->flush();
        }
        $result->code = 1000;
        $result->message = "操作成功";
        $this->successByJson($result);
    }

}
