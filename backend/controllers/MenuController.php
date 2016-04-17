<?php

namespace backend\controllers;

use backend\components\JsonModel;
use backend\forms\MenuForm;
use common\controller\BaseController;
use common\activeRecords\Menu;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{
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
     * Lists all Menu models.
     * @param $parent
     * @return View
     */
    public function actionIndex($parent = 0)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Menu::find()->where('`menu`.parent = '.intval($parent))->joinWith('childen')->orderBy('index desc'),
            'totalCount'=> Menu::find()->where('`menu`.parent = '.intval($parent))->count()+0
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new MenuForm();
        if ($form->submit()) {
            $model = new Menu();
            $model->setAttributes($form->getAttributes());
            if(!empty($form->parent2))
                $model->parent = $form->parent2;
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else
                $form->addErrors($model->getErrors());
        }
        return $this->render('create', [
                'model' => $form,
        ]);

    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $form = new MenuForm();
        $model = $this->findModel($id);
        $form->setAttributes($model->getAttributes());

        if ($form->submit()) {
            $model->setAttributes($form->getAttributes());
            if(!empty($form->parent2))
                $model->parent = $form->parent2;
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else
                $form->addErrors($model->getErrors());
        } else {
            return $this->render('update', [
                'model' => $form,
            ]);
        }
    }

    public function actionAjaxGetChildenMenu($parent_id)
    {
        if(empty($parent_id))
            $menus = [];
        else
            $menus = ['0'=>'请选择']+\yii\helpers\ArrayHelper::map(Menu::find()->where('parent = '.intval($parent_id))->all(),'id','name');
        return JsonModel::success('获取成功',$menus);
    }

    public function actionAjaxMenuDisplay()
    {
        $id = $this->request('pk');
        $value = $this->request('value');
        $menu = $this->findModel($id);
        $menu->display = intval($value);
        if(!$menu->save())
            throw new Exception(current($menu->getFirstErrors()));
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
